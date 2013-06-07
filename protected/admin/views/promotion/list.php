<?php
function can_delete_news($cid)
{
	if ( !Yii::app()->user->isAdmin() && in_array($cid, array(14, 15)) )
		return false;
	return true;
}

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
		'title' => '新闻标题',
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
			), array(
				'highlight' => $table->data['is_highlight'],
				'top' => $table->data['is_top'],
				'id' => $table->data['id'],
				'cover' => $table->data['cover'] ? 1 : 0
				)
			),
			$table->cell('update_time', array('type' => 'dateTime')),
			$table->cell('operate', $table->updateButton() . (can_delete_news($channel_id) ? $table->deleteButton() : ''))
		);
	}
	$table->endBody();
	
	$table->beginFoot();
		if ( can_delete_news($channel_id) )
			$this->renderPartial('/blocks/bulk_action', array(
				'name' => 'id',
				'options' => array(
					'批量删除' => $this->createUrl('delete'),
					'批量置顶' => $this->createUrl('ding'),
					'取消置顶' => $this->createUrl('ding', array('disabled' => 1)),
					'批量高亮' => $this->createUrl('highlight'),
					'取消高亮' => $this->createUrl('highlight', array('disabled' => 1))
			)
		));
		$table->renderPager();
	$table->endFoot();
	
$this->endWidget();

$widget->endTab();//baseTab

$this->endWidget(); //tabs

?>
