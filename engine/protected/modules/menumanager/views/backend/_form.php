<div class="form">

	<?php




	Yii::app()->clientScript->registerScript('selectFieldsReady', '
		selectFields($("#Menu_type").val());
	', CClientScript::POS_READY);


	if($model->special){
		$js = '
			hideAll();
			$("#menu_type").hide();
			$("#menu_title").show();
			
		';
		if($model->id == 1){
			$js .= '
				$("#menu_page_title").show();
				$("#menu_page_body").show();
				$("#menu_widget").show();
			';
		} else {
			$js .= '
				$("#menu_subitems").show();
			';
		}
		Yii::app()->clientScript->registerScript('selectSpecial', $js, CClientScript::POS_READY);
	}



	Yii::app()->clientScript->registerScript('selectFields', '
		function hideAll(){
			$("#menu_title").hide();
			$("#menu_href").hide();
			$("#menu_subitems").hide();
			$("#menu_page_title").hide();
			$("#menu_page_body").hide();
			$("#menu_widget").hide();
		}

		function selectFields(type){
			hideAll();
			if(type == '.Menu::LINK_NEW_MANUAL.'){
				$("#menu_title").show();
				$("#menu_href").show();
			}

			if(type == '.Menu::LINK_NEW_AUTO.'){
				$("#menu_title").show();
				$("#menu_page_title").show();
				$("#menu_page_body").show();
				$("#menu_widget").show();
			}
			
			if(type == '.Menu::LINK_DROPDOWN.'){
				$("#menu_title").show();
			}

			if(type == '.Menu::LINK_DROPDOWN_MANUAL.'){
				$("#menu_title").show();
				$("#menu_href").show();
				$("#menu_subitems").show();
			}

			if(type == '.Menu::LINK_DROPDOWN_AUTO.'){
				$("#menu_title").show();
				$("#menu_subitems").show();

				$("#menu_page_title").show();
				$("#menu_page_body").show();
				$("#menu_widget").show();
			}
		}
	', CClientScript::POS_END);

	$form=$this->beginWidget('CActiveForm', array(
		'id'=>$this->modelName.'-form',
		//'enableAjaxValidation'=>true,
	)); ?>

		<p class="note"><?php echo Yii::t('common', 'Fields with <span class="required">*</span> are required.'); ?></p>

		<?php echo $form->errorSummary($model); ?>

		<div class="row" id="menu_type">
			<?php echo $form->labelEx($model,'type'); ?>
			<?php echo $form->dropDownList($model,'type', $model->getTypes(), array(
				'class'=>'width450',
				'onChange' => 'js: selectFields(this.value);',
			)); ?>
			<?php echo $form->error($model,'type'); ?>
		</div>

		<div class="row" id="menu_subitems">
			<?php echo $form->labelEx($model,'subitems'); ?>
			<?php echo $form->dropDownList($model,'subitems', $model->getForSubitems(), array('class'=>'width450')); ?>
			<?php echo $form->error($model,'subitems'); ?>
		</div>

		<div class="row" id="menu_title">
			<?php echo $form->labelEx($model,'title'); ?>
			<?php echo $form->textField($model,'title',array('size'=>60,'maxlength'=>255)); ?>
			<?php echo $form->error($model,'title'); ?>
		</div>

		<div class="row" id="menu_href">
			<?php echo $form->labelEx($model,'href'); ?>
			<?php echo $form->textField($model,'href',array('size'=>60,'maxlength'=>255)); ?>
			<?php echo $form->error($model,'href'); ?>
		</div>

		<div class="row" id="menu_page_title">
			<?php echo $form->labelEx($model,'page_title'); ?>
			<?php echo $form->textField($model,'page_title',array('style'=>'width:80%;','maxlength'=>255)); ?>
			<?php echo $form->error($model,'page_title'); ?>
		</div>

		<div class="row" id="menu_page_body">
			<?php echo $form->labelEx($model,'page_body'); ?>
			<?php
				$this->widget('application.modules.editor.EImperaviRedactorWidget',array(
					'model'=>$model,
					'attribute'=>'page_body',

					'htmlOptions' => array('class' => 'editor_textarea', 'style' => 'width:950px;'),

					'options'=>array(
						'toolbar'=>'custom', /*original, classic, mini, */
						'lang' => Yii::app()->language,
						'focus' => false,
					),
				));
			?>
			<?php echo $form->error($model,'page_body'); ?>
		</div>

		<div class="row" id="menu_widget">
			<?php echo $form->labelEx($model,'widget'); ?>
			<?php echo $form->dropDownList($model,'widget', Menu::getWidgetOptions()); ?>
			<?php echo $form->error($model,'widget'); ?>
		</div>


		<div class="row buttons">
			<?php echo CHtml::submitButton($model->isNewRecord ? Yii::t('common', 'Create') : Yii::t('common', 'Save')); ?>
		</div>

	<?php $this->endWidget(); ?>

</div><!-- form -->