<?php 
echo $this->renderErrorSummary();

$widget = $this->beginWidget('application.widgets.Tabs', array(
	'title' => $title,
	'tabs' => array(
		'base' => array('label' => '基本信息'),
		'configs' => array('label' => '配置信息'),
		'css' => array('label' => '样式'),
		'javascript' => array('label' => '脚本')
	),
	'defaultTab' => 'base'
));  

echo $this->renderHiddenField('id');

$widget->beginTab('base');

	echo $this->renderTextRow(
			'title',
			null,
			array('class' => 'text-input medium-input')
	);
	
	if ( $this->model->id ) {
		echo $this->renderHiddenTextRow(
				'name',
				$this->model->name,
				"英文字母组成",
				array(
					'class' => 'text-input medium-input',
					'disabled' => 'disabled'
				)
		);
	} else {
		echo $this->renderTextRow(
			'name',
			"英文字母组成",
			array(
				'class' => 'text-input medium-input',
			)
		);
	}
	
	echo $this->renderTextRow(
			'entry',
			".php",
			array('class' => 'text-input')
	);
	
$widget->endTab();//base

$widget->beginTab('css');

echo $this->renderTextareaRow('css', null, array(
	'style' => 'height:200px;'
));

$widget->endTab();//css

$widget->beginTab('javascript');

echo $this->renderTextareaRow('js', null, array(
	'style' => 'height:200px;'
));

$widget->endTab();//javascript

$widget->beginTab('configs');

echo ConfigType::renderForm($this);

$widget->endTab();//configs

$this->endWidget(); //tabs

echo $this->renderSubmitRow();
?>