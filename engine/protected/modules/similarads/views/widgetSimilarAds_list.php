<?php
if (is_array($ads) && count($ads) > 0) {
	echo '<div class="similar-ads">';
		echo '<span class="viewapartment-subheader">'.tt('Similar ads', 'similarads').'</span>';
		echo '<ul id="mycarousel" class="jcarousel-skin-tango">';
			foreach ($ads as $item) {
				$image = $imgsOrder = '';
				if($item->images){
					$imgsOrder = unserialize($item->images->imgsOrder);
				}

				if (is_array($imgsOrder)) {	
					$countArr = count($imgsOrder);
					if ($countArr) {
						reset($imgsOrder);
						$image = key($imgsOrder);
					}	
				}		
				$imageDefault = Yii::app()->baseUrl.'/images/default/no_photo_mediumthumb.png';

				echo '<li>';
					echo '<a href="'.Yii::app()->createUrl('apartments/main/view', array('id' => $item->id, 'title' => $item->title_ru)).'">';
						if ($image) {
							echo '<img src="' . Yii::app()->request->baseUrl . '/uploads/apartments/' . $item->id . '/mediumthumbs/' . $image . '" alt="" title="" width="150" height="100" />';
						}
						else {
							echo '<img src="' . $imageDefault . '" alt="" title="" width="150" height="100" />';
						}
					echo '</a>';
					if($item->getStrByLang('description')){
						echo '<div class="similar-descr">'.truncateText(CHtml::encode($item->getStrByLang('description')), 6).'</div>';
					}
					echo '<div class="similar-price">'.tt('Price from', 'apartments').': '.$item->getPrettyPrice().'</div>'; 
				echo '</li>';
			}
		echo '</ul>';
	echo '</div>';
	
	if (count($ads) > 5) {
		Yii::app()->clientScript->registerScript('similar-ads-slider', 'jQuery("#mycarousel").jcarousel({ visible: 5});', CClientScript::POS_READY);
	}
	else {
		Yii::app()->clientScript->registerScript('similar-ads-slider', 'jQuery("#mycarousel").jcarousel({ visible: 5, buttonNextHTML: null, buttonPrevHTML: null});', CClientScript::POS_READY);
	}
}
?>