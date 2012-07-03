<?php $this->beginContent('//layouts/siteLayout'); ?>
<div class="slider_box">
<img class="zaglushka" src="/images/zaglushka.png" />
  <div class="slider"> <?php $this->renderPartial("//site/slider"); ?> </div>
    <div class="anons"> <?php $this->renderPartial("//site//realestate-news"); ?> </div>
  <div class="clear"></div>
</div>
<div class="content">
	<?php echo $content; ?>
  <div class="filtr">
	 		 <?php $this->renderPartial("//site/index-search-form"); ?>
	        <div class="clear"></div>
    </div>       
    <?php
        Yii::import('application.modules.apartments.components.*');
        $this->widget("ApartmentsWidget");
    ?>
  <div class="podval">&copy; 2009 Названиефирмы.ru  - Недвижимость в Санкт Петербурге<br />
    т.:  <strong>+7 (123)</strong> <strong style="font-size:18px">123 45 67</strong><br />
    г. Санкт Петербург, ул Такая-то 43-18<br />
  info@serer.ru  </div>
</div> 
  
<?php $this->endContent(); ?>