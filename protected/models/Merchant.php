<?php

class Merchant extends CActiveRecord
{
    public $id;
    public $phone;
    public $address;
    public $content;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return Merchant the static model class
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
        return '{{merchant}}';
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
            array('id', 'required')
        );
    }

    public function relations()
    {
        return array(
            'archive' => array(self::BELONGS_TO, 'Archive', 'id')
        );
    }

    public function getTopPromotions()
    {
        if ($this->phone) {
            Archive::model()
                ->with(array('promotion' => array('select' => 'discounts, start_time, end_time', 'alias' => 'p')))
                ->inChannels(5)
                ->published()->recently(5)->findAll();
        }
    }

    protected function beforeSave()
    {
        if (!$this->id) {
            $this->addError('id', '插入或更新需要指定ID');

            return false;
        }

        return parent::beforeSave();
    }
}
