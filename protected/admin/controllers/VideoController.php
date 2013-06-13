<?php

Yii::import('admin.controllers.ArchiveAdminController');

class VideoController extends ArchiveAdminController
{
    /**
     * @param $scenario
     * @param $idOrCid
     * @return ArchiveForm
     */
    protected function createFormModel($scenario, $idOrCid)
    {
        return new VideoForm($scenario);
    }

    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
                'cover' => '视频地址'
            ));
    }

    /**
     * @return ChannelModel
     */
    protected function getChannelModel()
    {
        return ChannelModel::findModel('video');
    }
}


