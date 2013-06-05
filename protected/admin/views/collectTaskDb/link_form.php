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
	
	echo $this->renderTextRow('configs[table]', null, array('class' => 'text-input'));
	
	echo $this->renderRow(
			$this->renderTextField('configs[pk]', null, array('class' => 'text-input')),
			$this->renderTextField('configs[title]', null, array('class' => 'text-input'))
	);
	
	echo $this->renderRow(
			$this->renderTextField('configs[url]', null, array('class' => 'text-input')),
			$this->renderTextField('configs[logo]', null, array('class' => 'text-input'))
	);
	
	echo $this->renderTextField('configs[order]', null, array('class' => 'text-input'));

	echo $this->renderTextRow('configs[where]', null, array('class' => 'text-input medium-input'));
	
	echo $this->renderCheckboxRow('is_repeat');

$widget->endTab();//baseTab

$this->endWidget(); //contentBox

echo $this->renderSubmitRow();
?>