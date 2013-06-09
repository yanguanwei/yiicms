<?php

Yii::import('admin.controllers.ArchiveAdminController');

class PromotionController extends ArchiveAdminController
{

    protected function onFormUpdate($id, $form)
    {
        $model = Promotion::model()->with('archive')->findByPk($id);
        if (!$model) {
            $this->setFlashMessage('error', "没有找到ID为{$id}的记录！");
        }

        $form->setAttributes($model->getAttributes(), false);
        $form->setAttributes($model->archive->getAttributes(), false);
    }

    protected function deleteModel(array $ids)
    {
        parent::deleteModel($ids);

        $criteria = new CDbCriteria();
        $criteria->addInCondition('id', $ids);

        return self::model()->deleteAll($criteria);
    }

    protected function prepareListSQLForTagsFilter(SelectSQL $sql)
    {
        $hasFilter = false;
        foreach (array('promotion_type', 'location', 'promotion_category') as $key) {
            if (isset($_GET[$key]) && $_GET[$key]) {
                $hasFilter = true;
                $sql->where("p.{$key}=?", $_GET[$key]);
            }
        }
        if ($hasFilter) {
            $sql->leftJoin(array('{{promotion}}', 'p'), null, 'p.id=base.id');
        }
    }

    /**
     * @return ChannelModel
     */
    protected function getChannelModel()
    {
        return ChannelModel::findModel('promotion');
    }

    /**
     * @param $scenario
     * @param $idOrCid
     * @return CModel
     */
    protected function createFormModel($scenario, $idOrCid)
    {
        return new PromotionForm($scenario);
    }
}
