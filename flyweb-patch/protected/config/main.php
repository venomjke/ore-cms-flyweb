<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

require_once( dirname(__FILE__) . '/../helpers/common.php');
require_once( dirname(__FILE__) . '/../helpers/strings.php');

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'re.monoray.ru',

	'sourceLanguage' => 'en',
	'language' => 'ru',

	'preload'=>array(
		'log',
		'configuration', // preload configuration
	),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',

		'application.modules.configuration.components.*',
		'application.modules.notifier.components.Notifier',
		'application.modules.booking.models.Booking',

		'application.modules.comments.models.Comment', // TODO
		'application.modules.windowto.models.WindowTo',
		'application.modules.apartments.models.Galleries',
		'application.modules.news.models.*',
		'application.modules.apartments.models.Apartment',
		'application.modules.metrostations.models.MetroStation',
		'application.modules.slider.models.Slider',
		'application.extensions.image.Image',
		'application.modules.selecttoslider.models.SelectToSlider',
		'application.modules.sitemap.models.Sitemap',
		'application.modules.similarads.models.SimilarAds',
		'application.modules.menumanager.models.Menu',
		'application.modules.windowto.models.WindowTo',
		'application.modules.apartments.components.*',
	),

	'modules'=>array(
		'news',
		//'infopages',
		'referencecategories',
		'referencevalues',
		'apartments',
		'apartmentObjType',
        'apartmentCity',
		'comments',
		'payment',
		'booking',
		'windowto',
		'contactform',
		'articles',
		'usercpanel',
		'users',
		'quicksearch',
		'configuration',
		'timesin',
		'timesout',
		'adminpass',
		'specialoffers',
		'install',
		'viewpdf',
		'selecttoslider',
		'similarads',
		'menumanager',
		'userads',
		'metrostations',
		'slider',

		// uncomment the following to enable the Gii tool
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'admin1',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),
		
	),

	'controllerMap' => array( 'photo_upload' => 'application.modules.editor.RedactorController', ),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
		),

		'configuration' => array(
			'class' => 'Configuration',
			'cachingTime' => 3 * 5184000, // caching configuration for 180 days
		),

		'cache'=>array(
			'class'=>'system.caching.CFileCache',
            /*'class'=>'system.caching.CMemCache',
			//'useMemcached' => true,
            'servers'=>array(
                array('host'=>'127.0.0.1', 'port'=>11211),
            ),*/
		),

		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName' => false,
			'class'=>'application.components.MyUrlManager',
			'rules'=>array(
				'sitemap.xml'=>'sitemap/main/viewxml',
				
				'admin' => 'site/login',
				
				'<controller:(quicksearch|specialoffers)>/main/index' => '<controller>/main/index',

				'/' => 'site/index',
				'<_m>/<_c>/<_a>' => '<_m>/<_c>/<_a>',
				'<_c>/<_a>' => '<_c>/<_a>',


				'<module:\w+>/backend/<controller:\w+>/<action:\w+>'=>'<module>/backend/<controller>/<action>', // CGridView ajax
		
			),
		),

        'db'=>require(dirname(__FILE__) . '/db.php'),

		'errorHandler'=>array(
            'errorAction'=>'site/error',
        ),
	),

	'params'=>array(


		'languages' => array('ru'),

		// default site lang
		'defaultLang' => 'ru',
		
		// lang for notifications for admin
		'adminDefaultLang' => 'ru',

		'dateFormat' => 'd.m.Y H:i:s',
		'module_users_bookingsPerPage' => 5,


		'adminPaginationPageSize' => 20,

	),
);