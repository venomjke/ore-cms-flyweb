<div class="appartment_item <?php if ($item->is_special_offer) { echo 'special_offer_highlight'; } ?>">
    <div class="offer">
        <div class="offer-photo" align="left">
            <?php
                $img = $item->getMainThumb();
                if($img){
                    echo CHtml::link('<img src="'.Yii::app()->baseUrl.'/uploads/apartments/'.$item->id.'/mediumthumbs/'.$img.'"
                                alt="'.$item->getStrByLang('title').'"
                                title="'.$item->getStrByLang('title').'" />',
                        $item->getUrl());
                }
				else {
					echo CHtml::link('<img src="'.Yii::app()->baseUrl.'/images/default/no_photo_mediumthumb.png"
                                alt="'.$item->getStrByLang('title').'"
                                title="'.$item->getStrByLang('title').'" />',
                        $item->getUrl());
				}
            ?>
        </div>
        <div class="offer-text">
            <div class="apartment-title">
					<?php 
						if($item->rating && !isset($booking)){
							$title = truncateText($item->getStrByLang('title'), 5);
						}
						else {
							$title = truncateText($item->getStrByLang('title'), 10);
						}
						echo CHtml::link($title, 
						$item->getUrl(), array('class' => 'offer')); 
					?>
			</div>
            <?php
                if($item->rating && !isset($booking)){
                    echo '<div class="ratingview">';
                    $this->widget('CStarRating',array(
                        'model'=>$item,
                        'attribute' => 'rating',
                        'readOnly'=>true,
                        'id' => 'rating_' . $item->id,
						'name'=>'rating'.$item->id,
                    ));
                    echo '</div>';
                }
            ?>
            <div class="clear"></div>
            <?php
                if(isset($booking)){
                    ?>
                    <p class="cost">
                            <?php
                                if($booking->sum_rur){
                                    echo Yii::t('common', '{n} RUR|{n} RUR', $booking->sum_rur);
                                }
                            ?>
                    </p>
                    <?php
                }
                else{
                    ?>
                    <p class="cost"><?php echo $item->getPrettyPrice(); ?></p>
                    <?php
                }

                if(isset($booking)){
                    $title = 'title_'.Yii::app()->language;

                    echo '<p class="desc">';
                    echo Yii::t('common', 'From').' '.$booking->getDate($booking->date_start).' ('.$booking->time_in_value->$title.') ';
                    echo Yii::t('common', 'to').' '.$booking->getDate($booking->date_end).' ('.$booking->time_out_value->$title.') ';
                    echo '</p>';

                    ?>
                    <div class="row">
                        <?php
                            echo '<strong>'.tt('Status', 'booking').':</strong> '.$booking->returnStatusHtml();
                            if($booking->date_end < date('Y-m-d') && ($booking->status == Booking::STATUS_NEW || $booking->status == Booking::STATUS_WAITPAYMENT)){
                                echo ' ('.tt('Check-out date is expired', 'usercpanel').') ';
                            }
                        ?>
                    </div>
                    <?php
                        if(($booking->status == Booking::STATUS_NEW || $booking->status == Booking::STATUS_WAITPAYMENT) && $booking->date_end >= date('Y-m-d') ){
                            ?>
                                <div class="row">
                                    <strong><?php echo tt('Actions', 'usercpanel'); ?>:</strong>

                                    <?php echo CHtml::link(tt('Decline booking', 'booking'), '#',
                                        array('onclick' =>
                                            'declineBooking("'.$booking->id.'", "'.Yii::app()->controller->createUrl('/usercpanel/main/declinebooking',
                                                    array('id' => $booking->id)).'"); return false;')
                                    ); ?>
                                    <?php
                                        if($booking->status == Booking::STATUS_WAITPAYMENT){
                                            echo ' | '.CHtml::link(tt('Pay', 'payment'), array('/payment/main/payForm', 'id' => $booking->id));;
                                        }
                                    ?>
                                </div>
                            <?php
                        }
                }
                else{
                    if( $item->floor || $item->floor_total || $item->square || $item->berths){
                        echo '<p class="desc">';

                        $echo = array();

						if($item->floor && $item->floor_total){
							$echo[] = Yii::t('module_apartments', '{n} floor of {total} total', array($item->floor, '{total}' => $item->floor_total));
						} else {
							if($item->floor){
								$echo[] = $item->floor.' этаж';
							}
							if($item->floor_total){
								$echo[] = 'Этажей: '.$item->floor_total;
							}
						}

                        if($item->square){
                            $echo[] = '<span class="nobr">'.Yii::t('module_apartments', 'total square: {n} m<sup>2</sup>', $item->square)."</span>";
                        }
                        if($item->berths){
                            $echo[] = '<span class="nobr">'.Yii::t('module_apartments', 'berths').': '.CHtml::encode($item->berths)."</span>";
                        }
                        echo implode(', ', $echo);
                        unset($echo);

                        echo '</p>';
                    }
                }

            ?>
        </div>
    </div>
</div>