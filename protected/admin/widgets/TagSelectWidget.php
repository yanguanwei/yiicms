<?php

class TagSelectWidget extends CWidget
{
    public $types = array();
    public $prefixName;

    public function run()
    {
        $selects = array();
        $tagTypes = TagType::getTagTypeTitles($this->types);
        $tagOptions = Tag::getTagOptions($this->types);
        foreach ($tagTypes as $name => $typeTitle) {
            $options = isset($tagOptions[$name]) ? $tagOptions[$name] : array();
            $attribute = $this->prefixName === null ? $name : "{$this->prefixName}[$name]";
            $selects[] = $this->form->dropDownList(
                $this->model,
                $attribute,
                $options,
                array('empty' => '--' . $tagTypes[$name] . '--')
            );
        }
    }
}