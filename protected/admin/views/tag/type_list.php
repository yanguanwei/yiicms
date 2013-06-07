<?php
$tabs = array(
    'base' => array(
        'label' => '类型列表',
    ),
    'create' => array(
        'label' => '创建类型',
        'url' => array('tag/typeCreate')
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
        'primaryKey' => 'name',
        'htmlOptions' => array('class' => 'table'),
        'titles' => array(
            'name' => '类型名',
            'title' => '显示名称',
            'operate' => '操作'
        )
    ));
$table->beginBody();
while( $table->nextRow() ) {
    $table->renderRow(
        $table->cell('name'),
        $table->cell('title', array(
                'type' => 'link',
                'typeOptions' => array(
                    'url' => $this->createUrl('list', array('type_name' => $table->data['name'])),
                    'class' => 'popuplayer iframe',
                    'popuplayer' => '{"iframeWidth":900, "iframeHeight":510}',
                    'title' => $table->data['title']
                )
            )
        ),
        $table->cell('operate', $table->updateButton('typeUpdate') . $table->deleteButton('typeDelete'))
    );
}
$table->endBody();

$table->beginFoot();
    $table->renderPager();
$table->endFoot();

$this->endWidget();

$widget->endTab();//baseTab

$this->endWidget(); //tabs