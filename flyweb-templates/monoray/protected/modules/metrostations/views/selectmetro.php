<div>
	<?php
	$fancy = isset($isFancy)? $isFancy : 0;
	$this->widget('application.modules.metrosearch.components.MetrosearchWidget', array(
		'list' => $list,
		'isFancy' => $fancy,
	)); ?>
</div>
