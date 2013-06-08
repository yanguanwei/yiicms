<?php
class NewsForm extends ArchiveForm
{
    public $source;
    public $content;

    public function rules()
    {
        $rules = parent::rules();

        return $rules;
    }

    public function attributeLabels()
    {
        return parent::attributeLabels() + array(
            'source' => '来源',
            'content' => '内容'
        );
    }

    protected function doSave()
    {
        parent::doSave();

        if ($this->getScenario() == 'insert') {
            $news = new News();
        } else {
            $news = News::model()->findByPk($this->id);
        }

        $news->setAttributes($this->getAttributes(), false);

        if (!$news->save()) {
            $this->addErrors($news->getErrors());
            throw new CException();
        }
    }

    protected function getChannelModel()
    {
        return ChannelModel::findModel('news');
    }
}
