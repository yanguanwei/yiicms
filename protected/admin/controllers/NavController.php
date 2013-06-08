<?php
class NavController extends AdminController
{
    public $navigationCurrentItemKey = 'system/nav';

    public function actionIndex($theme_id = 0, $type_id = 0)
    {
        $theme_id = intval($theme_id);

        if (!$theme_id) {
            $row = Yii::app()->db->createCommand("SELECT id FROM {{theme}} ORDER BY id ASC LIMIT 1")->queryRow();
            if ($row) {
                $theme_id = intval($row['id']);
            }

            if ($theme_id) {
                $_GET['theme_id'] = $theme_id;
            }
        }

        $_GET['type_id'] = $type_id;

        $conn = Yii::app()->db;
        $command = $conn->createCommand(
            "SELECT * FROM {{nav}} WHERE theme_id='{$theme_id}' AND type_id='{$type_id}' ORDER BY sort_id DESC, id ASC"
        );
        $data = $command->queryAll();

        foreach ($data as &$row) {
            $row['enabled'] = $this->getSelected($row['enabled']);
        }

        $this->render(
            'index',
            array(
                'data' => $data,
                'title' => Theme::findThemeTitle($theme_id),
                'theme_id' => $theme_id,
                'type_id' => $type_id,
                'types' => Nav::fetchNavTypeSelectOptions()
            )
        );
    }

    protected function getSelected($value)
    {
        return $value ? '<span style="color:#090">√</span>' : '<span style="color:#900">×</span>';
    }

    public function actionCreate($theme_id, $type_id, $parent_id = 0)
    {
        $nav = new Nav();

        if ($_POST['Nav']) {
            $nav->setAttributes($_POST['Nav']);
            $nav->id = null;

            if ($nav->save()) {
                $this->setFlashMessage(
                    'success',
                    '创建导航成功！点击<a href="' . $this->createUrl(
                        'create',
                        array('theme_id' => $nav->theme_id, 'type_id' => $nav->type_id, 'parent_id' => $parent_id)
                    ) . '">继续创建</a>'
                );
                $this->redirect(
                    $this->createUrl('index', array('theme_id' => $nav->theme_id, 'type_id' => $nav->type_id))
                );
            } else {
                $this->setFlashMessage('error', "创建导航失败！");
            }
        } else {
            $nav->theme_id = intval($theme_id);
            $nav->type_id = intval($type_id);
            $nav->parent_id = intval($parent_id);
        }

        return $this->render(
            '//form_template',
            array(
                'form' => $nav,
                'title' => '创建导航'
            )
        );
    }

    public function actionUpdate($id)
    {
        $nav = Nav::model()->findByPk($id);
        if (!$nav) {
            throw new CHttpException(404);
        }

        if ($_POST['Nav']) {
            $nav->setAttributes($_POST['Nav']);
            if ($nav->save()) {
                $this->setFlashMessage('success', '更新导航成功！');
                $this->redirect(
                    $this->createUrl('index', array('theme_id' => $nav->theme_id, 'type_id' => $nav->type_id))
                );
            } else {
                $this->setFlashMessage('error', "更新导航失败！");
            }
        }

        $_GET['theme_id'] = $nav->theme_id;
        $_GET['type_id'] = $nav->type_id;

        return $this->render(
            '//form_template',
            array(
                'form' => $nav,
                'title' => '编辑导航'
            )
        );
    }

    public function actionDelete()
    {
        if (isset($_POST['id'])) {
            $id = $_POST['id'];
        } else {
            $id = array(intval($_GET['id']));
        }

        $count = 0;
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id', $id);

        $count = Nav::model()->deleteAll($criteria);

        if (Yii::app()->request->isAjaxRequest) {
            echo 'ok';
        } else {
            if ($count) {
                $this->setFlashMessage('success', "删除成功：共删除 {$count} 条记录！");
            } else {
                $this->setFlashMessage('information', "该记录不存在或已经被删除！");
            }

            $url = Yii::app()->getRequest()->getUrlReferrer();
            if ($url) {
                $this->redirect($url);
            }
        }
    }

    public function actionUpdateSort()
    {
        return $this->doUpdateSort('nav');
    }

    public function getShortcuts()
    {
        $buttons = array();

        foreach (Theme::fetchThemeSelectOptions() as $id => $title) {
            $buttons[] = array(
                'shortcut' => $this->asset('images/icons/e.png'),
                'label' => $title,
                'url' => $this->createUrl('nav/index', array('theme_id' => $id))
            );
        }

        if ($_GET['theme_id']) {
            $buttons[] = array(
                'shortcut' => $this->asset('images/icons/add.png'),
                'label' => '创建导航',
                'url' => $this->createUrl(
                    'nav/create',
                    array('theme_id' => $_GET['theme_id'], 'type_id' => $_GET['type_id'])
                )
            );
        }

        return $buttons;
    }
}
