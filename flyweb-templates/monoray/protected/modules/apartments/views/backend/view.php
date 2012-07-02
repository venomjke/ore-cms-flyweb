<?php

$this->breadcrumbs=array(
	$model->getStrByLang('title'),
);

$this->menu = array(
	array('label'=>tt('Manage apartments'), 'url'=>array('admin')),
	array('label'=>tt('Add apartment'), 'url'=>array('create')),
	array('label'=>tt('Update apartment'), 'url'=>array('update', 'id' => $model->id)),
	array('label'=>tt('Delete apartment'), 'url'=>'#',
		'linkOptions'=>array(
			'submit'=>array('delete','id'=>$model->id),
			'confirm'=>tt('Are you sure you want to delete this apartment?')
		)
	),
);

$this->breadcrumbs=array(
	$model->getStrByLang('title'),
);
$this->pageTitle .= ' - '.$model->getStrByLang('title');
?>

<div id="apartment-title" class="<?php echo issetModule('viewpdf') ? 'div-pdf-fix' : ''; ?>">
	<?php 
	
	if(issetModule('viewpdf')) {
		echo '<div class="floatleft pdficon">
			<a href="'.Yii::app()->baseUrl.'/viewpdf/main/view?id='.$model->id.'"
				target="_blank"><img src="'.Yii::app()->baseUrl.'/images/design/file_pdf.png"
				alt="'.Yii::t('common', 'Pdf version').'" title="'.Yii::t('common', 'Pdf version').'"  />
			</a></div>';
	}


	echo '<div class="floatleft"><h1 class="h1-ap-title">'.$model->getStrByLang('title').'</h1></div>';
	if($model->rating){ // если у объявления есть рейтинг - показываем
		?>
		<div class="ratingview">
			<?php
				$this->widget('CStarRating',
					array(
						'name'=>'ratingview'.$model->id,
						'id'=>'ratingview'.$model->id,
						'value'=>intval($model->rating),
						'readOnly'=>true,
					));
			?>
		</div>
		<?php
	}
	?>
</div>

<div class="clear"></div>
<?php
// показвываем непосредственно объявление
$this->renderPartial('../_view', array(
	'data'=>$model,
	'usertype' => 'visitor',
));

