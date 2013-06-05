<?php
error_reporting(E_ALL ^ E_NOTICE);
defined('YII_DEBUG') or define('YII_DEBUG', false);
//protected目录路径
$apps = dirname(dirname(__FILE__));

require dirname($apps) . '/framework/yii.php';

//web根路径
$wwwroot = dirname($apps);
//前台项目路径
$frontend = $apps . '/frontend';
//台后项目路径
$admin = $apps . '/admin';
//前台相对URL
define('FRONTEND_URL', '');
//主题相对URL
define('THEMES_URL', '/themes');

Yii::setPathOfAlias('wwwroot', $wwwroot);
Yii::setPathOfAlias('apps', $apps);
Yii::setPathOfAlias('frontend', $frontend);
Yii::setPathOfAlias('admin', $admin);

return array(
	'language'=>'zh_cn',
	'timeZone'=>'Asia/Shanghai',
	'preload'=>array('log'),
	'import'=>array(
		'apps.models.*',
		'apps.models.News',
		'apps.controllers.YController'
	),
	'modules' => array(
		'gii' => array(
			'class'=>'system.gii.GiiModule',
			'password'=>'nbzhongsou', 
			// 'ipFilters'=>array(...a list of IPs...),
			// 'newFileMode'=>0666, 
			// 'newDirMode'=>0777,
		)
	),
	'components'=>array(
		'widgetFactory' => array(
			'enableSkin' => true
		),
		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=nbsp_2012',//nbsp_db
			'emulatePrepare' => true,
			'username' => 'root',//'nbsp',
			'password' => 'root',//'nbsp2012',
			'charset' => 'utf8',
			'tablePrefix' => 'y_',
		),
		'authManager'=>array(
			'class'=>'CDbAuthManager',
			'defaultRoles'=>array('guest'),//默认角色
			'connectionID'=>'db',
			'itemTable'=>'items',
			'assignmentTable'=>'assignments',
			'itemChildTable'=>'itemchildren'
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
		'themeManager'=>array(
			'class'=>'CThemeManager',
			'basePath'=>Yii::getPathOfAlias('wwwroot.themes'),
			'baseUrl' => THEMES_URL
		),
	),
);