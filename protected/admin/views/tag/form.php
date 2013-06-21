<?php 
echo $this->renderErrorSummary();

$widget = $this->beginWidget('application.widgets.Tabs', array(
	'title' => $title,
	'tabs' => array(
    'create' => array(
      'label' => '标签列表',
      'url' => array('tag/list', 'type_name' => $this->model->type_name)
    ),
		'base' => array('label' => '标签类型')
	),
	'defaultTab' => 'base'
));  

echo $this->renderHiddenField('id');
echo $this->renderHiddenField('type_name');

$widget->beginTab('base');

	echo $this->renderTextRow('title', null, array('class' => 'text-input medium-input'));
    echo $this->renderCKFinderInputRow('cover');
    echo $this->renderTextRow('sort_id', '越大越靠前', array('class' => 'text-input'));

$widget->endTab();//baseTab

$this->endWidget(); //tabs

echo $this->renderSubmitRow();
?>