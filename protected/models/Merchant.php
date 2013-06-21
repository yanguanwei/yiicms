<?php

class Merchant extends CActiveRecord
{
    public $id;
    public $phone;
    public $address;
    public $content;

    private $promotions;

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

    public function inPhone($phone)
    {
        $this->getDbCriteria()->compare('phone', $phone);

        return $this;
    }

    public function getTopPromotions()
    {
        if (null === $this->promotions) {
            if ($this->phone) {
                $this->promotions = Archive::model()
                    ->with(array('promotion' => array('select' => 'id, discounts, start_time, end_time', 'alias' => 'p')))
                    ->inChannels(5)
                    ->published()->recently(5)->findAll('p.phone=:phone', array(':phone' => $this->phone));
            } else {
                $this->promotions = array();
            }
        }
        return $this->promotions;
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
