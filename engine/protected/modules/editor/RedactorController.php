<?php

class RedactorController extends CExtController {
    function run($actionID) {
		if(Yii::app()->user->getState("isAdmin")){
			if (!empty($_FILES['file']['name']) && !Yii::app()->user->isGuest) {
				//$dir = Yii::getPathOfAlias('webroot.upload') . '/' . Yii::app()->user->id . '/'; // директория для загрузки изображений
				$dir = Yii::getPathOfAlias('webroot.uploads') . '/';
				if (!is_dir($dir))
					@mkdir($dir, '0777', true);

				$image = CUploadedFile::getInstanceByName('file');
				if ($image) {
					$new_name = md5(time()) . '.' . $image->extensionName;
					$image->saveAs($dir . $new_name);

					Controller::disableProfiler();

					echo Yii::app()->getBaseUrl(false).'/uploads/' . $new_name;
					Yii::app()->end();
				}
			}
		}
    }
}