<?php

echo $this->renderErrorSummary();

$widget = $this->beginWidget(
    'application.widgets.Tabs',
    array(
        'title' => $title,
        'tabs' => array(
            'base' => array('label' => '栏目信息'),
            'seo' => array('label' => 'SEO信息'),
            'tags' => array('label' => '标签信息')
        ),
        'defaultTab' => 'base'
    )
);

echo $this->renderHiddenField('id');

$widget->beginTab('base');

echo $this->renderTextRow('title', null, array('class' => 'text-input medium-input'));

echo $this->renderTextRow(
    'alias',
    '可直接通过 index.php?r=channel/别名 这样的路径来访问此栏目；<br />如果是一级栏目，此别名可激活与标识符相同的导航',
    array('class' => 'text-input medium-input')
);

echo $this->renderRow(
    $this->renderHiddenTextField(
        'parent_id',
        $this->model->parent_id ? Channel::findChannelTitle($this->model->parent_id) : '无（作为一级栏目）',
        null, array('class' => 'text-input medium-input')
    ),
    $this->renderSelectField(
        'model_name',
        ChannelModel::fetchChannelModelSelectOptions(),
        null,
        array(
            'disabled' => ($this->model->getScenario() == 'insert' && !$this->model->parent_id) ? false : 'disabled'
        )
    )
);

echo $this->renderRow(
    $this->renderSelectField('theme_id', Theme::fetchThemeSelectOptions(), null, array(
        'disabled' => ($this->model->getScenario() == 'insert' && !$this->model->parent_id) ? false : 'disabled'
    )),
    $this->renderSelectField(
        'channel_attach',
        ChannelModel::fetchChannelModelSelectOptions(),
        null,
        array(
            'disabled' => $this->model->getScenario() == 'insert' ? false : 'disabled',
            'empty' => ' '
        )
    )
);

$templates = ThemeTemplate::getTemplateSelectOptions();
echo $this->renderRow(
    $this->renderSelectField(
        'channel_template',
        $templates,
        '当不使用模板时，将不能访问此栏目',
        array(
            'empty' => array(
                '' => '不使用模板',
                '1' => '跳转至第一个子栏目'
            )
        )
    ),
    $this->renderSelectField(
        'archive_template',
        $templates,
        '当不使用内容模板且内容自身也没指定模板时，则不能访问内容详细页',
        array('empty' => '不使用模板')
    )
);


echo $this->renderCheckboxRow('visible', '不显示在后台管理的栏目导航中');

echo $this->renderTextRow('sort_id', '越大越靠前', array('class' => 'text-input medium-input'));

$widget->endTab(); //baseTab

$widget->beginTab('seo');

echo $this->renderRow(
    $this->renderTextareaField('keywords', '各关键字以半角逗号“,”分隔，少于255个字符'),
    $this->renderTextareaField('description', '少于255个字符')
);

$widget->endTab(); //seoTab

$widget->beginTab('tags');

$tags = array();
foreach (TagType::getTagTypeTitles() as $name => $title) {
    $tags[] = $this->form->checkBox($this->model, "tags[{$name}]") . $title;
}

echo $this->renderRow(implode("\n", $tags));

$widget->endTab(); //tagsTab

$this->endWidget(); //contentBox

echo $this->renderSubmitRow();
