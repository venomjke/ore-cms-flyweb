<div class="gcontainer clearfix">
	<?php
		if(count($this->arrItems)):
			$this->widget('zii.widgets.jui.CJuiSortable', array(
				'items'=>$this->arrItems,
				'id'=>'container_sortabil',
				'options'=>array(
					'delay'=>'300',
// 					'handle'=>'.gImgName',
						'stop' => "js: function(){
							var ids = new Array;
							var urls = $(this).find('a.gImg');

							$(urls).each(function(){
								var a = $(this).attr('href').split('/');
								var l = a.length;
								ids.push(a[l-1]);
							});
							$.post('$this->rUri', 'newImgOrder='+ids);
						}"
				),
			));

			$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
				'id'=>'myDialog',
				'options'=>array(
					'dialogClass'=>'hidden',
					'autoOpen'=>false,
				),
			));
			echo '<div class="msg hide"></div>';
			$this->endWidget('zii.widgets.jui.CJuiDialog');
		else:
			echo Yii::t('common', 'This gallery is empty.');
		endif;
	?>
</div>

<div class="toggleUploader toggleDown"><?php echo Yii::t('common', 'Uploader');?></div>
<!-- <div id="fbguploader" class="fbguploader">uploader zone</div> -->
<?php 

// 	if($this->uploaderConfig['max'])
// 		CController::renderPartial('uploader');
// 	else
// 		echo '<div class="maxUploaded">'.Yii::t('app', 'Nivel maxim de incarcare.').'</div>';
?>
