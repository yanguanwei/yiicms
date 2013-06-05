<?php $widget = $this->beginWidget('application.widgets.Tabs', array(
	'title' => '用户列表',
	'tabs' => array(
		'index' => array(
			'label' => '全部用户'
		)
	),
	'defaultTab' => 'index'
));  ?>
	<?php $widget->beginTab('index');?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider'=>$dataProvider,
    'columns'=>array(
    	array(
			'name' => 'id',
			'htmlOptions' => array('class' => 'id-column')
		),
        'username',
        'email',
        array(
            'name'=>'last_login',
            'value'=>'date("Y-m-d H:i:s", $data->last_login)',
        	'htmlOptions' => array('class' => 'time-column')
        ),
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


