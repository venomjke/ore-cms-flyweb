<?php

$this->menu=array(
	array('label'=>tt('Add value', 'windowto'), 'url'=>array('create')),
	array('label'=>tt('Delete value', 'windowto'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),
		'confirm'=>tt('Are you sure you want to delete this value?', 'windowto'))),
);

$this->adminTitle = tt('Update value', 'windowto');
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>