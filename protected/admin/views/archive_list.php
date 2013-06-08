<style type="text/css">
    .table-filters { border-bottom: 1px solid #CCCCCC; padding: 5px 0; margin: 0 0 10px 0;}
    .table-filters label { font-size: bold; display: inline-block; margin-right: 10px;}

</style>
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
        if ($channel->id == $id)
            $defaultTab = $tab;
    }
}

$widget = $this->beginWidget('application.widgets.Tabs', array(
        'title' => $title,
        'tabs' => $tabs,
        'defaultTab' => $defaultTab
    ));

if ($filters) {
    echo '<form class="table-filters" method="get">';
    echo '<input type="hidden" name="r" value="' . $_GET['r'] .'" />';
    echo '<input type="hidden" name="cid" value="' . $_GET['cid'] .'" />';
    echo '<label>筛选: </label>';
    echo implode("\n", $filters) . "\n";
    echo '<input type="submit" value="搜索" class="button" /></form>';
}

$widget->beginTab($defaultTab);

$table = $this->beginWidget('apps.ext.young.ListTable', array(
    'dataProvider' => $dataProvider,
    'htmlOptions' => array('class' => 'table'),
    'selectable' => true,
    'titles' => array(
      'id' => 'ID',
      'title' => '标题',
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
    $table->cell('operate', $table->updateButton() . $table->deleteButton())
  );
}
$table->endBody();

$table->beginFoot();

  $this->renderPartial('/blocks/bulk_action', array(
      'name' => 'id',
      'options' => $this->getBulkActions()
    ));
$table->renderPager();
$table->endFoot();

$this->endWidget();

$widget->endTab();//baseTab

$this->endWidget(); //tabs

