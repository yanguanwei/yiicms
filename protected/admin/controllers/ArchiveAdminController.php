<?php

abstract class ArchiveAdminController extends AdminController
{
    /**
     * @param $scenario
     * @return ArchiveForm
     */
    protected function getFormModel($scenario)
    {
        return new ArchiveForm($scenario);
    }

    protected function onFormUpdate($id, $form)
    {
    }

    protected function onFormCreate($cid, $form)
    {
    }

    protected function getModelLabel()
    {
    }

    protected function onPrevDelete($id)
    {
    }

    protected function onListFilter(SelectSQL $sql)
    {
    }

    protected function getListFilters($cid)
    {
        $tagTypeNames = Channel::getChannelTags($cid);
        if ($tagTypeNames) {
            $selects = array();
            $tagTypes = TagType::getTagTypeTitles($tagTypeNames);
            $tagOptions = Tag::getTagOptions($tagTypeNames);
            foreach ($tagTypes as $name => $typeTitle) {
                $options = isset($tagOptions[$name]) ? $tagOptions[$name] : array();
                $selects[$name] = CHtml::dropDownList($name, isset($_GET[$name]) ? $_GET[$name] : '', $options, array('empty' => '--' . $tagTypes[$name] . '--'));
            }
            return $selects;
        }
        return array();
    }

    protected function getShortcutsCreateIcon()
    {
        return $this->asset('images/icons/edit.png');
    }

    protected function getShortcutsListIcon()
    {
        return $this->asset('images/icons/paper_content_pencil_48.png');
    }

    protected function getListTemplate()
    {
        return '//archive_list';
    }

    public function actionIndex($cid)
    {
        $cid = intval($cid);

        $title = Channel::getChannelTitle($cid);

        if (!$title) {
            throw new CHttpException(404);
        }

        $subs = Channel::getSubChannelTitles($cid);

        $ids = array_keys($subs);
        $ids[] = $cid;

        Yii::import('apps.ext.young.SelectSQL');
        Yii::import('apps.ext.young.SelectDataProvider');

        $sql = new SelectSQL();
        $sql->from(array('{{archive}}', 'a'), '*')
            ->in('cid', $ids);

        $this->onListFilter($sql);

        $sql->order('a.is_top DESC, a.update_time DESC, a.id DESC');

        $dataProvider = new SelectDataProvider(Yii::app()->db, $sql);

        $this->render(
            $this->getListTemplate(),
            array(
                'dataProvider' => $dataProvider,
                'channel_id' => $cid,
                'channel_topid' => $cid,
                'channels' => $subs,
                'title' => $title,
                'filters' => $this->getListFilters($cid)
            )
        );
    }

    public function actionList($cid)
    {
        $cid = intval($cid);

        $channel = Channel::model()->findByPk($cid);
        if (!$channel) {
            throw new CHttpException(404);
        }

        //获取一级栏目ID
        $topid = Channel::getTopChannelId($cid);
        if ($topid == $cid) {
            return $this->redirect(array('index', 'cid' => $topid));
        }

        $this->navigationCurrentItemKey = 'theme_' . Channel::getThemeId($topid) . '/' . $topid;


        $subs = Channel::getSubChannelTitles($channel->parent_id);

        Yii::import('apps.ext.young.SelectSQL');
        Yii::import('apps.ext.young.SelectDataProvider');

        $sql = new SelectSQL();
        $sql->from(array('{{archive}}', 'a'), '*')
            ->where('cid=?', $cid);

        $this->onListFilter($sql);

        $sql->order('a.is_top DESC, a.update_time DESC, a.id DESC');

        $dataProvider = new SelectDataProvider(Yii::app()->db, $sql);

        $this->render(
            $this->getListTemplate(),
            array(
                'dataProvider' => $dataProvider,
                'channel_id' => $cid,
                'channel_topid' => $topid,
                'channels' => $subs,
                'title' => Channel::getChannelTitle($topid),
                'filters' => $this->getListFilters($cid)
            )
        );
    }

    public function actionCreate($cid)
    {
        $form = $this->getFormModel('insert');

        if (isset($_POST[get_class($form)])) {

            if ($form->post($_POST[get_class($form)], true)) {
                $this->setFlashMessage(
                    'success',
                    '创建成功！点击<a href="' . $this->createUrl('create', array('cid' => $form->cid)) . '">继续创建</a>'
                );
                $this->redirect($this->createUrl('list', array('cid' => $form->cid)));
            } else {
                $this->setFlashMessage('error', "创建失败！");
            }
        } else {
            $form->cid = intval($cid);
            $this->onFormCreate($cid, $form);
        }

        $form->update_time = date('Y-m-d H:i', time());

        $topid = Channel::getTopChannelId($cid);
        $this->navigationCurrentItemKey = 'theme_' . Channel::getThemeId($topid) . '/' . $topid;

        $this->render(
            '//form_template',
            array(
                'model' => $form,
                'title' => '创建' . $this->getModelLabel()
            )
        );
    }

    public function actionUpdate($id)
    {
        $form = $this->getFormModel('update');

        if (isset($_POST[get_class($form)])) {

            if ($form->post($_POST[get_class($form)], false)) {
                $this->setFlashMessage('success', '更新成功！');
                $this->redirect($this->createUrl('list', array('cid' => $form->cid)));
            } else {
                $this->setFlashMessage('error', "更新失败！");
            }
        } else {
            $this->onFormUpdate($id, $form);
        }

        $topid = Channel::getTopChannelId($form->cid);
        $this->navigationCurrentItemKey = 'theme_' . Channel::getThemeId($topid) . '/' . $topid;

        $_GET['cid'] = $form->cid;

        $this->render(
            '//form_template',
            array(
                'model' => $form,
                'title' => '更新' . $this->getModelLabel()
            )
        );
    }

    public function actionDelete()
    {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
        } else {
            $id = intval($_GET['id']);
        }

        if (false !== $this->onPrevDelete($id)) {
            $count = Archive::deleteArchives($id);

            if ($count) {
                $this->setFlashMessage('success', "删除成功：共删除 {$count} 条新闻！");
            } else {
                $this->setFlashMessage('information', "该记录不存在或已经被删除！");
            }
        }

        $url = Yii::app()->getRequest()->getUrlReferrer();
        if ($url) {
            $this->redirect($url);
        }
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
        if ($_GET['cid']) {
            return array(
                array(
                    'shortcut' => $this->getShortcutsListIcon(),
                    'label' => Channel::getChannelTitle($_GET['cid']),
                    'url' => $this->createUrl('list', array('cid' => $_GET['cid']))
                ),
                array(
                    'shortcut' => $this->getShortcutsCreateIcon(),
                    'label' => '创建' . $this->getModelLabel(),
                    'url' => $this->createUrl('create', array('cid' => $_GET['cid']))
                )
            );
        }

        return array();
    }
}
