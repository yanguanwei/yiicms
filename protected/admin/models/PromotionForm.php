<?php
class PromotionForm extends ArchiveForm
{
    public $id;
    public $promotion_type;
    public $location;
    public $promotion_category;
    public $discounts;
    public $start_time;
    public $end_time;
    public $content;

    public function rules()
    {
        $rules = parent::rules();

        return $rules;
    }

    public function attributeLabels()
    {
        return parent::attributeLabels() + array(
            'start_time' => '起始时间',
            'end_time' => '截止时间',
            'content' => '活动详情'
        );
    }

    protected function doSave()
    {
        parent::doSave();

        if ($this->getScenario()=='insert') {
            $news = new Promotion();
        } else {
            $news = Promotion::model()->findByPk($this->id);
        }

        $news->setAttributes($this->getAttributes(), false);

        if (!$news->save()) {
            $this->addErrors($news->getErrors());
            throw new CException();
        }
    }

    protected function getChannelModel()
    {
        return ChannelModel::findModel('promotion');
    }
}
