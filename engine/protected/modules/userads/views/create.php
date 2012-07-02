<h1>Добавление объявления</h1>
<?php

$this->widget('zii.widgets.CMenu', array(
	'items' => array(
		array('label'=>'Управление объявлениями', 'url'=>array('index')),
	)
));

$this->renderPartial('_form',array(
	'model'=>$model,
	'categories' => $categories,
));
