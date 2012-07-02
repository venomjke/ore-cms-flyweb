<div class="slider-wrapper theme-default">
  <div id="slider" style="position: relative; width: 568px; height: 288px; ">
  <?php if (issetModule('slider')): {
      $Slider = Slider::model();
      $images = $Slider->getAllImages();
      foreach($images as $image):
      ?>
      <img src="<?php echo Yii::app()->request->baseUrl; ?>/uploads/slider/<?php echo $image->path; ?>" alt="<?php echo $image->title; ?>" <?php echo (!empty($image->title) or !empty($image->descr))?'title="#title-'.$image->id.'"':""; ?> width="568" height="310" />
      <?php
      endforeach;
  } 
  endif;?>        
  </div>
  <?php if(issetModule('slider')): ?>
  <?php   foreach($images as $image): ?>
  <?php if(!empty($image->title) or !empty($image->descr)): ?>
    <div style="display:none;" id="title-<?php echo $image->id; ?>"> <?php echo $image->title." ".$image->descr; ?> </div>
  <?php endif;?>
  <?php   endforeach; ?>
  <?php endif; ?>
</div>
<?php
  Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/js/slider/themes/default/default.css');
  Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/js/slider/nivo-slider.css');

  Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/slider/jquery.nivo.slider.pack.js', CClientScript::POS_END);
  Yii::app()->clientScript->registerScript('slider', '$("#slider").nivoSlider({effect: "random"});', CClientScript::POS_READY);
?>