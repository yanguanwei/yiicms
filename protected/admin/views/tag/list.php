<?php
$tabs = array(
    'base' => array(
        'label' => '标签列表',
    ),
    'create' => array(
        'label' => '创建标签',
        'url' => array('tag/create', 'type_name' => $type_name)
    )
);

$defaultTab = 'base';

$widget = $this->beginWidget('application.widgets.Tabs', array(
        'title' => $title,
        'tabs' => $tabs,
        'defaultTab' => $defaultTab
    ));

$widget->beginTab($defaultTab);

$table = $this->beginWidget('apps.ext.young.ListTable', array(
        'dataProvider' => $dataProvider,
        'htmlOptions' => array('class' => 'table'),
        'titles' => array(
            'id' => 'ID',
            'sort_id' => '排序',
            'title' => '标签名称',
            'operate' => '操作'
        )
    ));
$table->beginBody();
while( $table->nextRow() ) {
    $table->renderRow(
        $table->cell('id'),
        $table->cell(
            'sort_id',
            CHtml::textField("sort_id[{$table->data['id']}]", $table->data['sort_id'], array("class"=>"text-input sort_id", "size"=>5, "maxlength"=>5))
        ),
        $table->cell('title', array(
                'type' => 'link',
                'typeOptions' => array(
                    'url' => $this->createUrl('update', array('id' => $table->data['id']))
                )
            )
        ),
        $table->cell('operate', $table->updateButton() . $table->deleteButton())
    );
}
$table->endBody();

$table->beginFoot();
    $this->renderPartial('/blocks/bulk_action', array(
        'hasOrder' => true
    ));
    $table->renderPager();
$table->endFoot();

$this->endWidget();

$widget->endTab();//baseTab

$this->endWidget(); //tabs