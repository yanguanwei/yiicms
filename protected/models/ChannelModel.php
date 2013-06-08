<?php
/**
 * 信息表
 *
 * @author yanguanwei@qq.com
 */
class ChannelModel extends CActiveRecord
{
    public $id;
    public $title;
    public $table_name;
    public $alias;

    private static $channelModels = array();

    /**
     * Returns the static model of the specified AR class.
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
        return 'id';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('title, table_name, alias', 'required'),
            array('id', 'required', 'on' => 'update')
        );
    }

    protected function beforeSave()
    {
        if ($this->getIsNewRecord()) {
            if ($this->exists('alias=:alias', array(':alias' => $this->alias))) {
                $this->addError('alias', '已经存在的控制器名称！');

                return false;
            }
        } else {
            if ($this->exists('id<>:id AND alias=:alias', array(':id' => $this->id, ':alias' => $this->alias))) {
                $this->addError('alias', '已经存在的控制器名称！');

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
            'id' => 'ID',
            'title' => '模型名称',
            'table_name' => '数据库表名',
            'alias' => '控制器名称'
        );
    }

    public static function getChannelModelSelectOptions()
    {
        $sql = "SELECT id, title FROM {{channel_model}} ORDER BY id ASC";
        $options = array();
        foreach (Yii::app()->db->createCommand($sql)->queryAll() as $row) {
            $options[$row['id']] = $row['title'];
        }

        return $options;
    }

    public static function getTableName($id)
    {
        $sql = "SELECT table_name FROM {{channel_model}} WHERE id='{$id}'";
        if (false !== $row = Yii::app()->db->createCommand($sql)->queryRow()) {
            return $row['table_name'];
        }
    }

    /**
     * @param $id
     * @return ChannelModel
     */
    public static function findModel($id)
    {
        if (!isset(self::$channelModels[$id])) {
            self::$channelModels[$id] = self::model()->findByPk($id);
        }
        return self::$channelModels[$id];
    }
}
