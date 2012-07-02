<?php

if($page){
	if($page->page_title){
		echo '<h1>'.$page->page_title.'</h1>';
	}

	if($page->page_body){
		echo $page->page_body;
	}

	if ($page->widget){
		echo '<div class="clear">';
		Yii::import('application.modules.'.$page->widget.'.components.*');
		if($page->widget == 'contactform'){
			$this->widget('ContactformWidget', array('page' => 'index'));
		} else {
			$this->widget(ucfirst($page->widget).'Widget');
		}
		echo '</div>';
	}
}

