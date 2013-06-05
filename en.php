<?php
$main = require dirname(__FILE__).'/protected/config/main.php';
$config = require Yii::getPathOfAlias('frontend').'/config/main.php';
$config['basePath'] = Yii::getPathOfAlias('frontend');
$config['theme'] = 'en';
$config = CMap::mergeArray($config, array (
  'name' => '',
  'language' => 'zh_cn',
));
$config['params'] = array (
  'name' => '',
  'title' => '',
  'logo' => '',
  'language' => 'zh_cn',
  'url' => 'http://',
  'icp' => '',
  'address' => '',
  'hotline' => '',
  'keywords' => '',
  'description' => '',
  'phone' => '',
  'theme_id' => 11,
);
Yii::createWebApplication(CMap::mergeArray($main, $config))->run();	