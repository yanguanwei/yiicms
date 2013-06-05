<?php 
$widget = $this->beginWidget('application.widgets.Tabs', array(
	'title' => '配置列表',
	'tabs' => array(
		'index' => array(
			'label' => '配置列表'
		),
		'create' => array(
			'label' => '创建配置',
			'url' => array('create')	
		)
	),
	'defaultTab' => 'index'
)); 
	$widget->beginTab('index');

$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
    	array(
    		'name' => 'sort_id',
    		'value' => 'CHtml::textField("sort_id[$data->id]", $data->sort_id, array("class"=>"text-input sort_id", "size"=>5, "maxlength"=>5))',
    		'htmlOptions' => array('class' => 'sort-column'),
    		'type' => 'raw'
    	),
        'title',
        'key',
    	array(
    		'name' => 'type',
    		'value' => 'ConfigType::getTypeSelectOpotions($data->type)'		
    	),
    	'is_app',
        array(
            'class'=>'CButtonColumn',
        	'template'=>'{update} {delete}'
        )
    ),
	'pager'=>array(
		'header'=>'',
		'firstPageLabel'=>'&lt;&lt;',
		'prevPageLabel'=>'&lt;',
		'nextPageLabel'=>'&gt;',
		'lastPageLabel'=>'&gt;&gt;'
	)
));

$this->renderPartial('/blocks/bulk_action', array(
	'hasOrder' => true
));

	$widget->endTab();
$this->endWidget();

?>


