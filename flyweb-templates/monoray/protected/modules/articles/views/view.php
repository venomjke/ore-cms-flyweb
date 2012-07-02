<?php
$this->pageTitle .= ' - '.tt("FAQ").' - '.$model['page_title'];
$this->breadcrumbs=array(
	tt("FAQ")=>array('index'),
	$model['page_title'],
);
?>

<h1><?php echo tt("FAQ"); ?></h1>
    
<?php
	if ($articles) {
		echo '<ul class="apartment-description-ul">';
		foreach ($articles as $article) {
			echo '<li>'.CHtml::link($article['page_title'], $article->getUrl(), array('class'=>'title')).'</li>';
		}
		echo '</ul>';
	}
	if (!empty($model)) {
		?>
		<h2><?php echo $model['page_title'];?></h2>
		<p><?php echo $model['page_body'];?></p>
		<?php
	}
?>