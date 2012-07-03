<?php
if($apartments){
	
	foreach ($apartments as $item){
		$img = $item->getMainThumb();
		$tagImg = '';
        if($img){
            $tagImg =  '<img style="width:50px; height:50px;" src="'.Yii::app()->baseUrl.'/uploads/apartments/'.$item->id.'/mediumthumbs/'.$img.'" alt="'.$item->getStrByLang('title').'" title="'.$item->getStrByLang('title').'" />';
        }
        else {
            $tagImg = '<img style="width:50px; height:50px;" src="'.Yii::app()->baseUrl.'/images/default/no_photo_mediumthumb.png" alt="'.$item->getStrByLang('title').'" title="'.$item->getStrByLang('title').'" />';
        }
		?>
        <a class="anons" href="<?php echo $item->getUrl(); ?>"><?php echo $tagImg; ?>        
  		<p><span class="offer"><?php truncateText($item->title,4); ?></span>
        <span class="prise"><?php $item->getPrettyPrice(); ?></span>

        <?php if($item->square): ?>
	        Общая площадь <?php echo $item->square; ?> м2<br />
    	<?php endif; ?>
        <?php
	        if(!function_exists("fetchRentTime")){
                function fetchRentTime($item){
                    if($item->price_type == Apartment::PRICE_PER_HOUR or $item->price_type == Apartment::PRICE_PER_DAY){
                        return "Посуточно";
                    }else if($item->price_type == Apartment::PRICE_PER_WEEK or $item->price_type == Apartment::PRICE_PER_MONTH){
                        return "Длительный";
                    }
                }
            }

            if($item->type == Apartment::TYPE_SALE){
                $echo[] = '<span class="nobr">'.$item->getNameByType($item->type).'</span>';
            }else{
                $echo[] = '<span class="nobr">'.'Срок аренды: '.fetchRentTime($item).'</span>';
            }
            echo implode($echo,"<br/>");
            unset($echo);
        ?>

    	</p>
        </a>
		<?php
	}
}
?>
