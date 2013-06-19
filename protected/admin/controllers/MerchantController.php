<?php

Yii::import('admin.controllers.ArchiveAdminController');

class MerchantController extends ArchiveAdminController
{
    protected function prepareListSQL(SelectSQL $sql)
    {
        if (isset($_GET['phone']) && $_GET['phone']!=='') {
            $sql->where('merchant.phone=?', intval($_GET['phone']));
        }

        $sql->leftJoin(array('{{merchant}}', 'merchant'), 'phone', 'merchant.id=base.id');
    }

    protected function getListFilters()
    {
        $filters = parent::getListFilters();

        $filters['phone'] = CHtml::textField('phone', isset($_GET['phone']) ? $_GET['phone'] : '', array('class' => 'text-input', 'placeholder' => '联系电话'));

        return $filters;
    }

    protected function onFormUpdate($id, $form)
    {
        parent::onFormUpdate($id, $form);
        $model = Merchant::model()->findByPk($id);
        $form->setAttributes($model->getAttributes(), false);
    }

    protected function deleteModel(array $ids)
    {
        parent::deleteModel($ids);

        $criteria = new CDbCriteria();
        $criteria->addInCondition('id', $ids);

        return self::model()->deleteAll($criteria);
    }

    /**
     * @return ChannelModel
     */
    protected function getChannelModel()
    {
        return ChannelModel::findModel('merchant');
    }

    /**
     * @param $scenario
     * @param $idOrCid
     * @return CModel
     */
    protected function createFormModel($scenario, $idOrCid)
    {
        return new MerchantForm($scenario);
    }

    public function getFormCellLabels()
    {
        $labels = parent::getFormCellLabels();
        return array(
            'id' => $labels['id'],
            'title' => $labels['title'],
            'phone' => '联系电话',
            'status' => $labels['status'],
            'update_time' => $labels['update_time'],
            'operate' => $labels['operate']
        );
    }

    public function getFormCell(ListTable $table)
    {
        $cells = parent::getFormCell($table);
        return array(
            'id' => $cells['id'],
            'title' => $cells['title'],
            'phone' => array(),
            'status' => $cells['status'],
            'update_time' => $cells['update_time'],
            'operate' => $cells['operate']
        );
    }
}
