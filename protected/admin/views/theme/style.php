<?php 
echo $this->renderErrorSummary();

$widget = $this->beginWidget('application.widgets.Tabs', array(
	'title' => '更新样式',
	'tabs' => array(
		'style' => array('label' => '样式'),
		'script' => array('label' => '脚本', 'url' => array('theme/script', id=> $this->model->id)),
	),
	'defaultTab' => 'style'
));  

echo $this->renderHiddenField('id');

$widget->beginTab('style');

	echo $this->renderTextareaRow('css', null, array(
	'style' => 'height:200px;'
));
	
$widget->endTab();//style

$this->endWidget(); //tabs

echo $this->renderSubmitRow();
?>