<?php
$script = <<<code
$('td.title-column').each(function() {
	if ( $(this).attr('highlight') == '1' ) {
		$(this).append('<span class="highlight">[亮]</span>');
	}
		
	if ( $(this).attr('top') == '1' ) {
		$(this).append('<span class="top">[顶]</span>');
	}
		
	if ( $(this).attr('cover') == '1' ) {
		$(this).append('<span class="cover">[图]</span>');
	}
});
code;

Yii::app()->getClientScript()->registerScript('title-column-highlight', $script);

if ($isAttach) {
    $tabs = array(
        'base' => array(
            'label' => $model->title . '列表',
            'url' => array('index', 'cid' => $channel->id)
        ),
        'create' => array(
            'label' => '创建' . $model->title,
            'url' => array('create', 'cid' => $channel->id)
        )
    );

    $defaultTab = 'base';

    $title = $channel->title;
} else {
    $parent = $channel->getParentChannel();
    if (!$parent) {
        $parent = $channel;
    }

    $tabs = array(
        'base' => array(
            'label' => '全部列表',
            'url' => array('index', 'cid' => $parent->id)
        )
    );

    $title = $parent->title;
    $defaultTab = 'base';

    foreach ($parent->getSubChannels() as $id => $sub) {
        $tab = 'channel_' . $id;
        $tabs[$tab] = array(
            'label' => $sub->title,
            'url' => array('list', 'cid' => $id)
        );
        if ($channel->id == $id) {
            $defaultTab = $tab;
        }
    }
}

$widget = $this->beginWidget(
    'application.widgets.Tabs',
    array(
        'title' => $title,
        'tabs' => $tabs,
        'defaultTab' => $defaultTab
    )
);

$this->renderPartial(
    '/blocks/filters',
    array(
        'filters' => $filters
    )
);

$widget->beginTab($defaultTab);

$table = $this->beginWidget(
    'apps.ext.young.ListTable',
    array(
        'dataProvider' => $dataProvider,
        'htmlOptions' => array('class' => 'table'),
        'selectable' => true,
        'titles' => $this->getFormCellLabels()
    )
);
$table->beginBody();
while ($table->nextRow()) {
    $row = array();
    foreach ($this->getFormCell($table) as $name => $args) {
        array_unshift($args, $name);
        $row[] = call_user_func_array(array($table, 'cell'), $args);
    }
    $table->renderRow($row);
}
$table->endBody();

$table->beginFoot();

$this->renderPartial(
    '/blocks/bulk_action',
    array(
        'name' => 'id',
        'options' => $this->getBulkActions()
    )
);
$table->renderPager();
$table->endFoot();

$this->endWidget();

$widget->endTab(); //baseTab

$this->endWidget(); //tabs

