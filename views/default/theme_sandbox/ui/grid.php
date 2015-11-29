<h3>Spans</h3>
<div>
	<div class="elgg-small-6">
		<div class="placeholder">
			.elgg-small-6
		</div>
	</div>
	<div class="elgg-small-3">
		<div class="placeholder">
			.elgg-small-3
		</div>
	</div>
</div>

<div class="elgg-spacer"></div>

<h3>4-column static row</h3>
<div class="elgg-row">
	<?php
	for ($i = 1; $i <= 4; $i++) {
		?>
		<div class="elgg-small-3">
			<div class="placeholder">
				.elgg-small-3
			</div>
		</div>
		<?php
	}
	?>
</div>

<div class="elgg-spacer"></div>

<h3>Fluid row</h3>
<div class="elgg-row">
	<div class="elgg-small-12 elgg-medium-6">
		<div class="placeholder">
			.elgg-small-12.elgg-medium-6.elgg-large-6
		</div>
	</div>
	<div class="elgg-small-6 elgg-medium-2">
		<div class="placeholder">
			.elgg-small-6.elgg-medium-2
		</div>
	</div>
	<div class="elgg-small-6 elgg-medium-4 placeholder">
		.elgg-small-6.elgg-medium-4
	</div>
	<div class="elgg-small-12 elgg-medium-7 elgg-large-9 placeholder">
		<p class="elgg-small-12">
			div.elgg-small-12.elgg-medium-7.elgg-large-9
		</p>
		<p class="elgg-small-7 placeholder">
			<span class="elgg-small-12">
				p.elgg-small-7
			</span>
			<span class="elgg-small-3 placeholder">
				span.elgg-small-3
			</span>
			<span class="elgg-small-9 placeholder">
				span.elgg-small-9
			</span>
		</p>
		<p class="elgg-small-5 placeholder">
			p.elgg-small-5
		</p>
	</div>
	<div class="elgg-small-12 elgg-medium-5 elgg-large-3 placeholder">
		.elgg-small-12.elgg-medium-5.elgg-large-3
	</div>
</div>


<div class="elgg-spacer"></div>

<h3>Row with offsets</h3>
<div class="elgg-row">
	<div class="elgg-small-3 elgg-offset-small-2 placeholder">
		.elgg-small-3.elgg-offset-small-2
	</div>
	<div class="elgg-small-2 elgg-offset-small-1 placeholder">
		.elgg-small-2.elgg-offset-small-1
	</div>
</div>

<div class="elgg-spacer"></div>

<h3>Row with columns</h3>
<div class="elgg-row">
	<?php
	for ($i = 1; $i <= 10; $i++) {
		?>
		<div class="elgg-column elgg-small-3 placeholder">
			.elgg-column.elgg-small-3
		</div>
		<?php
	}
	?>
</div>

<h3>Row with columns and offsets</h3>
<div class="elgg-row">
	<div class="elgg-column elgg-small-3 elgg-offset-small-2 placeholder">
		.elgg-column.elgg-small-3.elgg-offset-small-2
	</div>
	<div class="elgg-column elgg-small-2 elgg-offset-small-1 placeholder">
		.elgg-column.elgg-small-2.elgg-offset-small-1
	</div>
</div>

<div class="elgg-spacer"></div>

<h3>5 column static block grid (.elgg-gallery-small-5)</h3>
<div class="elgg-gallery-small-5">
	<?php
	for ($i = 1; $i <= 10; $i++) {
		$height = rand(50, 150);
		?>
		<div class="placeholder" style="min-height: <?= $height ?>px">
			Element <?= $i ?>
		</div>
		<?php
	}
	?>
</div>

<div class="elgg-spacer"></div>

<h3>Fluid block grid (.elgg-gallery-small-2.elgg-gallery-medium-4.elgg-gallery-large-6)</h3>
<div class="elgg-gallery-small-2 elgg-gallery-medium-4 elgg-gallery-large-6">
	<?php
	for ($i = 1; $i <= 12; $i++) {
		$height = rand(50, 150);
		?>
		<div class="placeholder" style="min-height: <?= $height ?>px">
			Element <?= $i ?>
		</div>
		<?php
	}
	?>
</div>

<div class="elgg-spacer"></div>