<?php

Yii::import('admin.controllers.ArchiveAdminController');

class VideoController extends ArchiveAdminController
{
    /**
     * @param $scenario
     * @return ArchiveForm
     */
    protected function getFormModel($scenario)
    {
        return new ArchiveForm($scenario);
    }

    protected function getModelLabel()
    {
        return '视频';
    }

    protected function getShortcutsCreateIcon()
    {
        return $this->asset('images/icons/video.png');
    }

    protected function getShortcutsListIcon()
    {
        $this->asset('images/icons/video_list.png');
    }
}


