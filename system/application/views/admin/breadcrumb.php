<div class="pull-right">
	<ol class="breadcrumb">
		<li>
			<a href="<?= base_url('admin/') ?>"><?= lang('home') ?></a>
		</li>
		<?php
		foreach ($breadcrumb as $b) {
			if ($b['link'] === '#') {
				echo '<li class="active">' . $b['page'] . '</li>';
			} else {
				echo '<li><a href="' . $b['link'] . '">' . $b['page'] . '</a></li>';
			}
		}
		?>
	</ol>
</div>