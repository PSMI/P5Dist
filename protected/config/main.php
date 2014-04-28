<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');
Yii::setPathOfAlias('bootstrap', dirname(__FILE__).'/../extensions/bootstrap');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.

return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'P5 Distributor Portal',
        'defaultController'=>'site/login',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),
        'behaviors'=>array(
            //'class'=>'application.components.ApplicationBehavior',
            'onBeginRequest' => array(
                'class' => 'application.components.RequireLogin'
            )
        ),
        'theme'=>'bootstrap',
	'modules'=>array(
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			//'class'=>'system.gii.GiiModule',
			//'password'=>'Enter Your Password Here',
		 	// If removed, Gii defaults to localhost only. Edit carefully to taste.
			//'ipFilters'=>array('127.0.0.1','::1'),
                        'generatorPaths'=>array(
                            'bootstrap.gii',
                        ),
		),
		
	),

	// application components
	'components'=>array(
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
                        'class'=>'UserRights',
		),
                'bootstrap'=>array(
                    'class'=>'bootstrap.components.Bootstrap',
                ),
                'mailer' => array(
                    'class' => 'application.extensions.mailer.EMailer',
                    'pathViews' => 'application.views.email',
                    'pathLayouts' => 'application.views.email.layouts'
                 ),
		 'ePdf' => array(
                    'class'         => 'ext.html2pdf.EYiiPdf',
                    'params' => array(
                        'HTML2PDF' => array(
                            'librarySourcePath' => 'application.extensions.html2pdf.*',
                            'classFile' => 'html2pdf.class.php'
                        )
                    )
                ),
                'file'=>array(
                    'class'=>'application.extensions.file.CFile',
                 ),
		// uncomment the following to enable URLs in path-format
		
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		
//		'db'=>array(
//			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
//		),
		// uncomment the following to use a MySQL database
		
		'db'=>array(
                        //'connectionString' => 'mysql:host=localhost;dbname=hwebsolu_netmarketing', 
                        'connectionString' => 'mysql:host=localhost;dbname=netmarketing',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => 'admin',
			'charset' => 'utf8',
                    /*
			'connectionString' => 'mysql:host=localhost;dbname=hwebsolu_netmarketing',
			'emulatePrepare' => true,
			'username' => 'hwebsolu_p5auser',
			'password' => 'AGpzSNAy$x[o',
			'charset' => 'utf8',
                     * 
                     */
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
            'errorAction'=>'site/404',
        ),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'admin@p5partners.com',
                'companyName'=>'PSMI',
                'distributor_url'=>'distributors.p5partners.com',
	),
);