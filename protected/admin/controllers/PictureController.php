<?php

Yii::import('admin.controllers.ArchiveAdminController');

class PictureController extends ArchiveAdminController
{
    /**
     * @param $scenario
     * @param $idOrCid
     * @return ArchiveForm
     */
    protected function createFormModel($scenario, $idOrCid)
    {
        return new PictureForm($scenario);
    }

    protected function onFormUpdate($id, $form)
    {
        $archive = Archive::model()->findByPk($id);
        if ($archive) {
            $form->setAttributes($archive->getAttributes(), false);
            $form->tags = Archive::getTags($id);
        }
    }

    /**
     * @return ChannelModel
     */
    protected function getChannelModel()
    {
        return ChannelModel::findModel(3);
    }
}
