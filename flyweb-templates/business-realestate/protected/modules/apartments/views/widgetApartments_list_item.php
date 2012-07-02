<a class="anons appartment_item" href="<?php echo $item->getUrl(); ?>">
    <?php if ($item->is_special_offer) { echo '<span class="star"></span>'; } ?>
     <?php
        $img = $item->getMainThumb();
        if($img){
            echo '<img src="'.Yii::app()->baseUrl.'/uploads/apartments/'.$item->id.'/mediumthumbs/'.$img.'" alt="'.$item->getStrByLang('title').'" title="'.$item->getStrByLang('title').'" />';
        }
        else {
            echo '<img src="'.Yii::app()->baseUrl.'/images/default/no_photo_mediumthumb.png" alt="'.$item->getStrByLang('title').'" title="'.$item->getStrByLang('title').'" />';
        }
    ?>
    <p> 
        <span class="offer"> <?php echo truncateText($item->getStrByLang('title'), 5);?></span>
        <span class="prise"><?php echo $item->getPrettyPrice(); ?></span>

        <?php
            if( $item->square ){

                $echo = array();

                if(!function_exists("fetchRentTime")){
                    function fetchRentTime($item){
                        if($item->price_type == Apartment::PRICE_PER_HOUR or $item->price_type == Apartment::PRICE_PER_DAY){
                            return "Посуточно";
                        }else if($item->price_type == Apartment::PRICE_PER_WEEK or $item->price_type == Apartment::PRICE_PER_MONTH){
                            return "Длительный";
                        }
                    }
                }
                
                if($item->square){
                    $echo[] = '<span class="nobr">'.Yii::t('module_apartments', 'total square: {n} m<sup>2</sup>', $item->square)."</span>";
                }
                if($item->type == Apartment::TYPE_SALE){
                    $echo[] = '<br/><span class="nobr">'.$item->getNameByType($item->type).'</span>';
                }else{
                    $echo[] = '<br/><span class="nobr">'.'Срок аренды: '.fetchRentTime($item).'</span>';
                }

                /*
                if($item->berths){
                    $echo[] = '<span class="nobr">'.Yii::t('module_apartments', 'berths').': '.CHtml::encode($item->berths)."</span>";
                }
                */
                echo implode(', ', $echo);
                unset($echo);
            }
        ?>
    </p>
</a>