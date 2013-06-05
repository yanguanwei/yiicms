<style type="text/css">
ul#theme_list li {float: left;margin: 0 15px 0 0;background: 0;}
ul#theme_list li span:hover { color:#555;}
ul#theme_list li a { padding: 0 5px;}
ul#theme_list li .shortcut-button { height:60px; width:190px;}
</style>

<?php

$tabs = array('list' => array('label' => '模板列表'));

if ( $theme_id ) {
	$tabs['update'] = array(
			'label' => '编辑主题',
			'url' => array('theme/update', 'id' => $theme_id)
	);
	
	$tabs['css'] = array(
			'label' => '样式',
			'url' => array('theme/style', 'id' => $theme_id)
	);
	
	$tabs['js'] = array(
			'label' => '脚本',
			'url' => array('theme/script', 'id' => $theme_id)
	);
} else {
	$tabs['create'] = array(
		'label' => '创建全局模板',
		'url' => array('template/create', 'theme_id' => $theme_id)
	);
}

$widget = $this->beginWidget('application.widgets.Tabs', array(
	'title' => $title,
	'tabs' => $tabs,
	'defaultTab' => 'list'
));

$widget->beginTab('list');

echo '<ul id="theme_list">';

foreach ($data as $row) {
	echo sprintf('<li><div class="shortcut-button"><span>%s<br /><a href="%s" class="%s" popuplayer=\'%s\' title="%s">编辑</a>|<a href="%s" class="delete">删除</a></span></div></li>',
		$row['path'], 
		$this->createUrl('update', array('id' => $row['id'])), 
		$theme_id ? 'popuplayer iframe' : '', 
		$theme_id ? '{"iframeWidth":900, "iframeHeight":580}' : '',
		$title . '模板更新',
		$this->createUrl('delete', array('id' => $row['id']))
		 
	);
CODE;
}

echo '</ul>';

$widget->endTab();//baseTab

$this->endWidget(); //tabs
?>