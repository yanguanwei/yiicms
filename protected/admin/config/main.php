<?php
return array(
	'name' => '宁波软件园 | 宁波智慧园',
	'import' => array(
		'admin.models.*',
		'admin.components.*',
		'admin.controllers.AdminController',
    'admin.controllers.ChannelModelBaseController'
	),
	'components' => array(
		'user'=>array(
			// enable cookie-based authentication
			'class' => 'AdminWebUser',
			'allowAutoLogin'=>true,
			'loginUrl'=>array('site/login')
		),
	)
);
