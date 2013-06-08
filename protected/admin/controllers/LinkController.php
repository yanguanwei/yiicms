<?php

Yii::import('admin.controllers.ChannelModelBaseController');

class LinkController extends ChannelModelBaseController
{
    protected function getChannelModel()
    {
        return ChannelModel::findModel(2);
    }

    protected function createFormModel($scenario, $idOrCid)
    {
        if ($scenario=='insert') {
            return new FriendLink($scenario);
        } else {
            return FriendLink::model()->findByPk($idOrCid);
        }
    }

    protected function prepareListSQL(SelectSQL $sql)
    {
        $sql->order('sort_id DESC, id ASC');
    }

    public function actionUpdateSort()
    {
        return $this->doUpdateSort('link');
    }

    protected function onFormCreate($cid, $form)
    {
        $form->url = 'http://';
    }

    protected function deleteModel(array $ids)
    {
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id', $ids);
        return FriendLink::model()->deleteAll($criteria);
    }
}
