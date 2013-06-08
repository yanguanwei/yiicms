<?php

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
        ),
    );

    $defaultTab = 'base';
    $title = $parent->title;

    foreach ($channel->getSubChannels() as $id => $sub) {
        $tab = 'channel_' . $id;
        $tabs[$tab] = array(
            'label' => $sub->title,
            'url' => array('list', 'cid' => $id)
        );
        if ($channel->id == $id)
            $defaultTab = $tab;
    }
}

$widget = $this->beginWidget('application.widgets.Tabs', array(
        'title' => $title,
        'tabs' => $tabs,
        'defaultTab' => $defaultTab
    ));

$widget->beginTab($defaultTab);

$table = $this->beginWidget('apps.ext.young.ListTable', array(
		'dataProvider' => $dataProvider,
		'htmlOptions' => array('class' => 'table'),
		'selectable' => true,
		'titles' => array(
				'id' => 'ID',
				'sort_id' => '排序',
				'title' => '名称',
				'url' => '网址',
				'update_time' => '更新时间',
				'operate' => '操作'
		)
));
$table->beginBody();
while( $table->nextRow() ) {
	$table->beginRow();
		$table->renderCell('id');
		$table->renderCell(
			'sort_id', 
			CHtml::textField("sort_id[{$table->data['id']}]", $table->data['sort_id'], array("class"=>"text-input sort_id", "size"=>5, "maxlength"=>5))
		);
		$table->renderCell('title', array(
				'type' => 'link',
				'typeOptions' => array(
					'url' => $this->createUrl('update', array('id' => $table->data['id']))
				)
		));
		$table->renderCell('url', array('type' => 'link'));
		$table->renderCell('post_time', array('type' => 'dateTime'));
		$table->renderCell('operate', $table->updateButton() . $table->deleteButton() );
	$table->endRow();
}
$table->endBody();

$table->beginFoot();
$this->renderPartial('/blocks/bulk_action', array(
		'name' => 'id',
		'options' => array(
			'批量删除' => $this->createUrl('delete')
		),
		'hasOrder' => true
));
$table->renderPager();
$table->endFoot();

$this->endWidget();

$widget->endTab();//baseTab

$this->endWidget(); //tabs

