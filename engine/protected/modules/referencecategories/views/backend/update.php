<?php
$this->breadcrumbs=array(
	Yii::t('common', 'References') => array('/site/viewreferences'),
	tt('Manage reference categories')=>array('admin'),
	tt('Edit category:').' '.$model->catTitle(),
);

$this->menu=array(
	/*array('label'=>tt('Manage reference categories'), 'url'=>array('admin')),
	array('label'=>tt('Add category'), 'url'=>array('create')),
	array('label'=>tt('Delete category'), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>tt('Are you sure you want to delete this category?'))),*/
	array('label'=>tt('Add reference category'), 'url'=>array('/referencecategories/backend/main/create')),

);
$this->adminTitle = tt('Edit category:').' '.$model->catTitle();
?>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>