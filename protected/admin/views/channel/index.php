<?php
$widget = $this->beginWidget('application.widgets.Tabs', array(
	'title' => $title,
	'tabs' => array(
		'index' => array('label' => '栏目列表'),
		'create' => array('label' => '添加栏目', 'url' => array('channel/create', 'theme_id' => $theme_id))
	),
	'defaultTab' => 'index'
));

	$widget->beginTab('index');
	
	$this->widget('ext.treetable.JTreeTable',array(
		'id' => 'treeTable',
		'primaryColumn' => 'id',
		'parentColumn' => 'parent_id',
		'columns' => array(
			
			'id' => array(
				'label'=>'ID',
				'headerHtmlOptions'=>array('width'=>80, 'style'=>'padding-left: 20px;'),
				'htmlOptions'=>array('style'=>''),
			),
			'sort_id' => array(
				'label' => '排序',
				'value' => CHtml::textField("sort_id[__id__]", '__sort_id__', array("class"=>"text-input sort_id", "size"=>5, "maxlength"=>5)),
				'htmlOptions' => array('class' => 'sort-column', 'style'=>'text-align:center; padding-right:20px;'),
				'headerHtmlOptions'=>array('style'=>'text-align:center; padding-right:20px;'),
			),
			'title'=>'栏目名称',
				
			'archive_model' => array(
				'label' => '模型',
				'headerHtmlOptions'=>array('width'=>80, 'style'=>'text-align:center'),
				'htmlOptions'=>array('style'=>'text-align:center')
			),
			'visible' => array(
				'label' => '可见',
				'headerHtmlOptions'=>array('width'=>40, 'style'=>'text-align:center'),
				'htmlOptions'=>array('style'=>'text-align:center')
			),
			'add' => array(
				'label' => '子栏目',
				'headerHtmlOptions'=>array('width'=>50, 'style'=>'text-align:center'),
				'htmlOptions'=>array('style'=>'text-align:center'),
				'value' => CHtml::link('<img alt="添加" src="'.$this->asset('images/icons/add_16.png').'" />', array('channel/create', 'theme_id' => '__theme_id__', 'parent_id' => '__id__'))
			),
			'edit' => array(
				'label' => '编辑',
				'headerHtmlOptions'=>array('width'=>40, 'style'=>'text-align:center'),
				'htmlOptions'=>array('style'=>'text-align:center'),
				'value' => '<a href="'.$this->createUrl('channel/update', array('id' => '__id__')).'"><img src="'.$this->asset('images/icons/pencil.png').'" alt="编辑" /></a>'
			),
			'delete' => array(
				'label' => '删除',
				'headerHtmlOptions'=>array('width'=>40, 'style'=>'text-align:center'),
				'htmlOptions'=>array('style'=>'text-align:center'),
				'value' => '<a href="'.$this->createUrl('channel/delete', array('id' => '__id__')).'" class="delete"><img src="'.$this->asset('images/icons/cross.png').'" alt="删除" /></a>'
			)
		),
		'items' => $channels
	));

	$this->renderPartial('/blocks/bulk_action', array(
			'hasOrder' => true
	));
	
	$widget->endTab();//index
	
$this->endWidget();
?>