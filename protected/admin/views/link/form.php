<?php
echo $this->renderErrorSummary();

$tabs = array();

if ($isAttach) {
    $tabs['index'] = array(
        'label' => $model->title . '列表',
        'url' => array('index', 'cid' => $channel->id)
    );
}

$tabs['base'] = array('label' => $title);

$widget = $this->beginWidget(
    'application.widgets.Tabs',
    array(
        'title' => $title,
        'tabs' => $tabs,
        'defaultTab' => 'base'
    )
);

echo $this->renderHiddenField('id');

$widget->beginTab('base');

echo $this->renderTextRow('title', null, array('class' => 'text-input medium-input'));

echo $this->renderHiddenDisabledChannelTextRow('cid', null, array('class' => 'text-input medium-input'));

echo $this->renderTextRow(
    'url',
    "以http://开头",
    array('class' => 'text-input medium-input')
);

echo $this->renderCKFinderInputRow('logo');

echo $this->renderCheckboxRow('visible');

echo $this->renderTextRow('sort_id', null, array('class' => 'text-input'));

$widget->endTab(); //baseTab

$this->endWidget(); //contentBox

echo $this->renderSubmitRow();
?>