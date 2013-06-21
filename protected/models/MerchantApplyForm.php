<?php

class MerchantApplyForm extends CFormModel
{
    public $title;
    public $phone;
    public $content;
    public $verifyCode;

    public function attributeLabels()
    {
        return array(
            'title' => '商家全称',
            'address' => '商铺地址',
            'phone' => '联系方式',
            'content' => '商家简介',
        );
    }

    public function rules()
    {
        return array(
            array('title, address, phone, content', 'required'),
            array('title', 'length', 'max' => 20),
            array('phone', 'length', 'max' => 20),
            array('verifyCode', 'captcha', 'allowEmpty'=> !extension_loaded('gd'))
        );
    }

    public function save()
    {
        try {
            $archive = new Archive();
            $archive->cid = 3;
            $archive->model_name = 'merchant';
            $archive->status = Archive::STATUS_DRAFT;
            $archive->title = trim($this->title);
            if ($archive->save()) {
                $merchant = new Merchant();
                $merchant->id = $archive->id;
                $merchant->phone = $this->phone;
                $merchant->content = $this->content;
                if ($merchant->save()) {
                } else {
                    $this->addErrors($merchant->getErrors());
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