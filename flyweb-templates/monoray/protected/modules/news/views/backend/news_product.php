<?php
$this->pageTitle .= ' - '.NewsModule::t('News product');
$this->breadcrumbs=array(
	NewsModule::t('News product'),
);
?>

<h1><?php echo NewsModule::t('News product'); ?></h1>
<?php
foreach ($items as $item){
	echo '<div class="news-items">';
	    echo '<p><font class="date">'.$item->pubDate.'</font></p>';
	    //echo CHtml::link($item->title, $item->getUrl(), array('class'=>'title'));
	    echo '<p><font class="title">'.$item->title.'</font></p>';
	    echo '<p class="desc">';
		echo $item->description;
	    echo '</p>';
	    echo '<p>';
		echo CHtml::link(NewsModule::t('Read more &raquo;'), $item->link);
	    echo '</p>';
	echo '</div>';

    if($item->is_show == 0){
        $item->is_show = 1;
        $item->update('is_show');
    }
}

if(!$items){
	echo NewsModule::t('News list is empty.');
}

if($pages){
	$this->widget('itemPaginator',array('pages' => $pages, 'header' => ''));
}
?>