<?php
$this->breadcrumbs=array(
	Yii::t('common', 'User managment') => array('admin'),
	tt('User\'s bookings'),
);
$this->menu=array(
	array('label'=>tt('Add user'), 'url'=>array('/users/backend/main/create')),
);

$this->adminTitle = tt('User\'s bookings').': '.$user->email.($user->username != '' ? ' ('.$user->username.')' : '');

foreach($model as $item){
	$this->renderPartial('_booking',array(
		'model'=>$item,
	));
}
if(isset($pages) && $pages){
	$this->widget('itemPaginator',array('pages' => $pages));
}
?>
<br/><br/>