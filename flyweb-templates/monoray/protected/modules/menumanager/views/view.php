<?php

$this->pageTitle .= ' - '.$model->title;

if($model->page_title){
	echo '<h1>'.$model->page_title.'</h1>';
}

if($model->page_body){
	echo '<p class="desc">'.$model->page_body.'</p>';
}


if ($model->widget){
	echo '<div class="clear">';
	Yii::import('application.modules.'.$model->widget.'.components.*');
	$this->widget(ucfirst($model->widget).'Widget');
	echo '</div>';
}
