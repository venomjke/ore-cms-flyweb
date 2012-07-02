<?php
foreach ($news as $item){
	echo '<div>';
	echo CHtml::link($item->title, $item->getUrl(), array('class'=>'title')).' &nbsp; <font class="date">'.$item->dateCreated.'</font>';
	echo '<p class="desc">';
	echo truncateText(
		$item->body,
		param('newsModule_truncateAfterWords', 10),
		CHtml::link(tt('Read more &raquo;', 'news'), $item->getUrl())
	);
	echo '</p>';
	echo '</div>';
}

if(!$news){
	echo tt('News list is empty.', 'news');
}

if($pages){
	$this->widget('itemPaginator',array('pages' => $pages, 'header' => ''));
}
?>