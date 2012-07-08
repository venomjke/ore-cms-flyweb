<?php
	if($sorterLinks){
		foreach($sorterLinks as $link){
			echo '<div class="sorting">'.$link.'</div>';
		}
	}
?>

<h2>
	<?php
		echo Yii::t('module_apartments', 'Apartments list').(isset($count) && $count ? ' ('.$count.')' : '');
	?>
</h2>

<div class="appartment_box" id="appartment_box">
<?php
if($apartments){
	
	foreach ($apartments as $item){
		$this->renderPartial('widgetApartments_list_item', array(
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
	// print_r($pages);
	$this->widget('itemPaginator', array('pages' => $pages, 'header' => '', 'htmlOption'=>array('onClick'=>'reloadApartmentList(this.href); return false;')));
}
?>

<script type="text/javascript"> jQuery('.ratingview > span > input').rating({'readOnly':true}); </script>
