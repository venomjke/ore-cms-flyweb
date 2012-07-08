<div class="apartment-description">
	<?php
		if($data->is_special_offer){
			?>
			<div class="big-special-offer">
				<?php
				echo '<h4>'.Yii::t('common', 'Special offer!').'</h4>';

				if($data->is_free_from != '0000-00-00' && $data->is_free_to != '0000-00-00'){
					echo '<p>';
					echo Yii::t('common','Is avaliable');
					if($data->is_free_from != '0000-00-00'){
						echo ' '.Yii::t('common', 'from');
						echo ' '.Booking::getDate($data->is_free_from);

					}
					if($data->is_free_to != '0000-00-00'){
						echo ' '.Yii::t('common', 'to');
						echo ' '.Booking::getDate($data->is_free_to);
					}
					echo '</p>';
				}
				?>
			</div>
			<?php
		}
	?>
	
		<div class="viewapartment-main-photo">
		<?php
			$img = $data->getMainThumb();
			if($img){
				echo '<img src="'.Yii::app()->baseUrl.'/uploads/apartments/'.$data->id.'/bigthumb/'.$img.'"
							alt="'.$data->getStrByLang('title').'"
							title="'.$data->getStrByLang('title').'" />';
			}
			else {
				echo '<img src="'.Yii::app()->baseUrl.'/images/default/no_photo_bigthumb.png"
							alt="'.$data->getStrByLang('title').'"
							title="'.$data->getStrByLang('title').'" />';
			} 
		?>
	</div>

	<div class="viewapartment-description-top">
			<div>
				<strong>
				<?php 
					echo $data->objType->name . ' ' . tt('type_view_'.$data->type);
					if($data->stationsTitle() && $data->num_of_rooms){
						echo ',&nbsp;';
						echo Yii::t('module_apartments',
							'{n} bedroom|{n} bedrooms|{n} bedrooms near {metro} metro station', array($data->num_of_rooms, '{metro}' => $data->stationsTitle()));
					}
					elseif ($data->num_of_rooms){
						echo ',&nbsp;';
						echo Yii::t('module_apartments',
							'{n} bedroom|{n} bedrooms|{n} bedrooms', array($data->num_of_rooms));
					}
					if(isset($data->city) && isset($data->city->name)){
						echo ',&nbsp;';
						echo 'г.'.$data->city->name;
					}
				?>
				</strong>
			</div>
            <br/>
			
			<?php echo tt('Apartment ID').': '.$data->id; ?>

			
		
		<p class="cost padding-bottom10">
			<?php echo tt('Price from').': '.$data->getPrettyPrice(); ?>
		</p>
		
		<?php
			if($data->floor || $data->floor_total || $data->square || $data->berths || ($data->windowTo && $data->windowTo->getTitle()) ){
				echo '<p>';
				$echo = array();
				if($data->floor && $data->floor_total){
					$echo[] = Yii::t('module_apartments', '{n} floor of {total} total', array($data->floor, '{total}' => $data->floor_total));
				} else {
					if($data->floor){
						$echo[] = $data->floor.' этаж';
					}
					if($data->floor_total){
						$echo[] = 'Этажей: '.$data->floor_total;
					}
				}
				if($data->square){
					$echo[] = Yii::t('module_apartments', 'total square: {n} m<sup>2</sup>', $data->square);
				}
				if($data->berths){
					$echo[] = Yii::t('module_apartments', 'berths').': '.CHtml::encode($data->berths);
				}
				if($data->windowTo && $data->windowTo->getTitle()){
					$echo[] = tt('window to').' '.CHtml::encode($data->windowTo->getTitle());
				}
				echo implode(', ', $echo);
				unset($echo);

				echo '</p>';
			}
		?>

		<?php
			if(!Yii::app()->user->getState('isAdmin') && $data->type == 1){
				echo CHtml::link(tt('Booking'), array('/booking/main/bookingform', 'id' => $data->id), array('class' => 'btnsrch booking-button fancy')); 
				// booking-button
			}
		?>
		<div class="clear">&nbsp;</div>
	</div>
	
	<div class="apartment-description-item">
		<?php
			if ($data->images) {
				$this->widget('application.modules.gallery.FBGallery', array(
					'images' => $data->images,
					'pid' => $data->id,
					'userType' => $usertype,
				));
			}
		?>
	</div>
	<div class="viewapartment-description">
		<?php

			if($data->getStrByLang('description')){
				echo '<p><strong>'.tt('Description').':</strong> '.CHtml::encode($data->getStrByLang('description')).'</p>';
			}

			if($data->getStrByLang('description_near')){
				echo '<p><strong>'.tt('Near').':</strong> '.CHtml::encode($data->getStrByLang('description_near')).'</p>';
			}

			if($data->stationsTitle() || $data->getStrByLang('address')){
				if($data->stationsTitle()){
					echo '<p><strong>'.tt('Metro').':</strong> '.CHtml::encode($data->stationsTitle()).'</p>';
				}
                $adressFull = '';
                if(isset($data->city) && isset($data->city->name)){
                    $cityName = $data->city->name;
                    if($cityName) {
                        $adressFull = 'г. '.$cityName;
                    }
                }
                $adress = CHtml::encode($data->getStrByLang('address'));
                if($adress){
                    $adressFull .= ', '.$adress;
                }
                if($adressFull){
					echo '<p><strong>'.tt('Address').':</strong> '.$adressFull.'</p>';
				}
			}
		?>

		<?php
			$prev = '';
			$column1 = 0;
			$column2 = 0;
			$column3 = 0;

			foreach($data->getFullInformation($data->id, $data->type) as $item){
				if($item['title']){
					if($prev != $item['style']){
						$column2 = 0;
						$column3 = 0;
						echo '<div class="clear"></div>';
					}
					$$item['style']++;
					$prev = $item['style'];
					echo '<div class="'.$item['style'].'">';
					echo '<span class="viewapartment-subheader">'.CHtml::encode($item['title']).'</span>';
					echo '<ul class="apartment-description-ul">';
					foreach($item['values'] as $key => $value){
						if($value){							
							echo '<li><span>'.CHtml::encode($value).'</span></li>';
						}
					}
					echo '</ul>';
					echo '</div>';
					if(($item['style'] == 'column2' && $column2 == 2)||$item['style'] == 'column3' && $column3 == 3){
						echo '<div class="clear"></div>';
					}

				}
			}
		?>
		<div class="clear"></div>
	</div>

	<?php 
		if (issetModule('similarads') && param('useSliderSimilarAds') == 1) { 
			Yii::import('application.modules.similarads.components.SimilarAdsWidget');
			$ads = new SimilarAdsWidget;
			$ads->viewSimilarAds($data);
		} 
	?>
	
	<?php
	if(($data->lat && $data->lng) || Yii::app()->user->getState('isAdmin')){
		if(param('useGoogleMap', 1)){
			?>
			<div class="row">
				<div class="row" id="gmap">
					<?php echo $this->actionGmap($data->id, $data); ?>
				</div>
			</div>
			<?php
		}
		if(param('useYandexMap', 1)){
			?>
			<div class="row">
				<div class="row" id="ymap">
					<?php echo $this->actionYmap($data->id, $data); ?>
				</div>
			</div>
			<?php
		}
	}
	?>
</div>
<br />
