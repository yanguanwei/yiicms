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

    /**
     * @return ChannelModel
     */
    protected function getChannelModel()
    {
        return ChannelModel::findModel('picture');
    }
}
