<?php 
echo $this->renderErrorSummary();

$widget = $this->beginWidget('application.widgets.Tabs', array(
	'title' => $title,
	'tabs' => array(
		'base' => array('label' => '图片信息')
	),
	'defaultTab' => 'base'
));  

echo $this->renderHiddenField('id');

$widget->beginTab('base');

	echo $this->renderTextRow('title', null, array('class' => 'text-input medium-input'));
	
	echo $this->renderTreeSelectRow(
			'cid', 
			Channel::getChannelTreeSelectOptionsForModel($this->model->cid)
		);

  echo $this->renderChannelTagSelectRow();

	echo $this->renderCKFinderInputRow('cover');
	
	
	echo $this->renderTextareaRow('keywords', '各关键字以半角“,”逗号隔开，且少于255个字符');
	
	echo $this->renderTextareaRow('description', '少于255个字符');


	echo $this->renderDateTimerRow('update_time', null, array('class' => 'text-input'));
	
	echo $this->renderSelectRow(
			'template',
			ThemeTemplate::getTemplateSelectOptions(),
			null,
			array('empty' => array('继承自栏目指定的模板'))
	);

$widget->endTab();//baseTab

$this->endWidget(); //tabs

echo $this->renderSubmitRow();
?>