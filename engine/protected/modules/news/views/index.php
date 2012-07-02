<?php
$this->pageTitle .= ' - '.NewsModule::t('News');
$this->breadcrumbs=array(
	NewsModule::t('News'),
);
?>

<h1><?php echo NewsModule::t('News'); ?></h1>
<?php
foreach ($items as $item){
	echo '<div class="news-items">';
	    echo '<p><font class="date">'.$item->dateCreated.'</font></p>';
	    //echo CHtml::link($item->title, $item->getUrl(), array('class'=>'title'));
	    echo '<p><font class="title">'.$item->title.'</font></p>';
	    echo '<p class="desc">';
		echo truncateText(
			$item->body,
			param('newsModule_truncateAfterWords', 50)
		);
	    echo '</p>';
	    echo '<p>';
		echo CHtml::link(NewsModule::t('Read more &raquo;'), $item->getUrl());
	    echo '</p>';
	echo '</div>';
}

if(!$items){
	echo NewsModule::t('News list is empty.');
}

if($pages){
	$this->widget('itemPaginator',array('pages' => $pages, 'header' => ''));
}
?>