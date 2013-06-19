<?php

class Promotion extends CActiveRecord
{
    public $id;
    public $phone;
    public $discounts;
    public $start_time;
    public $end_time;
    public $content;

    private $locationTitle;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return Promotion the static model class
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
        return '{{promotion}}';
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

    protected function beforeSave()
    {
        if (!$this->id) {
            $this->addError('id', '插入或更新需要指定ID');

            return false;
        }

        if ($this->start_time && (
            strpos($this->start_time, ':') > 0 ||
            strpos($this->start_time, '-') > 0 ||
            strpos($this->start_time, '/') > 0 ||
            strpos($this->start_time, ' ') > 0
          )
        ) {
            $this->start_time = strtotime($this->start_time);
        }

        if ($this->end_time && (
            strpos($this->end_time, ':') > 0 ||
            strpos($this->end_time, '-') > 0 ||
            strpos($this->end_time, '/') > 0 ||
            strpos($this->end_time, ' ') > 0
          )
        ) {
            $this->end_time = strtotime($this->end_time);
        }

        if (!$this->start_time) {
            $this->start_time = time();
        }

        if (!$this->end_time) {
            $this->end_time = time();
        }

        return parent::beforeSave();
    }

    protected function afterFind()
    {
        $this->start_time = date('Y-m-d', $this->start_time);
        $this->end_time = date('Y-m-d', $this->end_time);

        return parent::afterFind();
    }

    public function getLocationTitle()
    {
        if (null === $this->locationTitle) {
            if ($this->id) {
                $tid = ModelTag::findByType('promotion', $this->id, 'location');
                if ($tid) {
                    $this->locationTitle = Tag::fetchTitle($tid);
                } else {
                    $this->locationTitle = '';
                }
            } else {
                $this->locationTitle = '';
            }
        }

        return $this->locationTitle;
    }
}
