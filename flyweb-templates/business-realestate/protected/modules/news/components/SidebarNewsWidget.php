<?php
/**********************************************************************************************
*                            CMS Open Real Estate
*                              -----------------
*	version				:	1.2.0
*	copyright			:	(c) 2012 Monoray
*	website				:	http://www.monoray.ru/
*	contact us			:	http://www.monoray.ru/contact
*
* This file is part of CMS Open Real Estate
*
* Open Real Estate is free software. This work is licensed under a GNU GPL.
* http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
*
* Open Real Estate is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY;
* Without even the implied warranty of  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
***********************************************************************************************/

class SidebarNewsWidget extends CWidget {

	public $usePagination = 1;

	public function getViewPath($checkTheme=false){
		return Yii::getPathOfAlias('application.modules.news.views');
	}

	public function run() {
		$news = new News;
		$result = $news->getAllWithPagination();
		
		$this->render('widgetSidebarNews_list', array(
			'news' => $result['items'],
			'pages' => $result['pages'],
		));
	}
}