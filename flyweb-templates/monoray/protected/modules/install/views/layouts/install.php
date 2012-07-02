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

	<link rel="icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
	<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

    <style>
        body {
            background: white;
            font-family: 'Lucida Grande', Verdana, Geneva, Lucida, Helvetica, Arial, sans-serif;
            font-size: 10pt;
            font-weight: normal;
        }

        #page {
            width: 800px;
            margin: 0 auto;
        }

        #header {
        }

        #content {
        }

        #footer {
            color: gray;
            font-size: 8pt;
            border-top: 1px solid #aaa;
            margin-top: 10px;
        }

        h1 {
            color: black;
            font-size: 1.6em;
            font-weight: bold;
            margin: 0.5em 0pt;
        }

        h2 {
            color: black;
            font-size: 1.25em;
            font-weight: bold;
            margin: 0.3em 0pt;
        }

        h3 {
            color: black;
            font-size: 1.1em;
            font-weight: bold;
            margin: 0.2em 0pt;
        }

        table.result {
            background: #E6ECFF none repeat scroll 0% 0%;
            border-collapse: collapse;
            width: 100%;
        }

        table.result th {
            background: #CCD9FF none repeat scroll 0% 0%;
            text-align: left;
        }

        table.result th, table.result td {
            border: 1px solid #BFCFFF;
            padding: 0.2em;
        }

        td.passed {
            background-color: #60BF60;
            border: 1px solid silver;
            padding: 2px;
        }

        td.warning {
            background-color: #FFFFBF;
            border: 1px solid silver;
            padding: 2px;
        }

        td.failed {
            background-color: #FF8080;
            border: 1px solid silver;
            padding: 2px;
        }

        .install_box {background-color: #DDEBFF; margin: 5px; padding: 5px; border: 1px solid #CCCCCC;}
    </style>
</head>

<body>
	<div id="container">
		<div class="logo">
			<a title="<?php echo Yii::t('common', 'Go to main page'); ?>" href="<?php echo Yii::app()->request->baseUrl; ?>/">
				<img width="302" height="62" alt="<?php echo CHtml::encode($this->pageDescription); ?>" src="<?php echo Yii::app()->request->baseUrl; ?>/images/pages/logo-open-re.jpg" id="logo" />
			</a>
		</div>
		<div class="content">
			<?php echo $content; ?>
			<div class="clear"></div>
		</div>

		<div class="footer">
			<p class="slogan">&copy;&nbsp;<?php echo param('siteName_'.Yii::app()->language).', '.date('Y'); ?></p>
		</div>
    </div>
</body>
</html>
