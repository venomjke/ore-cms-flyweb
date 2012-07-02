<?php

class Uploader {

	public static function loadUploaderConfiguration($gallery) {
		$uploaderConfig = GalleryConfig::model()->find(array('condition' => "type='uploader'"))->config;
		$gallery->uploaderConfig = unserialize($uploaderConfig);
	}

	public static function maxLenghtUploader($gallery) {
		if ($gallery->uploaderConfig['max'] != '-1') {
			$filesInGallery = count($gallery->imgsOrder);
			if ($filesInGallery >= intval($gallery->uploaderConfig['max']))
				return intval('0');
			else
				return intval($gallery->uploaderConfig['max']) - $filesInGallery;
		}

		return $gallery->uploaderConfig['max'];
	}

	public static function preUpload($gallery) {
		if (Yii::app()->user->isGuest)
			return false;

		//if is a new gallery
		if (!file_exists($gallery->tmpPath))
			self::createFoldersStructure($gallery);

		//we resize original pictures and move to image/thumbs folders, then remove originals to save disk space
		if (self::uploadFiles($gallery->tmpPath, $gallery, $gallery->IsIecsv))
			$gallery->resizeAllNew($gallery->IsIecsv);
	}

	public static function createFoldersStructure($gallery) {
		$dirs = array(
			$gallery->originalPath,
			$gallery->imgsPath,
			$gallery->thPath,
			$gallery->tmpPath,
			$gallery->bigThPath,
			$gallery->mediumThPath,
		);
		foreach ($dirs as $dir) {
			MyFiles::NewFolder($dir);
		}
	}

	public static function uploadFiles($path, $gallery, $IsIecsv) {
		if (isset($_FILES["uploader"])) {
			//limit to maxim accepted if not unlimited
			$max = self::maxLenghtUploader($gallery);
			$max = $max == '-1' ? count($_FILES["uploader"]['name']) : $max;

			for ($i = 0; $i < $max; $i++) {
				if (isset($_FILES['uploader']['name'][$i]) && $_FILES['uploader']['name'][$i]) {
					if ($_FILES["uploader"]["error"][$i] == UPLOAD_ERR_OK) {
						$tmp_name = $_FILES["uploader"]["tmp_name"][$i];
						$name = MyFiles::cleanFileName($_FILES["uploader"]["name"][$i]);
						$my_path = $path.DIRECTORY_SEPARATOR.$name;
						if ($IsIecsv) {
						    copy($tmp_name, $my_path);
						    //unlink($tmp_name);
						}
						else {
						    move_uploaded_file($tmp_name, $my_path);
						}
					}
					else {
						$maxUpload = (int)(ini_get('upload_max_filesize'));
						$maxPost = (int)(ini_get('post_max_size'));
						$memoryLimit = (int)(ini_get('memory_limit'));
					
						$uploadLimit = min($maxUpload, $maxPost, $memoryLimit);
						Yii::app()->user->setFlash('error', 'Один или несколько файлов не могут быть загружены! Превышен допустимый размер файла или недостаточно прав для записи. Разрешаются файлы не более '.$uploadLimit.' Мб');
						//throw new Exception(Yii::t('module_gallery', 'Error: Couldn\'t upload files! Please check permissions.'));
					}
				}
			}
			return true;
		}
	}

}

