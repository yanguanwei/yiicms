<?php
$main = require dirname(__FILE__).'/../protected/config/main.php';
$config = require Yii::getPathOfAlias('admin').'/config/main.php';
$config['basePath'] = Yii::getPathOfAlias('admin');

Yii::createWebApplication(CMap::mergeArray($main, $config))->run();	