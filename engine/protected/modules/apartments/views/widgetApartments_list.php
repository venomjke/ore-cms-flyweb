<?php
Yii::app()->clientScript->registerScript('ajaxSetStatus', "
		var updateText = '".Yii::t('common', 'Loading ...')."';
		var resultBlock = 'appartment_box';
		var indicator = '".Yii::app()->request->baseUrl."/images/pages/indicator.gif';
		var bg_img = '".Yii::app()->request->baseUrl."/images/pages/opacity.png';
		
		function reloadApartmentList(url){
			$.ajax({
			    type: 'POST',
				url: url,
				data: {is_ajax: 1},
				ajaxStart: UpdatingProcess(resultBlock, updateText),
				success: function(msg){
					$('div.main-content-wrapper').html(msg);
					
					$('#update_div').remove();
					$('#update_text').remove();
					$('#update_img').remove();
				}
			});
		}
		
		function UpdatingProcess(resultBlock, updateText){
		
			$('#update_div').remove();
			$('#update_text').remove();
			$('#update_img').remove();

			var opacityBlock = $('#'+resultBlock);

			if (opacityBlock.width() != null){
				var width = opacityBlock.width();
				var height = opacityBlock.height();
				var left_pos = opacityBlock.offset().left;
				var top_pos = opacityBlock.offset().top;
				$('body').append('<div id=\"update_div\"></div>');

				var cssValues = {
					'z-index' : '5',
					'position' : 'absolute',
					'left' : left_pos,
					'top' : top_pos,
					'width' : width,
					'height' : height,
					'border' : '0px solid #FFFFFF',
					'background-image' : 'url('+bg_img+')'
				}

				$('#update_div').css(cssValues);

				var left_img = left_pos + width/2 - 16;
				var left_text = left_pos + width/2 + 24;
				var top_img = top_pos + height/2 -16;
				var top_text = top_img + 8;

				$('body').append(\"<img id='update_img' src='\"+indicator+\"' style='position:absolute;z-index:6; left: \"+left_img+\"px;top: \"+top_img+\"px;'>\");
				$('body').append(\"<div id='update_text' style='position:absolute;z-index:6; left: \"+left_text+\"px;top: \"+top_text+\"px;'>\"+updateText+\"</div>\");
			}
		}
	",
    CClientScript::POS_HEAD);
?>

<?php
	if($sorterLinks){
		foreach($sorterLinks as $link){
			echo '<div class="sorting">'.$link.'</div>';
		}
	}
?>

<h2>
	<?php
		if($this->widgetTitle !== null){
			echo $this->widgetTitle;
		}
		else{
			echo Yii::t('module_apartments', 'Apartments list').(isset($count) && $count ? ' ('.$count.')' : '');
		}
	?>
</h2>

<div class="appartment_box" id="appartment_box">
<?php
if($apartments){
	foreach ($apartments as $item){
		$this->render('widgetApartments_list_item', array(
			'item' => $item,
		));
	}
}
?>
</div>


<?php
if(!$apartments){
	echo Yii::t('module_apartments','Apartments list is empty.');
}

if($pages){
	$this->widget('itemPaginator', array('pages' => $pages, 'header' => '', 'htmlOption'=>array('onClick'=>'reloadApartmentList(this.href); return false;')));
}
?>