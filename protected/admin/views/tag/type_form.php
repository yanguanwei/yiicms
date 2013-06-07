<?php 
echo $this->renderErrorSummary();

$widget = $this->beginWidget('application.widgets.Tabs', array(
	'title' => $title,
	'tabs' => array(
    'create' => array(
      'label' => '类型列表',
      'url' => array('tag/index')
    ),
		'base' => array('label' => '标签类型')
	),
	'defaultTab' => 'base'
));  

echo $this->renderTextRow('name', '类型名必须为字母、数字、下划线', array(
    'class' => 'text-input medium-input',
    'disabled' => $this->model->getScenario()=='insert' ? false : 'disabled'
  ));

$widget->beginTab('base');

	echo $this->renderTextRow('title', null, array('class' => 'text-input medium-input'));

$widget->endTab();//baseTab

$this->endWidget(); //tabs

echo $this->renderSubmitRow();
?>