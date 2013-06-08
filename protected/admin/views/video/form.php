<?php 
echo $this->renderErrorSummary();

$tabs = array();

if ($isAttach) {
    $tabs['index'] = array(
        'label' => $model->title . '列表',
        'url' => array('index', 'cid' => $channel->id)
    );
}

$tabs['base'] = array('label' => '基本信息');
$tabs['advanced'] = array('label' => '高级设置');

$widget = $this->beginWidget('application.widgets.Tabs', array(
	'title' => $title,
	'tabs' => $tabs,
	'defaultTab' => 'base'
));

echo $this->renderHiddenField('id');

$widget->beginTab('base');

	echo $this->renderTextRow('title', null, array('class' => 'text-input medium-input'));

  echo $this->renderHiddenDisabledChannelTextRow('cid', null, array('class' => 'text-input medium-input'));

	echo $this->renderCKFinderInputRow('cover', null, false);
	
	echo $this->renderDateRow('update_time', null, array('class' => 'text-input'));

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
?>