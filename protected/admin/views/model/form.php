<?php 
echo $this->renderErrorSummary();

$widget = $this->beginWidget('application.widgets.Tabs', array(
	'title' => $title,
	'tabs' => array(
		'index' => array(
			'label' => '模型列表',
			'url' => array('model/index')		
		),
		'base' => array('label' => '创建模型')
	),
	'defaultTab' => 'base'
));  ?>

<?php
echo $this->renderHiddenField('id'); 

$widget->beginTab('base');

	echo $this->renderTextRow('title', null, array('class' => 'text-input medium-input'));
	
	echo $this->renderTextRow('table_name', 
		'该表中必须含有cid字段', 
		array('class' => 'text-input medium-input'));
	
	echo $this->renderTextRow('alias', 
		'控制器文件位于protected/admin/controllers中，必须有actionIndex($cid)动作'
	, array('class' => 'text-input medium-input'));

$widget->endTab();//baseTab

$this->endWidget(); //contentBox

echo $this->renderSubmitRow();
?>