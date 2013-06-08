<?php

class TagType extends CActiveRecord
{
    public $name;
    public $title;

    /**
     * Returns the static model of the specified AR class.
     * @return CActiveRecord the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{tag_type}}';
    }

    public function primaryKey()
    {
        return 'name';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
          array('name', 'match',
            'pattern'=>'/^[a-zA-Z0-9_]{0,}$/',
            'message'=>'类型名必须为字母、数字、下划线'
          ),
            array('name, title', 'required')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'name' => '类型名',
            'title' => '显示名称'
        );
    }

    public static function getTagTypeTitles($names = null)
    {
        $sql = "SELECT name, title FROM {{tag_type}}";
        if ($names) {
            $names = (array) $names;
            $sql .= " WHERE name IN('" . implode("', '", $names) . "')";
        }

        $titles = array();
        foreach (Yii::app()->db->createCommand($sql)->queryAll() as $row) {
            $titles[$row['name']] = $row['title'];
        }
        return $titles;
    }
}