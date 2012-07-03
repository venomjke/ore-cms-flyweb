<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />
	<meta name="description" content="<?php echo CHtml::encode($this->pageDescription); ?>" />
	<meta name="keywords" content="<?php echo CHtml::encode($this->pageKeywords); ?>" />

	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	<link media="screen" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/styles.css" rel="stylesheet" />

	<!--[if IE]> <link href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" rel="stylesheet" type="text/css"> <![endif]-->

	<link rel="icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
	
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>
	<div id="container">
		<noscript><div class="noscript"><?php echo Yii::t('common', 'Allow javascript in your browser for comfortable use site.'); ?></div></noscript> 
		<div class="logo">
			<a title="<?php echo Yii::t('common', 'Go to main page'); ?>" href="<?php echo Yii::app()->request->baseUrl; ?>/">
				<img alt="<?php echo CHtml::encode($this->pageDescription); ?>" src="<?php echo Yii::app()->request->baseUrl; ?>/images/pages/logo-open-re.jpg" id="logo" />
			</a>
		</div>
		<div id="user-cpanel">
        	<?php
				
                if(!isset($adminView)){
					?>
						<img src="<?php echo Yii::app()->request->hostInfo; ?>/images/numb5.png" />
					<?php
					/*
                    $this->widget('zii.widgets.CMenu',array(
                        'id' => 'nav',
                        'items'=>$this->aData['userCpanelItems'],
                        'htmlOptions' => array('class' => 'header'),
                    ));
					*/
                } else {
					$this->widget('zii.widgets.CMenu',array(
                        'id' => 'dropDownNav',
						'items'=>array(array('label' => Yii::t('common', 'Logout'), 'url'=>array('/site/logout'))),
                        'htmlOptions' => array('class' => 'dropDownNav adminTopNav'),
                    ));
				}
            ?>
        </div>

		<?php
		if(!isset($adminView)){
		?>
			<div id="search" class="menu_item">
				<?php
					/*
					$this->widget('application.extensions.YandexShareApi', array(
						'services' => param('shareItems', 'yazakladki,moikrug,linkedin,vkontakte,facebook,twitter,odnoklassniki')
					));
					*/
					$this->widget('zii.widgets.CMenu',array(
						'id' => 'dropDownNav',
						'items'=>$this->aData['topMenuItems'],
						'htmlOptions' => array('class' => 'dropDownNav'),
					));
				?>
			</div>
		<?php
		} else {
			echo '<hr />';
			?>
			
			<div class="admin-top-menu">
			    <?php
				$this->widget('zii.widgets.CMenu', array(
					'items'=>$this->aData['adminMenuItems'],
					'encodeLabel' => false,
					'submenuHtmlOptions' => array('class' => 'admin-submenu'),
					'htmlOptions' => array('class' => 'adminMainNav')
				));
			    ?>
			</div>
		<?php	   
		}
		?>

		<div class="content">
			<?php echo $content; ?>
			<div class="clear"></div>
		</div>
         		
		<div class="footer">
			<p class="slogan">&copy;&nbsp;<?php echo param('siteName_'.Yii::app()->language).', '.date('Y'); ?></p>
		</div>

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
		
	    if(Yii::app()->user->getState('isAdmin')){	
		
			Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/tooltip/jquery.tipTip.minified.js', CClientScript::POS_END);
			Yii::app()->clientScript->registerScript('adminMenuToolTip', '
				$(function(){
					$(".adminMainNavItem").tipTip({maxWidth: "auto", edgeOffset: 10, delay: 200});
				});
			', CClientScript::POS_READY);
		?>
			<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/tooltip/tipTip.css" />

			<div class="admin-menu-small" onclick="location.href='<?php echo Yii::app()->request->baseUrl; ?>/apartments/backend/main/admin'" style="cursor: pointer;">
				<img src="<?php echo Yii::app()->request->baseUrl; ?>/images/adminmenu/administrator.png" alt="<?php echo Yii::t('common','Administration'); ?>" title="<?php echo Yii::t('common','Administration'); ?>" class="adminMainNavItem" />    
			</div>
		<?php } ?>
</body>
</html>