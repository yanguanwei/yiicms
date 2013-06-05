<?php 
echo $this->renderErrorSummary();

$widget = $this->beginWidget('application.widgets.Tabs', array(
	'title' => $title,
	'tabs' => array(
		'base' => array('label' => '基本信息')
	),
	'defaultTab' => 'base'
));  

echo $this->renderHiddenField('id');

$widget->beginTab('base');

	echo $this->renderTextRow('title', null, array('class' => 'text-input medium-input'));
	echo $this->renderTextRow('identifier', 
		'在控制器中，通过指定 $this->activeNavKey 与该标识符相匹配而激活当前的导航', 
		array('class' => 'text-input medium-input'));
	
	echo $this->renderHiddenDisabledSelectRow(
			'theme_id',
			Theme::getThemeSelectOptions()
		);
	
	echo $this->renderRow(
			$this->renderHiddenDisabledSelectField('type_id', Nav::getNavTypeSelectOptions()),
			$this->model->parent_id ? $this->renderTreeSelectField(
					'parent_id',
					Nav::getAllNavsForTreeSelect($this->model->theme_id, $this->model->type_id),
					0, null,
					array('empty' => array(0 => '无（作为一级导航）')))
			: $this->renderHiddenDisabledTreeSelectField(
					'parent_id',
					Nav::getAllNavsForTreeSelect($this->model->theme_id, $this->model->type_id),
					null,
					array('empty' => array(0 => '无（作为一级导航）')))
		);

	
	echo $this->renderTextRow(
		'url',
		'站内链接：controller/action?key1=value1&key2=value2<br />外接链接以http://开头', 
		array('class' => 'text-input medium-input')
	);

	echo $this->renderTextRow('sort_id', '越大越靠前', array('class' => 'text-input'));
	
	echo $this->renderCheckboxRow('enabled', '不启用，则在前台不显示此导航');
	
$widget->endTab();//baseTab

$this->endWidget(); //tabs

echo $this->renderSubmitRow();
?>