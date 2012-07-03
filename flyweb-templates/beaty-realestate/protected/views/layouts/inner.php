<?php $this->beginContent('//layouts/siteLayout'); ?>
 <div class="filtr" style="margin:0px auto;">
 <?php $this->renderPartial("//site/index-search-form"); ?>
	        <div class="clear"></div>
    </div>       
<div class="content">
  <div class="main-content-wrapper">
	<?php echo $content; ?>
  </div>
  <div class="podval">&copy; 2009 Названиефирмы.ru  - Недвижимость в Санкт Петербурге<br />
    т.:  <strong>+7 (123)</strong> <strong style="font-size:18px">123 45 67</strong><br />
    г. Санкт Петербург, ул Такая-то 43-18<br />
  info@serer.ru  
	</div>
  
</div>
<?php $this->endContent(); ?>