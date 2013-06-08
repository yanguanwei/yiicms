<?php
/**
 * 信息表
 *
 * @author yanguanwei@qq.com
 */
class ChannelModel extends CActiveRecord
{
    public $name;
    public $title;
    public $table_name;
    public $controller;

    private static $channelModels = array();

    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return CActiveRecord the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return '{{channel_model}}';
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
                'message'=>'模型名必须为字母、数字、下划线'
            ),
            array('name, title, table_name, controller', 'required')
        );
    }

    protected function beforeSave()
    {
        if ($this->getIsNewRecord()) {
            if ($this->exists('name=:name', array(':name' => $this->name))) {
                $this->addError('alias', '已经存在的模型！');

                return false;
            }
        }

        return parent::beforeSave();
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'name' => '模型名',
            'title' => '显示名称',
            'table_name' => '数据库表名',
            'controller' => '控制器名称'
        );
    }

    public static function fetchChannelModelSelectOptions()
    {
        $sql = "SELECT name, title FROM {{channel_model}} ORDER BY name ASC";
        $options = array();
        foreach (Yii::app()->db->createCommand($sql)->queryAll() as $row) {
            $options[$row['name']] = $row['title'];
        }
        return $options;
    }

    public static function getTableName($name)
    {
        $sql = "SELECT table_name FROM {{channel_model}} WHERE name='{$name}'";
        if (false !== $row = Yii::app()->db->createCommand($sql)->queryRow()) {
            return $row['table_name'];
        }
    }

    /**
     * @param $name
     * @return ChannelModel
     */
    public static function findModel($name)
    {
        if (!isset(self::$channelModels[$name])) {
            self::$channelModels[$name] = self::model()->findByPk($name);
        }
        return self::$channelModels[$name];
    }
}
