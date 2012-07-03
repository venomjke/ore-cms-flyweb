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
    <a href="<?php echo Yii::app()->request->hostInfo; ?>"><img class="logo" src="/images/logo.png" /></a>
    <!--<span style="color:#dc916b">+7 (123)</span> --><span style="font-size:26px"><?php echo param("adminPhone"); ?></span> <br />
    Недвижимость в Санкт Петербурге <div class="clear"></div>
  </div>
<div class="menu">
  <div class="socialki"><img src="/images/socialki.png" /> </div>  
  <ul id="nav">
    <li><a href="/contactform/main/index">Связаться с нами</a></li>
  </ul>

  <div class="clear"></div>
        <?php
          $this->widget('zii.widgets.CMenu',array(
            'id' => 'dropDownNav',
            'items'=>$this->aData['topMenuItems'],
            'htmlOptions' => array('class' => 'dropDownNav'),
          ));
      ?>
</div>
<?php echo $content; ?>     
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
