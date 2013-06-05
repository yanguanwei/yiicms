<style type="text/css">
ul#theme_list li {float: left;margin: 0 15px 0 0;background: 0;}
ul#theme_list li span:hover { color:#555;}
ul#theme_list li a { padding: 0 5px;}
</style>

<?php
$widget = $this->beginWidget('application.widgets.Tabs', array(
	'title' => '主题列表',
	'tabs' => array(
		'list' => array('label' => '主题列表')
	),
	'defaultTab' => 'list'
));

$widget->beginTab('list');

echo '<ul id="theme_list">';

foreach ($themes as $theme) {
	$editurl = $this->createUrl('template/index', array('theme_id' => $theme['id']));
	$delurl = $this->createUrl('theme/delete', array('id' => $theme['id']));
	echo <<<CODE
<li>
	<div class="shortcut-button">
		<span>
		
		{$theme['title']}<br />
		{$theme['name']}<br />
		<a href="{$editurl}">详细</a>|<a href="{$delurl}" class="delete">删除</a>
		</span>
	</div>
</li>
CODE;
}

echo '</ul>';

$widget->endTab();//baseTab

$this->endWidget(); //tabs
?>