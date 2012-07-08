<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  <meta name="language" content="en" />
  <meta name="description" content="<?php echo CHtml::encode($this->pageDescription); ?>" />
  <meta name="keywords" content="<?php echo CHtml::encode($this->pageKeywords); ?>" />


  <link media="screen" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/style.css" rel="stylesheet" />

  <link rel="icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
  <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
  
  <title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body>
<div class="shapka">
  <div class="topmenu"><strong><?php echo param("adminPhone"); ?></strong> 
    <ul>
      <li><a class="fancy" href="<?php echo Yii::app()->request->baseUrl; ?>/booking/main/mainform">ОСТАВИТЬ ЗАЯВКУ</a></li>
    </ul>
  </div>
  <div class="clear"></div>
  <a href="<?php echo Yii::app()->request->hostInfo; ?>">
    <img class="logo" src="<?php echo Yii::app()->request->baseUrl; ?>/images/logo.png" />
  </a>
  <div style="overflow:auto;float:right;margin-top:25px;">
    <div style="overflow:auto;">
       <div class="share42init" style="float:right;"></div>
         <script type="text/javascript" src="share42/share42.js"></script>
    <script type="text/javascript">share42('share42/',150,20)</script>
    </div>  
   <span class="slogan">Слоган компании, типа «Вся недвижимость Петербурга»</span>
  </div>
  <div class="clear"></div>
  <div class="menu">
    <?php
          $this->widget('zii.widgets.CMenu',array(
            'id' => 'dropDownNav',
            'items'=>$this->aData['topMenuItems'],
            'htmlOptions' => array('class' => 'dropDownNav'),
          ));
      ?>
  </div>
</div>
<div class="clear"></div>
<div class="poiskpolosa">
  <div class="poiskcentr">
          <?php $this->renderPartial("//site/slider"); ?>
          <?php $this->renderPartial("//site/index-search-form"); ?>
    </div>
</div>
<div class="centr">
  <div class="leftstolb">
      <?php $this->renderPartial("//site//realestate-news"); ?>
      <?php $this->renderPartial("//site//realestate-special"); ?>   
  </div>
  <div class="content main-content-wrapper">
     <?php echo $content; ?>
  </div>  
</div>
<div class="clear" style="height:2px; background-color:#084767;"></div>
<div class="podval">
</div>
<div class="footer">
      <p class="slogan">&copy;&nbsp;<?php echo param('siteName_'.Yii::app()->language).', '.date('Y'); ?></p>
    </div>
<div id="loading" style="display:none;"><?php echo Yii::t('common', 'Loading content...'); ?></div>
  <?php
    Yii::app()->clientScript->registerCoreScript('jquery');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/jquery.dropdownPlain.js', CClientScript::POS_END);
    Yii::app()->clientScript->registerScript('loading', '
      $("#loading").bind("ajaxSend", function(){
        $(this).show();
      }).bind("ajaxComplete", function(){
        $(this).hide();
      });
    ', CClientScript::POS_READY);

    Yii::app()->clientScript->registerScript('focusSubmit', '
      function focusSubmit(elem) {
        elem.keypress(function(e) {
          if(e.which == 13) {
            $(this).blur();
            $("#btnleft").focus().click();
          }
        });
      }
    ', CClientScript::POS_END);
    
    $this->widget('application.modules.fancybox.EFancyBox', array(
      'target'=>'a.fancy',
      'config'=>array(
          'ajax' => array('data'=>"isFancy=true"),
        ),
      )
    );

    Yii::app()->clientScript->registerScript('fancybox', '
        $("a.fancy").fancybox({
          "ajax":{
            "data": {"isFancy":"true"}
          }
        });
    ', CClientScript::POS_READY);
    ?>
      </body>
</html>
