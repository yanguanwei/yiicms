<?php

class VideoForm extends ArchiveForm
{
    protected function getChannelModel()
    {
        return ChannelModel::findModel('video');
    }
}