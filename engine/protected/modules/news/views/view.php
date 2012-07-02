<?php
$this->pageTitle .= ' - '.NewsModule::t('News').' - '.$model->title;

?>

<h2><?php echo $model->title;?></h2>
<font class="date"><?php echo NewsModule::t('Created on').' '.$model->dateCreated; ?></font>
<p>
	<?php
		echo $model->body;
	?>
</p>