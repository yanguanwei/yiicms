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

    protected function onFormUpdate($id, $form)
    {
        $archive = Archive::model()->findByPk($id);
        if (!$archive) {
            $this->setFlashMessage('error', "没有找到ID为{$id}的记录！");
        }
        $form->setAttributes($archive->getAttributes(), false);
        $form->tags = ModelTag::find($this->getChannelModel()->name, $id);
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

    public function getFormCellLabels()
    {
        return array(
            'id' => 'ID',
            'title' => '标题',
            'status' => '状态',
            'update_time' => '更新时间',
            'operate' => '操作'
        );
    }

    public function getFormCell(ListTable $table)
    {
        return array(
            'id' => array(),
            'title' => array(
                array(
                    'type' => 'link',
                    'typeOptions' => array(
                        'url' => $this->createUrl('update', array('id' => $table->data['id']))
                    )
                ),
                array(
                    'highlight' => $table->data['is_highlight'],
                    'top' => $table->data['is_top'],
                    'id' => $table->data['id'],
                    'cover' => $table->data['cover'] ? 1 : 0
                )
            ),
            'status' => array(
                Archive::fetchArchiveStatusOptions($table->data['status'])
            ),
            'update_time' => array(
                array('type' => 'dateTime')
            ),
            'operate' => array(
                $table->updateButton() . $table->deleteButton()
            )
        );
    }
}
