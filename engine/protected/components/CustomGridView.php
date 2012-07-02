<?php

Yii::import('zii.widgets.grid.CGridView');

class CustomGridView extends CGridView {
	public $pager=array('class'=>'itemPaginator');
	public $template="{summary}\n{pager}\n{items}\n{pager}";
}