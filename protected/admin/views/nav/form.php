<?php
echo $this->renderErrorSummary();

$widget = $this->beginWidget(
    'application.widgets.Tabs',
    array(
        'title' => $title,
        'tabs' => array(
            'base' => array('label' => '基本信息')
        ),
        'defaultTab' => 'base'
    )
);

echo $this->renderHiddenField('id');

$widget->beginTab('base');

echo $this->renderTextRow('title', null, array('class' => 'text-input medium-input'));
echo $this->renderTextRow(
    'identifier',
    '在控制器中，通过指定 $this->activeNavKey 与该标识符相匹配而激活当前的导航',
    array('class' => 'text-input medium-input')
);

echo $this->renderHiddenTextRow(
    'theme_id',
    Theme::findThemeTitle($this->model->theme_id),
    null,
    array('class' => 'text-input medium-input')
);

echo $this->renderRow(
    $this->renderHiddenTextRow(
        'type_id',
        Nav::fetchNavTypeSelectOptions($this->model->type_id),
        null,
        array('class' => 'text-input medium-input')
    ),
    $this->renderTreeSelectField(
        'parent_id',
        Nav::fetchAllNavsForTreeSelect($this->model->theme_id, $this->model->type_id),
        0,
        null,
        array(
            'empty' => array(0 => '无（作为一级导航）'),
            'disabled' => $this->model->id ? 'disabled' : false
        )
    )
);


echo $this->renderTextRow(
    'url',
    '站内链接：controller/action?key1=value1&key2=value2<br />外接链接以http://开头',
    array('class' => 'text-input medium-input')
);

echo $this->renderTextRow('sort_id', '越大越靠前', array('class' => 'text-input'));

echo $this->renderCheckboxRow('enabled', '不启用，则在前台不显示此导航');

$widget->endTab(); //baseTab

$this->endWidget(); //tabs

echo $this->renderSubmitRow();
?>