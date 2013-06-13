<?php

Yii::import('admin.controllers.ArchiveAdminController');

class NewsController extends ArchiveAdminController
{
    protected function createFormModel($scenario, $idOrCid)
    {
        return new NewsForm($scenario);
    }

    protected function onFormUpdate($id, $form)
    {
        parent::onFormUpdate($id, $form);
        $news = News::model()->findByPk($id);
        $form->setAttributes($news->getAttributes(), false);
    }

    protected function deleteModel(array $ids)
    {
        parent::deleteModel($ids);

        $criteria = new CDbCriteria();
        $criteria->addInCondition('id', $ids);

        return News::model()->deleteAll($criteria);
    }

    /**
     * @return ChannelModel
     */
    protected function getChannelModel()
    {
        return ChannelModel::findModel('news');
    }
}
