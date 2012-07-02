<?php
	/**
	* FBAdmin class file.
	*
	* @author Ovidiu Pop <matricks@webspider.ro>
	* @link http://www.webspider.ro/
	* @copyright Copyright &copy; 2010 Ovidiu Pop
	* Dual licensed under the MIT and GPL licenses:
	* http://www.opensource.org/licenses/mit-license.php
	* http://www.gnu.org/licenses/gpl.html
	*
	*/

class FBAdmin
{
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public static function loadDefaultConfig($gallery)
	{
		$type = $_POST['loadDefaultConfig'];
		switch($type){
			case 'Gallery':$config = serialize(Cfg::defaultGalleryConfig());break;
			case 'Fancybox':$config = serialize(Cfg::defaultFancyBoxConfig());break;
			case 'Uploader':$config = serialize(Cfg::defaultUploaderConfig());break;
		}

		$record = GalleryConfig::model()->find(array('condition'=>"type='$type'"));
		$attributes = array("config"=>$config);
		$record->saveAttributes($attributes);
		$gallery->owner->refresh();
	}

	public static function updateGalleryConfig($gallery)
	{
		$type= $_POST['scenario'];
		$model = new Cfg($type);

		$model->attributes = $_POST['Cfg'];

		if($model->validate())
		{
			$record = GalleryConfig::model()->find(array('condition'=>"type='$type'"));
			$attributes = array("config"=> serialize($_POST['Cfg']));
			$record->saveAttributes($attributes);
			Yii::app()->user->setFlash('succes',Yii::t('app', 'Configuration succesfully saved!'));
		}else{
			Yii::app()->user->setFlash('error', Yii::t('app', 'There are errors. Configuration wasn\'t saved! Old configuration is reloaded!'));
		}
	}

	public static function cpanel($gallery)
	{
		echo $gallery->render('_cpanel', array(
			'galleryConfig'=> $gallery->cfgModel('gallery'),
			'fancyBoxConfig'=> $gallery->cfgModel('fancybox'),
			'uploaderConfig'=> $gallery->cfgModel('uploader'),
		), true);

		//#dialog ok and cancel buttons
		$gallery->okButton = $gallery->galleryConfig['okButton'];
		$gallery->cancelButton = $gallery->galleryConfig['cancelButton'];

		//#dialog title
		$dialogLoadDefault = Yii::t('app', 'Load default configuration for ');
		//#dialog content
		$dialogLoadDefaultMessage = Yii::t('app','Do you load default configuration for <strong>xxxxx</strong>?<br />Actuall settings will be lost!');

		Yii::app()->clientScript->registerScript('admincp', "
			$('.flash-success').click(function(){
				$(this).fadeOut('slow');
			});

			$('.defaultCfg').click(function(){
				var thisName = $(this).attr('id').replace('def','');
				var msg = '$dialogLoadDefaultMessage'.replace('xxxxx', thisName);
				$('.msg').html(msg);
				$('#myDialog').dialog({ 
						buttons: { 
							'$gallery->okButton': function() {
									$(this).dialog('close'); 
									$.post('$gallery->rUri','loadDefaultConfig='+thisName);
							},
							'$gallery->cancelButton': function() { 
								$(this).dialog('close'); 
							}
						},
						'width':400
				});
				$('#myDialog').dialog({ title: '$dialogLoadDefault '+thisName+'?'});
				$('#myDialog').dialog('open');
				return false;
			});
		");
	}


}

?>