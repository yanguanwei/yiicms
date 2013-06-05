<?php
class TemplateController extends AdminController
{
	public $navigationCurrentItemKey = 'system/theme';
	
	public function actionIndex($theme_id = 0)
	{
		$theme_id = intval($theme_id);
		
		if ( $theme_id ) {
			$title = Theme::getThemeTitle($theme_id);
			if ( !$title )
				throw new CHttpException(404);
		} else {
			$title = '全局模板';
			$this->layout = '//layouts/iframe';
		}
		
		$sql = "SELECT id, path FROM {{theme_template}} WHERE theme_id='{$theme_id}' ORDER BY path ASC, id ASC";
		$data = Yii::app()->db->createCommand($sql)->queryAll();
		
		return $this->render('list', array(
				'data' => $data,
				'title' => $title,
				'theme_id' => $theme_id
			));
	}
	
	public function actionCreate($theme_id = 0)
	{
		$form = new TemplateForm('insert');
		
		if ( $_POST['TemplateForm'] ) {
			if ( $form->post($_POST['TemplateForm'], true) ) {
				$this->setFlashMessage('success', '创建成功！点击<a href="'.$this->createUrl('create', array('theme_id' => $form->theme_id)).'">继续创建</a>');
				return $this->redirect($this->createUrl('update', array('id' => $form->id)));
			} else {
				$this->setFlashMessage('error', '创建失败！');
			}
		} else {
			$form->theme_id = $theme_id;
		}
		
		$this->layout = '//layouts/iframe';
		
		return $this->render('//form_template', array(
			'model' => $form,
			'title' => '创建模板'
		));
		
	}
	
	public function actionUpdate($id)
	{
		$form = new TemplateForm('update');
		
		if ( $_POST['TemplateForm'] ) {
			if ( $form->post($_POST['TemplateForm'], false) ) {
				$this->setFlashMessage('success', '更新成功！');
			} else {
				$this->setFlashMessage('error', '更新失败！');
			}
		} else {
			$template = ThemeTemplate::model()->findByPk($id);
			if ( !$template )
				throw new CHttpException(404, "找不到ID为{$id}的记录");
			$form->setAttributes($template->getAttributes(), false);
			$form->content = ThemeTemplate::getThemeTemplateContent($template->theme_id, $template->path);
		}
		
		$this->layout = '//layouts/iframe';
		return $this->render('//form_template', array(
			'model' => $form,
			'title' => '更新模板'
		));
	}
	
	public function actionDelete($id)
	{
		$id = intval($id);
		$theme_id = 0;
		
		if ( $id ) {
			$count = 0;
			
			$model = ThemeTemplate::model()->findByPk($id);
			
			if ( $model ) {
				$theme_id = $model->theme_id;
				//删除模板文件
				$file = ThemeTemplate::getThemeTemplateByThemeId($model->theme_id, $model->path);
				if ( is_file($file) )
					unlink($file);
				
				$count = $model->delete();
				$this->setFlashMessage('success', "删除成功：共删除{$count}个模板！");
			} else {
				$this->setFlashMessage('information', "该模板不存在或已经被删除！");
			}
		} else {
			$this->setFlashMessage('error', "ID非法！");
		}
		
		$url = Yii::app()->getRequest()->getUrlReferrer();
		if ( $url )
			$this->redirect($url);
	}
	
	public function getShortcuts()
	{
		$themeId = intval($_GET['theme_id']);
		
		$buttons = array(
			array(
				'shortcut' => $this->asset('images/icons/box_present.png'),
				'label' => '主题列表',
				'url' => $this->createUrl('theme/index')
			),
			array(
				'shortcut' => $this->asset('images/icons/box_content.png'),
				'label' => '创建主题',
				'url' => $this->createUrl('theme/create')
			),
			array(
				'shortcut' => $this->asset('images/icons/tag.png'),
				'label' => '全局模板',
				'url' => $this->createUrl('template/index', array('theme_id' => 0)),
				'class' => 'popuplayer iframe',
				'pupuplayer' => '{"iframeWidth":900, "iframeHeight":510}'
			),
			array(
				'shortcut' => $this->asset('images/icons/tag_add.png'),
				'label' => $themeId ? '创建主题模板' : '创建全局模板',
				'url' => $this->createUrl('template/create', array('theme_id' => $themeId)),
				'class' => 'popuplayer iframe',
				'pupuplayer' => '{"iframeWidth":900, "iframeHeight":580}'
			)
		);

		if ( $themeId ) {
			$themeName = Theme::getThemeName($themeId);
			if ( $themeName ) {
				Yii::import('ext.ckfinder.CKFinder');
				Yii::import('application.widgets.CKFinderThemeButton');
				CKFinder::registerAssets();
				CKFinderThemeButton::initCKFinderSession($themeName);
			}

			$buttons[] = array(
					'shortcut' => $this->asset('images/icons/tag.png'),
					'label' => '主题模板',
					'url' => $this->createUrl('template/index', array('theme_id' => $themeId))
			);
			
			$buttons[] = array(
					'shortcut' => $this->asset('images/icons/photo_album.png'),
					'label' => '资源',
					'url' => '#',
					'class' => 'CKFinderPopup'
			);
		}
		
		return $buttons;
	}
}
?>