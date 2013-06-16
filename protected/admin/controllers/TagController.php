<?php

class TagController extends AdminController
{
    public $navigationCurrentItemKey = 'system/tag';

    public function actionIndex()
    {
        Yii::import('apps.ext.young.SelectSQL');
        Yii::import('apps.ext.young.SelectDataProvider');

        $sql = new SelectSQL();
        $sql->from('{{tag_type}}', '*');

        $dataProvider = new SelectDataProvider(Yii::app()->db, $sql);

        return $this->render('type_list', array(
            'title' => '标签分类列表',
            'dataProvider' => $dataProvider
        ));
    }

    public function actionTypeCreate()
    {
        $form = new TagType('insert');

        if ( isset($_POST['TagType']) ) {
            $form->setAttributes($_POST['TagType'], true);
            if ( $form->save()) {
                $this->setFlashMessage('success', '创建成功！点击<a href="'.$this->createUrl('typeCreate').'">继续创建</a>');
                $this->redirect($this->createUrl('index'));
            } else {
                $this->setFlashMessage('error', "创建失败！");
            }
        }

        $this->render('//form_template', array(
            'form' => $form,
            'title' => '创建标签类型',
            'view' => 'type_form'
        ));
    }

    public function actionTypeUpdate($name)
    {
        $form = TagType::model()->findByPk($name);

        if ( !$form )
            throw new CHttpException(404);

        if ( isset($_POST['TagType']) ) {
            $form->setAttributes($_POST['TagType'], true);
            if ( $form->save() ) {
                $this->setFlashMessage('success', '更新成功！');
                $this->redirect($this->createUrl('index'));
            } else {
                $this->setFlashMessage('error', "更新失败！");
            }
        }

        $this->render('//form_template', array(
            'form' => $form,
            'title' => '更新标签类型',
            'view' => 'type_form'
        ));
    }

    public function actionTypeDelete($name)
    {
        if (Tag::countByType($name)) {
            $this->setFlashMessage('error', "请先删除该类型下的标签！");
        } else {
            if (TagType::model()->deleteAll('name=:name', array(':name' => $name))) {
                $this->setFlashMessage('success', "删除成功！");
            } else {
                $this->setFlashMessage('information', "该记录不存在或已经被删除！");
            }
        }

        $url = Yii::app()->getRequest()->getUrlReferrer();
        if ( $url )
            $this->redirect($url);
    }

    public function actionList($type_name)
    {
        Yii::import('apps.ext.young.SelectSQL');
        Yii::import('apps.ext.young.SelectDataProvider');

        $sql = new SelectSQL();
        $sql->from('{{tag}}', '*')->where('type_name=?', $type_name);

        $dataProvider = new SelectDataProvider(Yii::app()->db, $sql);

        $this->layout = '/layouts/iframe';

        return $this->render('list', array(
            'title' => '标签列表',
            'dataProvider' => $dataProvider,
            'type_name' => $type_name
        ));
    }

    public function actionCreate($type_name)
    {
        $form = new Tag('insert');

        if ( isset($_POST['Tag']) ) {
            $form->setAttributes($_POST['Tag'], true);
            if ( $form->save()) {
                $this->setFlashMessage('success', '创建成功！点击<a href="'.$this->createUrl('create', array('type_name' => $form->type_name)).'">继续创建</a>');
                $this->redirect($this->createUrl('list', array('type_name' => $form->type_name)));
            } else {
                $this->setFlashMessage('error', "创建失败！");
            }
        } else {
            $form->type_name = $type_name;
        }

        $this->layout = '/layouts/iframe';

        $this->render('//form_template', array(
            'form' => $form,
            'title' => '创建标签'
        ));
    }

    public function actionUpdate($id)
    {
        $form = Tag::model()->findByPk($id);

        if ( !$form )
            throw new CHttpException(404);

        if ( isset($_POST['Tag']) ) {
            $form->setAttributes($_POST['Tag'], true);
            if ( $form->save() ) {
                $this->setFlashMessage('success', '更新成功！');
                $this->redirect($this->createUrl('list', array('type_name' => $form->type_name)));
            } else {
                $this->setFlashMessage('error', "更新失败！");
            }
        }

        $this->layout = '/layouts/iframe';

        $this->render('//form_template', array(
            'form' => $form,
            'title' => '更新标签'
        ));
    }

    public function actionDelete()
    {
        $id = intval($_GET['id']);
        $tag = Tag::model()->findByPk($id);

        if ( !$tag )
            throw new CHttpException(404);

        $count = $tag->delete();
        ModelTag::deleteByTag($id);

        $this->setFlashMessage(success, "共删除 {$count} 条标签！");

        $url = Yii::app()->getRequest()->getUrlReferrer();
        if ( $url )
            $this->redirect($url);
    }
}