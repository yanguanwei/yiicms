<?php
$tabs = array(
	'base' => array(
		'label' => '全部列表',
		'url' => array('index', 'cid' => $channel_topid)
	)
);

$defaultTab = 'base';

foreach ( $channels as $id => $channelname ) {
	$tab = 'channel_' . $id;
	$tabs[$tab] = array(
		'label' => $channelname,
		'url' => array('list', 'cid' => $id)		
	);
	if ( $channel_id == $id)
		$defaultTab = $tab;
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
				'title' => '图片标题',
				'update_time' => '更新时间',
				'operate' => '操作'
		)
));
$table->beginBody();
while( $table->nextRow() ) {
	$table->renderRow(
			$table->cell('id'),
			$table->cell('title', array(
					'type' => 'link',
					'typeOptions' => array(
							'url' => $this->createUrl('update', array('id' => $table->data['id']))
					)
				)
			),
			$table->cell('update_time', array('type' => 'dateTime')),
			$table->cell('operate', $table->updateButton() . $table->deleteButton() )
	);
}
$table->endBody();

$table->beginFoot();

$this->renderPartial('/blocks/bulk_action', array(
	'name' => 'id',
	'options' => array(
		'批量删除' => $this->createUrl('delete')
	)
));

$table->renderPager();
$table->endFoot();

$widget->endTab();//baseTab

$this->endWidget(); //tabs

?>
