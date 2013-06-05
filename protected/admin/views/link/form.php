<?php 
echo $this->renderErrorSummary();

$widget = $this->beginWidget('application.widgets.Tabs', array(
	'title' => $title,
	'tabs' => array(
		'base' => array('label' => '基本信息')
	),
	'defaultTab' => 'base'
));  ?>

<?php
echo $this->renderHiddenField('id'); 

$widget->beginTab('base');

	echo $this->renderTextRow('title', null, array('class' => 'text-input medium-input'));
	
	echo $this->renderChannelTreeSelectRow(
			'cid', 2
		);
	
	echo $this->renderTextRow(
		'url', "以http://开头", 
		array('class' => 'text-input medium-input'));
	
	echo $this->renderCKFinderInputRow('logo');
	
	echo $this->renderCheckboxRow('visible');
	
	echo $this->renderTextRow('sort_id', null, array('class' => 'text-input'));

$widget->endTab();//baseTab

$this->endWidget(); //contentBox

echo $this->renderSubmitRow();
?>