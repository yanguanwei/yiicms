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
            'title' => '标签名称',
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
        $table->cell('operate', $table->updateButton() . $table->deleteButton())
    );
}
$table->endBody();

$table->beginFoot();
    $table->renderPager();
$table->endFoot();

$this->endWidget();

$widget->endTab();//baseTab

$this->endWidget(); //tabs