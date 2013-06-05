<?php 
$tabs = array();
foreach ( $types as $tid => $tname) {
	$tabs['list_' . $tid] = array(
		'label' => $tname,
		'url' => array('nav/index', 'theme_id' => $theme_id, 'type_id' => $tid)		
	);
}

$widget = $this->beginWidget('application.widgets.Tabs', array(
	'title' => $title,
	'tabs' => $tabs,
	'defaultTab' => 'list_' . $type_id
));  

	$widget->beginTab('list_' . $type_id);
	
	$this->widget('ext.treetable.JTreeTable',array(
		'id' => 'treeTable',
		'primaryColumn' => 'id',
		'parentColumn' => 'parent_id',
		'columns' => array(
			'id' => array(
				'label'=>'ID',
				'headerHtmlOptions'=>array('width'=>100, 'style'=>'text-align:center'),
				'htmlOptions'=>array('style'=>'text-align:center'),
			),
			'sort_id' => array(
				'label' => '排序',
				'value' => CHtml::textField("sort_id[__id__]", '__sort_id__', array("class"=>"text-input sort_id", "size"=>5, "maxlength"=>5)),
				'htmlOptions' => array('class' => 'sort-column', 'style'=>'text-align:center; padding-right:20px;'),
				'headerHtmlOptions'=>array('style'=>'text-align:center; padding-right:20px;'),
			),
			'title'=>array(
				'label' => '导航名称',
				'headerHtmlOptions'=>array('width'=>120, 'style'=>'text-align:center'),
				'htmlOptions'=>array('style'=>'text-align:center')
			),
			'identifier'=> array(
				'label' => '导航标识符',
				'headerHtmlOptions'=>array('width'=>120, 'style'=>'text-align:center'),
				'htmlOptions'=>array('style'=>'text-align:center')
			),
			'url'=> 'URL',
			'enabled' => array(
				'label' => '启用',
				'headerHtmlOptions'=>array('width'=>40, 'style'=>'text-align:center'),
				'htmlOptions'=>array('style'=>'text-align:center')
			),
			'add' => array(
				'label' => '子导航',
				'headerHtmlOptions'=>array('width'=>50, 'style'=>'text-align:center'),
				'htmlOptions'=>array('style'=>'text-align:center'),
				'value' => CHtml::link('<img alt="添加" src="'.$this->asset('images/icons/add_16.png').'" />', array('create', 'theme_id' => '__theme_id__', 'type_id' => '__type_id__', 'parent_id' => '__id__'))
			),
			'edit' => array(
				'label' => '编辑',
				'headerHtmlOptions'=>array('width'=>40, 'style'=>'text-align:center'),
				'htmlOptions'=>array('style'=>'text-align:center'),
				'value' => '<a href="'.$this->createUrl('update', array('id' => '__id__')).'"><img src="'.$this->asset('images/icons/pencil.png').'" alt="编辑" /></a>'
			),
			'delete' => array(
				'label' => '删除',
				'headerHtmlOptions'=>array('width'=>40, 'style'=>'text-align:center'),
				'htmlOptions'=>array('style'=>'text-align:center'),
				'value' => '<a href="'.$this->createUrl('delete', array('id' => '__id__')).'" class="delete"><img src="'.$this->asset('images/icons/cross.png').'" alt="删除" /></a>'
			)
		),
		'items' => $data
	));
	

	$this->renderPartial('/blocks/bulk_action', array(
			'hasOrder' => true
	));
	$widget->endTab();
$this->endWidget(); ?>