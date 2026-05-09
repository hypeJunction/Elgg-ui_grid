#!/bin/bash
set -e

# Wait for MySQL to be ready
echo "Waiting for MySQL..."
until php -r "new PDO('mysql:host=${ELGG_DB_HOST:-db}', '${ELGG_DB_USER:-elgg}', '${ELGG_DB_PASS:-elgg}');" 2>/dev/null; do
    sleep 1
done
echo "MySQL is ready."

cd /var/www/html

# Ensure the data root is writable by the apache user. The host-mounted
# named volume comes up root-owned, which would override the chown done
# at image build time and break Elgg's cache writes.
mkdir -p "${ELGG_DATA_ROOT:-/var/www/data/}"
chown -R www-data:www-data "${ELGG_DATA_ROOT:-/var/www/data/}"

# Check if Elgg is already installed
if [ ! -f /var/www/html/.elgg-installed ]; then
    echo "Installing Elgg 7.x..."

    # Create settings.php
    mkdir -p elgg-config
    cat > elgg-config/settings.php <<'SETTINGS_TEMPLATE'
<?php
global $CONFIG;
if (!isset($CONFIG)) {
    $CONFIG = new \stdClass;
}
SETTINGS_TEMPLATE

    cat >> elgg-config/settings.php <<SETTINGS_VALUES
\$CONFIG->dbuser = '${ELGG_DB_USER:-elgg}';
\$CONFIG->dbpass = '${ELGG_DB_PASS:-elgg}';
\$CONFIG->dbname = '${ELGG_DB_NAME:-elgg}';
\$CONFIG->dbhost = '${ELGG_DB_HOST:-db}';
\$CONFIG->dbport = '3306';
\$CONFIG->dbprefix = 'elgg_';
\$CONFIG->dbencoding = 'utf8mb4';
\$CONFIG->dataroot = '${ELGG_DATA_ROOT:-/var/www/data/}';
\$CONFIG->wwwroot = '${ELGG_SITE_URL:-http://localhost/}';
\$CONFIG->cacheroot = '${ELGG_DATA_ROOT:-/var/www/data/}cache/';
\$CONFIG->assetroot = '${ELGG_DATA_ROOT:-/var/www/data/}assets/';
SETTINGS_VALUES

    # Run the installer
    php -r "
        require_once 'vendor/autoload.php';

        \$params = [
            'dbuser' => '${ELGG_DB_USER:-elgg}',
            'dbpassword' => '${ELGG_DB_PASS:-elgg}',
            'dbname' => '${ELGG_DB_NAME:-elgg}',
            'dbhost' => '${ELGG_DB_HOST:-db}',
            'dbport' => '3306',
            'dbprefix' => 'elgg_',
            'sitename' => 'Elgg 7.x Migration Test',
            'siteemail' => '${ELGG_ADMIN_EMAIL:-admin@example.com}',
            'wwwroot' => '${ELGG_SITE_URL:-http://localhost/}',
            'dataroot' => '${ELGG_DATA_ROOT:-/var/www/data/}',
            'displayname' => 'Admin',
            'email' => '${ELGG_ADMIN_EMAIL:-admin@example.com}',
            'username' => 'admin',
            'password' => '${ELGG_ADMIN_PASSWORD:-admin1234567890123456}',
        ];

        \$installer = new \ElggInstaller();
        \$installer->batchInstall(\$params);
        echo 'Elgg 7.x installed successfully.' . PHP_EOL;
    " 2>&1 || echo "Install completed (check for errors above)."

    # Symlink core plugins from vendor/elgg/elgg/mod into /var/www/html/mod
    # so generateEntities() can discover them (Elgg looks in mod/ only).
    echo "Linking core plugins..."
    for src in /var/www/html/vendor/elgg/elgg/mod/*; do
        [ -d "$src" ] || continue
        name="$(basename "$src")"
        if [ ! -e "/var/www/html/mod/$name" ]; then
            ln -s "$src" "/var/www/html/mod/$name"
        fi
    done

    # Activate plugins in priority order
    echo "Activating plugins..."
    PLUGIN_ORDER_FILE="/var/www/html/mod/.plugin-order.txt"
    if [ -f "$PLUGIN_ORDER_FILE" ]; then
        echo "Using ordered activation from .plugin-order.txt"
        php -r "
            require_once 'vendor/autoload.php';
            \$app = \Elgg\Application::getInstance();
            \$app->bootCore();
            _elgg_services()->plugins->generateEntities();
            \$order = file('$PLUGIN_ORDER_FILE', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            \$activated = 0;
            \$failed = [];
            foreach (\$order as \$id) {
                \$id = trim(\$id);
                if (empty(\$id) || \$id[0] === '#') continue;
                \$plugin = elgg_get_plugin_from_id(\$id);
                if (!\$plugin) { echo 'Plugin not found: ' . \$id . PHP_EOL; continue; }
                if (\$plugin->isActive()) { \$activated++; continue; }
                try {
                    \$plugin->activate();
                    \$activated++;
                    echo '  + ' . \$id . PHP_EOL;
                } catch (\Throwable \$e) {
                    \$failed[] = \$id . ': ' . \$e->getMessage();
                }
            }
            echo \$activated . ' plugin(s) activated.' . PHP_EOL;
            if (!empty(\$failed)) {
                echo count(\$failed) . ' plugin(s) failed:' . PHP_EOL;
                foreach (\$failed as \$f) echo '  - ' . \$f . PHP_EOL;
            }
        " 2>&1 || echo "Plugin activation completed (check for errors above)."
    else
        echo "No .plugin-order.txt found, activating all plugins..."
        php -r "
            require_once 'vendor/autoload.php';
            \$app = \Elgg\Application::getInstance();
            \$app->bootCore();
            _elgg_services()->plugins->generateEntities();
            \$plugins = elgg_get_plugins('inactive');
            \$failed = [];
            foreach (\$plugins as \$plugin) {
                try { \$plugin->activate(); }
                catch (\Throwable \$e) { \$failed[] = \$plugin->getID() . ': ' . \$e->getMessage(); }
            }
            if (empty(\$failed)) { echo 'All plugins activated.' . PHP_EOL; }
            else {
                echo count(\$failed) . ' plugin(s) failed:' . PHP_EOL;
                foreach (\$failed as \$f) echo '  - ' . \$f . PHP_EOL;
            }
        " 2>&1 || echo "Plugin activation completed (check for errors above)."
    fi

    # Wipe simplecache + view_locations so the next request rebuilds the
    # consolidated CSS/JS bundles to include freshly-activated plugin views.
    php -r "
        require_once 'vendor/autoload.php';
        \$app = \Elgg\Application::getInstance();
        \$app->bootCore();
        elgg_clear_caches();
        echo 'Caches cleared after plugin activation.' . PHP_EOL;
    " 2>&1 || echo "Cache clear completed (check for errors above)."

    chown -R www-data:www-data /var/www/data
    touch /var/www/html/.elgg-installed
    echo "Elgg 7.x setup complete."
fi

# Start Apache
echo "Starting Apache..."
exec apache2-foreground
