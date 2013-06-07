<?php
$main = require dirname(__FILE__).'/protected/config/main.php';
$config = require Yii::getPathOfAlias('frontend').'/config/main.php';
$config['basePath'] = Yii::getPathOfAlias('frontend');
$config['theme'] = 'default';
$config = CMap::mergeArray($config, array (
  'name' => '宁波购物节',
  'language' => 'zh_cn',
));
$config['params'] = array (
  'name' => '宁波购物节',
  'title' => '2013宁波购物节',
  'logo' => '/uploads/images/logo.jpg',
  'language' => 'zh_cn',
  'url' => 'http://www.nbsp.cc',
  'icp' => '浙ICP备13001534号-1',
  'address' => '宁波国家高新区创苑路750号A座2楼',
  'hotline' => '0574-87903707',
  'keywords' => '宁波,软件',
  'description' => '宁波软件园，宁波智慧园',
  'theme_id' => 1,
);
Yii::createWebApplication(CMap::mergeArray($main, $config))->run();	