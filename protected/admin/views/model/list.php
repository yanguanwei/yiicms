<?php $widget = $this->beginWidget('application.widgets.Tabs', array(
	'title' => '文档模型',
	'tabs' => array(
		'index' => array(
			'label' => '模型列表'
		),
		'create' => array(
			'label' => '创建模型',
			'url' => array('model/create')		
		)
	),
	'defaultTab' => 'index'
));  ?>
	<?php $widget->beginTab('index');?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
    	'name',
        'title',
        'table_name',
    	'controller',
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
?>
	
	<?php $widget->endTab();?>
<?php $this->endWidget(); ?>


