<?php

class PictureForm extends ArchiveForm
{
    protected function getChannelModel()
    {
        return ChannelModel::findModel('picture');
    }
}