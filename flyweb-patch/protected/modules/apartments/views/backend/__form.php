<?php
if($model->is_free_from == '0000-00-00'){
	$model->is_free_from = '';
}
if($model->is_free_to == '0000-00-00'){
	$model->is_free_to = '';
}
?>

<p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

<?php echo $form->errorSummary($model); ?>

<?php if(!$model->isNewRecord){ ?>
	<div class="row">
		<strong><?php echo tt('Apartment ID', 'apartments'); ?></strong>: <?php echo $model->id; ?>
	</div>
<?php } ?>

<div class="row">
	<?php echo $form->labelEx($model,'type'); ?>
	<?php echo $form->dropDownList($model,'type',Apartment::getTypesArray(), array('class' => 'width150', 'id'=>'ap_type')); ?>
	<?php echo $form->error($model,'type'); ?>
</div>

<div class="row">
	<?php echo $form->labelEx($model,'city_id'); ?>
	<?php echo $form->dropDownList($model,'city_id',Apartment::getCityArray(), array('class' => 'width150')); ?>
	<?php echo $form->error($model,'city_id'); ?>
</div>

<div class="row">
	<?php echo $form->labelEx($model,'obj_type_id'); ?>
	<?php echo $form->dropDownList($model,'obj_type_id', Apartment::getObjTypesArray(), array('class' => 'width150')); ?>
	<?php echo $form->error($model,'obj_type_id'); ?>
</div>

<div class="row">
	<?php echo $form->checkBox($model,'is_special_offer'); ?>
	<?php echo $form->labelEx($model,'is_special_offer', array('class' => 'noblock')); ?>
	<?php echo $form->error($model,'is_special_offer'); ?>
</div>

<div class="row">
	<?php echo $form->labelEx($model,'price_from_rur'); ?>
	<?php echo $form->textField($model,'price_from_rur', array('class' => 'width50')); ?>
	<?php echo $form->dropDownList($model,'price_type', Apartment::getPriceArray($model->type), array('class' => 'width150')); ?>
	<?php echo $form->error($model,'price_from_rur'); ?>
</div>

<div class="row">
	<?php echo $form->labelEx($model,'num_of_rooms'); ?>
	<?php echo $form->dropDownList($model,'num_of_rooms',
			array_merge(
				array(0 => ''),
				range(1, param('moduleApartments_maxRooms', 8))
			)); ?>
	<?php echo $form->error($model,'num_of_rooms'); ?>
</div>

<div class="row">
	<?php echo $form->labelEx($model,'floor', array('class' => 'noblock')); ?> /
	<?php echo $form->labelEx($model,'floor_total', array('class' => 'noblock')); ?><br />
	<?php echo $form->dropDownList($model,'floor',
			array_merge(
				array('0' => ''),
				range(1, param('moduleApartments_maxFloor', 30))
			)); ?> /
	<?php echo $form->dropDownList($model,'floor_total',
			array_merge(
				array('0' => ''),
				range(1, param('moduleApartments_maxFloor', 30))
			)); ?>
	<?php echo $form->error($model,'floor'); ?>
	<?php echo $form->error($model,'floor_total'); ?>
</div>

<div class="row">
	<?php echo $form->labelEx($model,'square'); ?>
	<?php echo $form->textField($model,'square', array('size' => 10)); ?>
	<?php echo $form->error($model,'square'); ?>
</div>

<?php if (issetModule('metrostations')) { ?>
<div class="row">
	<?php echo $form->labelEx($model,'metroStations'); ?>
	<?php echo tt('(press and hold SHIFT button for multiply select)', 'apartments'); ?><br />
	<?php
		echo $form->listBox($model,'metroStations', MetroStation::getAllStations(), array('class'=>'width300', 'size' => 20, 'multiple'=>'multiple'));
	?>
	<?php echo $form->error($model,'metroStations'); ?>
</div>
<?php } ?>

<div class="row">
	<?php echo $form->labelEx($model,'berths'); ?>
	<?php echo $form->textField($model,'berths',array('class' => 'width150','maxlength'=>255)); ?>
	<?php echo $form->error($model,'berths'); ?>
</div>

<div class="apartment-description-item">
	<?php
		if($categories){
			$prev = '';
			$column1 = 0;
			$column2 = 0;
			$column3 = 0;

			$count = 0;
			foreach($categories as $catId => $category){
				if(isset($category['values']) && $category['values'] && isset($category['title'])){

					if($prev != $category['style']){
						$column2 = 0;
						$column3 = 0;
						echo '<div class="clear">&nbsp;</div>';
					}
					$$category['style']++;
					$prev = $category['style'];
					echo '<div class="'.$category['style'].'">';
					echo '<span class="viewapartment-subheader">'.$category['title'].'</span>';
					echo '<ul class="no-disk">';
					foreach($category['values'] as $valId => $value){
						if($value){
								$checked = $value['selected'] ? 'checked="checked"' : '';
								echo '<li><input type="checkbox" id="category['.$catId.']['.$valId.']" name="category['.$catId.']['.$valId.']" '.$checked.'/>
									<label for="category['.$catId.']['.$valId.']" />'.$value['title'].'</label></li>';
						}
					}
					echo '</ul>';
					echo '</div>';
					if(($category['style'] == 'column2' && $column2 == 2)||$category['style'] == 'column3' && $column3 == 3){
						echo '<div class="clear"></div>';
					}
				}

			}
		}
	?>
	<div class="clear"></div>
</div>
<div class="row">
	<div class="full-multicolumn-first">
		<?php echo $form->labelEx($model,'title_ru', array('class' => 'ru-flag-label')); ?>
		<?php echo $form->textField($model,'title_ru',array('class'=>'width300','maxlength'=>255)); ?>
		<?php echo $form->error($model,'title_ru'); ?>
	</div>
</div>
<div class="clear">&nbsp;</div>
<div class="row">
	<div class="full-multicolumn-first">
		<?php echo $form->labelEx($model,'description_ru', array('class' => 'ru-flag-label')); ?>
		<?php echo $form->textArea($model,'description_ru',array('class'=>'width300', 'rows'=>6)); ?>
		<?php echo $form->error($model,'description_ru'); ?>
	</div>
</div>
<div class="clear">&nbsp;</div>
<div class="row">
	<div class="full-multicolumn-first">
		<?php echo $form->labelEx($model,'description_near_ru', array('class' => 'ru-flag-label')); ?>
		<?php echo $form->textArea($model,'description_near_ru',array('rows'=>6, 'class'=>'width300')); ?>
		<?php echo $form->error($model,'description_near_ru'); ?>
	</div>
</div>
<div class="clear">&nbsp;</div>

<div class="row">
	<div class="full-multicolumn-first">
		<?php echo $form->labelEx($model,'address_ru',  array('class' => 'ru-flag-label')); ?>
		<?php echo $form->textArea($model,'address_ru',array('rows'=>3, 'class'=>'width300')); ?>
		<?php echo $form->error($model,'address_ru'); ?>
	</div>
</div>

<div class="clear">&nbsp;</div>
<?php if(!$model->isNewRecord){ ?>
<div class="row" id="photo-gallery">
	<?php
		$this->widget('application.modules.gallery.FBGallery', array(
				'pid' => $model->id,
				'userType' => 'admin',
			));
	?>
</div>
<?php } ?>

<div class="clear">&nbsp;</div>
<?php if(!$model->isNewRecord){
	if (param('useGoogleMap', 1)){?>
	<div class="row">
		<div class="row" id="gmap">
			<?php echo $this->actionGmap($model->id); ?>
		</div>
	</div>
<?php } elseif (param('useYandexMap', 1)) { ?>
	<div class="row">
		<div class="row" id="ymap">
			<?php echo $this->actionYmap($model->id); ?>
		</div>
	</div>
<?php }
}?>
