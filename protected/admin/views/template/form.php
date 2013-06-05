<?php 
echo $this->renderErrorSummary();

$tabs = array();
if ( !$this->model->theme_id )
	$tabs['index'] = array(
		'label' => '模板列表',
		'url' => array('index', 'theme_id' => $this->model->theme_id)		
	);

$tabs['base'] = array('label' => '模板信息');

$widget = $this->beginWidget('application.widgets.Tabs', array(
	'title' => $title,
	'tabs' => $tabs,
	'defaultTab' => 'base'
));  

echo $this->renderHiddenField('id');

$widget->beginTab('base');

	if ( $this->model->id ) {
		echo $this->renderHiddenDisabledTextRow(
			'path', '以“/”开头', 
			array(
				'class' => 'text-input medium-input'
			)
		);
	} else {
		if ( $this->model->theme_id ) {
			echo $this->renderSelectRow('path', ThemeTemplate::getTemplateSelectOptions());
		} else {
			echo $this->renderTextRow('path', '以“/”开头', array('class' => 'text-input medium-input'));
		}
	}
	
	if ( $this->model->theme_id ) {
		echo $this->renderHiddenDisabledSelectRow('theme_id', Theme::getThemeSelectOptions());
	} else {
		echo $this->renderHiddenField('theme_id');
	}
	
	echo $this->renderTextareaRow('content', null, array(
			'style' => 'height:200px;'
	));

$widget->endTab();//baseTab

$this->endWidget(); //tabs

echo $this->renderSubmitRow();
?>