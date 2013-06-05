<?php 
echo $this->renderErrorSummary();

$widget = $this->beginWidget('application.widgets.Tabs', array(
	'title' => $title,
	'tabs' => array(
		'index' => array(
			'label' => '配置列表',
			'url' => array('index')		
		),
		'base' => array('label' => '基本信息')
	),
	'defaultTab' => 'base'
));  

echo $this->renderHiddenField('id');

$widget->beginTab('base');

	echo $this->renderTextRow('title', null, array('class' => 'text-input medium-input'));
	echo $this->renderTextRow('key', null, array('class' => 'text-input'));
	
	echo $this->renderRow(
			$this->renderSelectField(
					'type',
					ConfigType::getTypeSelectOpotions()
			),
			$this->renderCheckboxField('is_app', '把此配置信息传递给Yii中的CWebApplication对象')
		);
	
	echo $this->renderTextRow('note', null, array('class' => 'text-input medium-input'));

	echo $this->renderTextRow('default', null, array('class' => 'text-input medium-input'));
	
	echo $this->renderTextRow('sort_id', null, array('class' => 'text-input'));
		
$widget->endTab();//baseTab

$this->endWidget(); //tabs

echo $this->renderSubmitRow();
?>