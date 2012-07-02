<?php
$this->breadcrumbs=array(
	tt("FAQ")=>array('index'),
	tt("Manage article")=>array('admin'),
	$model->page_title => array('view','id'=>$model->id),
	tt("Update FAQ"),
);

$this->menu=array(
	array('label' => tt("Manage FAQ"), 'url'=>array('/articles/backend/main/admin')),
	array('label'=>tt("Add FAQ"), 'url'=>array('/articles/backend/main/create')),
	array('label'=>tt('Delete FAQ'), 'url'=>'#',
		'linkOptions'=>array(
			'submit'=>array('delete','id'=>$model->id),
			'confirm'=>tt('Are you sure you want to delete this FAQ?')
		)
	),

);

$this->adminTitle = tt("Update FAQ");

?>

<?php echo $this->renderPartial('/backend/_form', array('model'=>$model)); ?>