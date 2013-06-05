<?php
$main = require dirname(__FILE__).'/protected/config/main.php';
$config = require Yii::getPathOfAlias('frontend').'/config/main.php';
$config['basePath'] = Yii::getPathOfAlias('frontend');
$config['theme'] = 'default';
$config = CMap::mergeArray($config, array (
  'name' => '宁波软件园',
  'language' => 'zh_cn',
));
$config['params'] = array (
  'name' => '宁波软件园',
  'title' => '宁波软件园 | 宁波智慧园',
  'logo' => '/uploads/images/logo.jpg',
  'language' => 'zh_cn',
  'url' => 'http://www.nbsp.cc',
  'icp' => '浙ICP备09109305号-1',
  'address' => '宁波国家高新区创苑路750号A座2楼',
  'hotline' => '0574-87903707',
  'keywords' => '宁波,软件',
  'description' => '宁波软件园，宁波智慧园',
  'phone' => '12333333333333',
  'theme_id' => 1,
);
Yii::createWebApplication(CMap::mergeArray($main, $config))->run();	