<?php

class PromotionApplyForm extends CFormModel
{
    public $merchant;
    public $phone;
    public $address;
    public $description;

    public $title;
    public $location;
    public $promotion_type;
    public $promotion_category;
    public $content;
    public $discount;
    public $start_time;
    public $end_time;

    public $verifyCode;

    public function attributeLabels()
    {
        return array(
            'title' => '广告标题',
            'phone' => '联系方式',
            'content' => '活动详情',
            'location' => '区域选择',
            'promotion_type' => '促销类别',
            'promotion_category' => '促销分类'
        );
    }

    public function rules()
    {
        return array(
            array('title, phone, content, location, promotion_type, promotion_category, start_time, end_time', 'required'),
            array('title', 'length', 'max' => 20),
            array('phone', 'length', 'max' => 20),
            array('merchant', 'safe'),
            array('verifyCode', 'captcha', 'allowEmpty'=> !extension_loaded('gd'))
        );
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        $file = CUploadedFile::getInstanceByName('cover');
        $filepath = Yii::getPathOfAlias('wwwroot') . '/uploads/images/apply';

        try {
            $archive = new Archive();
            $archive->cid = 5;
            $archive->model_name = 'promotion';
            $archive->status = Archive::STATUS_DRAFT;
            $archive->title = trim($this->title);
            if ($archive->save()) {
                $promotion = new Promotion();
                $promotion->id = $archive->id;
                $promotion->phone = $this->phone;
                $promotion->content = $this->content;
                $promotion->start_time = strtotime($this->start_time);
                $promotion->end_time = strtotime($this->end_time);
                $promotion->discounts = $this->discount;
                $tags = array(
                    'location' => $this->location,
                    'promotion_type' => $this->promotion_type,
                    'promotion_category' => $this->promotion_category
                );
                ModelTag::update('promotion', $promotion->id, $tags);
                if ($promotion->save()) {
                } else {
                    $this->addErrors($promotion->getErrors());
                    return false;
                }
            } else {
                $this->addErrors($archive->getErrors());
                return false;
            }
        } catch (Exception $e) {
            $this->addError(null, $e->getMessage());
            return false;
        }
        return true;
    }
}