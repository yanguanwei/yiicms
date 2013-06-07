<?php

Yii::import('admin.controllers.ArchiveAdminController');

class PictureController extends ArchiveAdminController
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
        return '图片';
    }

    protected function getShortcutsCreateIcon()
    {
        return $this->asset('images/icons/image_add_48.png');
    }

    protected function getShortcutsListIcon()
    {
        return $this->asset('images/icons/picture.png');
    }
}
