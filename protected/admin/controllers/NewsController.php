<?php

Yii::import('admin.controllers.ArchiveAdminController');

class NewsController extends ArchiveAdminController
{
    protected function getFormModel($scenario)
    {
        return new NewsForm($scenario);
    }

    protected function onFormUpdate($id, $form)
    {
        $news = News::model()->with('archive')->findByPk($id);
        if (!$news) {
            $this->setFlashMessage('error', "没有找到ID为{$id}的记录！");
        }

        $form->setAttributes($news->getAttributes(), false);
        $form->setAttributes($news->archive->getAttributes(), false);
        $form->tags = Archive::getTags($id);
    }

    protected function getModelLabel()
    {
        return '新闻';
    }

    protected function onPrevDelete($id)
    {
        News::deleteNews($id);
    }
}
