<?php

class MerchantApplyForm extends CFormModel
{
    public $title;
    public $phone;
    public $content;
    public $address;
    public $verifyCode;

    public function attributeLabels()
    {
        return array(
            'title' => '商家全称',
            'address' => '商铺网址',
            'phone' => '联系方式',
            'content' => '商家简介',
        );
    }

    public function rules()
    {
        return array(
            array('phone', 'required'),
            array('title, address, phone, content', 'safe'),
            array('title', 'length', 'max' => 20),
            array('phone', 'length', 'max' => 20),
            array('verifyCode', 'captcha', 'allowEmpty'=> !extension_loaded('gd'))
        );
    }

    public function save()
    {
        if (!$this->validate()) {
            return false;
        }

        try {
            $this->phone = trim($this->phone);
            if (empty($this->phone)) {
                $this->addError('phone', '联系方式不可为空！');
                return false;
            }

            $merchant = Merchant::model()->inPhone($this->phone)->find();

            if ($merchant) {
                return true;
            }

            $this->title = trim($this->title);
            if (empty($this->title)) {
                $this->addError('title', '商家名称不可为空！');
                return false;
            }

            $this->address = trim($this->address);
            if (empty($this->address)) {
                $this->addError('address', '商家网址不可为空！');
                return false;
            }

            $this->content = trim($this->content);
            if (empty($this->content)) {
                $this->addError('content', '商家简介不可为空！');
                return false;
            }

            foreach (array('title', 'address', 'phone', 'content') as $key) {
                $this->$key = htmlspecialchars($this->$key);
            }

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
                    return true;
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
    }
}