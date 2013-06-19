<?php
class MerchantForm extends ArchiveForm
{
    public $id;
    public $phone;
    public $address;
    public $content;

    public function rules()
    {
        $rules = parent::rules();

        return $rules;
    }

    public function attributeLabels()
    {
        return parent::attributeLabels() + array(
            'phone' => '联系电话',
            'address' => '商家地址',
            'content' => '商家简介'
        );
    }

    protected function doSave()
    {
        parent::doSave();

        if ($this->getScenario()=='insert') {
            $news = new Merchant();
        } else {
            $news = Merchant::model()->findByPk($this->id);
        }

        $news->setAttributes($this->getAttributes(), false);

        if (!$news->save()) {
            $this->addErrors($news->getErrors());
            throw new CException();
        }
    }

    protected function getChannelModel()
    {
        return ChannelModel::findModel('merchant');
    }
}
