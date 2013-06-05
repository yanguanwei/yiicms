<?php 
echo $this->renderErrorSummary();

$widget = $this->beginWidget('application.widgets.Tabs', array(
	'title' => '更新脚本',
	'tabs' => array(
		'css' => array(
			'label' => '样式',
			'url' => array('theme/style', 'id' => $this->model->id)
		),
		'script' => array('label' => '脚本'),
	),
	'defaultTab' => 'script'
));  

echo $this->renderHiddenField('id');

$widget->beginTab('script');

	echo $this->renderTextareaRow('js', null, array(
	'style' => 'height:200px;'
));
	
$widget->endTab();//script

$this->endWidget(); //tabs

echo $this->renderSubmitRow();
?>