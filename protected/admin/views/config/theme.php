<?php 
echo $this->renderErrorSummary();

$widget = $this->beginWidget('application.widgets.Tabs', array(
	'title' => $title,
	'tabs' => array(
		'configs' => array('label' => '配置信息')
	),
	'defaultTab' => 'configs'
));  

echo $this->renderHiddenField('id');

$widget->beginTab('configs');

echo ConfigType::renderForm($this);

$widget->endTab();//configs

$this->endWidget(); //tabs

echo $this->renderSubmitRow();
?>