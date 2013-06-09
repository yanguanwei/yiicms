<?php

abstract class ArchiveAdminController extends ChannelModelBaseController
{
    protected function prepareListSQL(SelectSQL $sql)
    {
        $sql->where('base.model_name=?', $this->getChannelModel()->name);

        parent::prepareListSQL($sql);

        if (isset($_GET['status']) && $_GET['status']!=='') {
            $sql->where('base.status=?', intval($_GET['status']));
        }

        $sql->order('base.is_top DESC, base.update_time DESC, base.id DESC');
    }

    protected function getListFilters()
    {
        $status = isset($_GET['status']) ? $_GET['status'] : '';
        $filters['status'] = CHtml::dropDownList('status', $status, Archive::fetchArchiveStatusOptions(), array('empty' => '状态'));

        $filters = array_merge($filters, parent::getListFilters());

        return $filters;
    }

    protected function getListTemplate()
    {
        return '//archive_list';
    }

    protected function onFormCreate($cid, $form)
    {
        $form->update_time = date('Y-m-d H:i', time());
    }

    protected function onFormSubmitError($form)
    {
        $form->update_time = date('Y-m-d H:i', $form->update_time);
    }

    protected function deleteModel(array $ids)
    {
        parent::deleteModel($ids);

        $criteria = new CDbCriteria();
        $criteria->addInCondition('id', $ids);

        return Archive::model()->deleteAll($criteria);
    }

    public function actionDing($disabled = 0)
    {
        if (isset($_POST['id'])) {
            $criteria = new CDbCriteria();
            $criteria->addInCondition('id', $_POST['id']);
            $count = Archive::model()->updateAll(array('is_top' => $disabled ? 0 : 1), $criteria);
            $this->setFlashMessage('success', sprintf("成功%s置顶%s条记录！", $disabled ? '取消' : '', $count));
        }

        $url = Yii::app()->getRequest()->getUrlReferrer();
        if ($url) {
            $this->redirect($url);
        }
    }

    public function actionHighlight($disabled = 0)
    {
        if (isset($_POST['id'])) {
            $criteria = new CDbCriteria();
            $criteria->addInCondition('id', $_POST['id']);
            $count = Archive::model()->updateAll(array('is_highlight' => $disabled ? 0 : 1), $criteria);
            $this->setFlashMessage('success', sprintf("成功%s高亮%s条记录！", $disabled ? '取消' : '', $count));
        }

        $url = Yii::app()->getRequest()->getUrlReferrer();
        if ($url) {
            $this->redirect($url);
        }
    }

    public function actionChange($status)
    {
        if (isset($_POST['id'])) {
            $statuses = Archive::fetchArchiveStatusOptions();
            if (in_array($status, array_keys($statuses))) {
                $criteria = new CDbCriteria();
                $criteria->addInCondition('id', $_POST['id']);
                $count = Archive::model()->updateAll(array('status' => $status), $criteria);
                $this->setFlashMessage('success', sprintf("成功更改%s条记录为%s！", $count, $statuses[$status]));
            }
        }

        $url = Yii::app()->getRequest()->getUrlReferrer();
        if ($url) {
            $this->redirect($url);
        }
    }

    public function getShortcuts()
    {
        $shortcuts = parent::getShortcuts();

        if ($this->cid) {
            $attach = $this->getChannel()->getChannelAttach();
            if ($attach) {
                $shortcuts[] = array(
                    'shortcut' => $this->getChannelModelIcon($attach->name, 'create'),
                    'label' => $attach->title,
                    'url' => $this->createUrl($attach->controller.'Attach' . '/index', array('cid' => $this->cid)),
                    'class' => 'popuplayer iframe',
                    'popuplayer' => '{"iframeWidth":900, "iframeHeight":510}'
                );
            }
        }

        return $shortcuts;
    }

    public function getBulkActions()
    {
        return array(
            '批量发布' => $this->createUrl('change', array('status' => Archive::STATUS_PUBLISHED)),
            '批量未发布' => $this->createUrl('change', array('status' => Archive::STATUS_DRAFT)),
            '批量删除' => $this->createUrl('delete'),
            '批量置顶' => $this->createUrl('ding'),
            '取消置顶' => $this->createUrl('ding', array('disabled' => 1)),
            '批量高亮' => $this->createUrl('highlight'),
            '取消高亮' => $this->createUrl('highlight', array('disabled' => 1))
        );
    }
}
