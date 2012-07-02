<?php
	$existentsItems = count($this->arrItems);
	if($existentsItems){
		echo '<div class="gcontainer clearfix">';
		$ind = 0;
		$listed = 0;
		foreach($this->arrItems as $item)
		{
			if($ind === 0)
				echo '<div class="fbgrow clearfix">';

			echo $item;
			$listed++;
			$ind++;

			/*if($ind === $this->thOnLine || $listed === $existentsItems)
			{
				echo '</div>';
				$ind = 0;
			}*/
		}
		echo '</div></div>';
	}
	//else
	//	echo Yii::t('common', 'This gallery is empty.');
?>

