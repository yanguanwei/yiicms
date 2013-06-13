<?php 
echo $this->renderErrorSummary();

$widget = $this->beginWidget('application.widgets.Tabs', array(
	'title' => $title,
	'tabs' => array(
		'base' => array('label' => '基本信息'),
		'advanced' => array('label' => '高级设置')
	),
	'defaultTab' => 'base'
));  

echo $this->renderHiddenField('id');

$widget->beginTab('base');

	echo $this->renderTextRow('title', null, array('class' => 'text-input medium-input'));
	
	echo $this->renderCheckboxListRow('is_highlight', 'is_top');


  echo $this->renderHiddenDisabledChannelTextRow('cid', null, array('class' => 'text-input medium-input'));

  echo $this->renderChannelTagSelectRow();

	echo $this->renderCKFinderInputRow('cover');

  echo $this->renderTextRow('discounts', null, array('class' => 'text-input medium-input'));

  echo $this->renderRow(
    $this->renderDateRow('start_time', null, array('class' => 'text-input')),
    $this->renderDateRow('end_time', null, array('class' => 'text-input'))
  );

	echo $this->renderCKEditorRow('content');

$widget->endTab();//baseTab

$widget->beginTab('advanced');

echo $this->renderSelectRow(
		'template',
		ThemeTemplate::getTemplateSelectOptions(),
		null,
		array('empty' => array('继承自栏目指定的模板'))
);


echo $this->renderRow(
		$this->renderTextareaField('keywords', '各关键字以半角“,”逗号隔开，且少于255个字符'),
		$this->renderTextareaField('description', '少于255个字符')	
	);

$widget->endTab();//advanced

$this->endWidget(); //tabs

echo $this->renderSubmitRow();
