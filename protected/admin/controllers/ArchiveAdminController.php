<?php

abstract class ArchiveAdminController extends ChannelModelBaseController
{
    protected function prepareListSQL(SelectSQL $sql)
    {
        $sql->where('model_id=?', $this->getChannelModel()->id);
        $sql->order('base.is_top DESC, base.update_time DESC, base.id DESC');
    }

    protected function getListFilters()
    {
        $filters = parent::getListFilters();

        $status = isset($_GET['status']) ? $_GET['status'] : '';
        $filters['status'] = CHtml::dropDownList('status', $status, Archive::getStatusOptions(), array('empty' => '状态'));

        $tagTypeNames = $this->getChannel()->tags;
        if ($tagTypeNames) {
            $tagTypes = TagType::getTagTypeTitles($tagTypeNames);
            $tagOptions = Tag::getTagOptions($tagTypeNames);
            foreach ($tagTypes as $name => $typeTitle) {
                $options = isset($tagOptions[$name]) ? $tagOptions[$name] : array();
                $filters[$name] = CHtml::dropDownList($name, isset($_GET[$name]) ? $_GET[$name] : '', $options, array('empty' => '--' . $tagTypes[$name] . '--'));
            }
        }

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

    public function deleteModel(array $ids)
    {
        return Archive::deleteArchives($ids);
    }

    public function actionDing($disabled = 0)
    {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
        } else {
            $id = intval($_GET['id']);
        }

        $count = Archive::dingArchives($id, $disabled);

        $this->setFlashMessage('success', sprintf("成功%s置顶%s条记录！", $disabled ? '取消' : '', $count));

        $url = Yii::app()->getRequest()->getUrlReferrer();
        if ($url) {
            $this->redirect($url);
        }
    }

    public function actionHighlight($disabled = 0)
    {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
        } else {
            $id = intval($_GET['id']);
        }

        $count = Archive::highlightArchives($id, $disabled);

        $this->setFlashMessage('success', sprintf("成功%s高亮%s条记录！", $disabled ? '取消' : '', $count));

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
                    'shortcut' => $this->getChannelModelIcon($attach->id, 'create'),
                    'label' => $attach->title,
                    'url' => $this->createUrl($attach->alias.'Attach' . '/index', array('cid' => $this->cid)),
                    'class' => 'popuplayer iframe',
                    'popuplayer' => '{"iframeWidth":900, "iframeHeight":510}'
                );
            }
        }

        return $shortcuts;
    }
}
