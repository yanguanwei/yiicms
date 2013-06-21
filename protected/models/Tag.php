<?php

class Tag extends CActiveRecord
{
    public $id;
    public $type_name;
    public $title;
    public $sort_id = 0;
    public $cover;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return Tag the static model class
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
        return '{{tag}}';
    }

    public function primaryKey()
    {
        return 'id';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('id', 'required', 'on' => 'update'),
            array('type_name, title', 'required'),
            array('sort_id, cover', 'safe')
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'ID',
            'title' => '标签名称',
            'cover' => '图片',
            'sort_id' => '排序'
        );
    }

    public function in($type)
    {
        $this->getDbCriteria()->compare('type_name', $type);

        return $this;
    }

    public function orderly($limit = -1)
    {
        $this->getDbCriteria()->mergeWith(
            array(
                'order' => 'sort_id DESC, id ASC',
                'limit' => $limit,
            )
        );

        return $this;
    }

    public function relations()
    {
        return array(
            'type' => array(self::BELONGS_TO, 'TagType', 'type_name')
        );
    }

    public static function fetchByTypes(array $types)
    {
        $sql = "SELECT id, title, type_name FROM {{tag}} WHERE type_name IN('" . implode("', '", $types) . "')";
        $titles = array();
        foreach (Yii::app()->db->createCommand($sql)->queryAll() as $row) {
            $titles[$row['type_name']][$row['id']] = $row['title'];
        }
        return $titles;
    }

    public static function fetchByType($type)
    {
        $sql = "SELECT id, title FROM {{tag}} WHERE type_name='{$type}')";
        $titles = array();
        foreach (Yii::app()->db->createCommand($sql)->queryAll() as $row) {
            $titles[$row['id']] = $row['title'];
        }
        return $titles;
    }

    public static function countByType($type_name)
    {
        $sql = "SELECT COUNT(id) FROM {{tag}} WHERE type_name=:type_name";
        $row = Yii::app()->db->createCommand($sql)->queryRow(false, array(':type_name' => $type_name));
        return intval($row[0]);
    }

    public static function getTagOptions($tagTypeNames)
    {
        $options = array();
        $command = Yii::app()->db->createCommand();
        $command->select('id, title, type_name')->from('{{tag}}');

        if (is_array($tagTypeNames)) {
            $command->where(array('in', 'type_name', $tagTypeNames));
            foreach ($command->queryAll() as $row) {
                $options[$row['type_name']][$row['id']] = $row['title'];
            }
        } else {
            $command->where('type_name=:type_name', array(':type_name' => $tagTypeNames));
            foreach ($command->queryAll() as $row) {
                $options[$row['id']] = $row['title'];
            }
        }
        return $options;
    }

    public static function fetchTitle($tid)
    {
        static $labels = array();
        $tid = intval($tid);
        if (!isset($labels[$tid])) {
            $labels[$tid] = Yii::app()->db->createCommand("SELECT title FROM {{tag}} WHERE id='{$tid}'")->queryScalar();
        }
        return $labels[$tid];
    }
}