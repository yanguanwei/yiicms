<?php
class ThemeController extends AdminController
{
	public $navigationCurrentItemKey = 'system/theme';
	
	public function actionIndex()
	{
		$sql = "SEL"."ECT id, name, title FROM {{theme}} OR"."DER BY id ASC";
		$themes = Yii::app()->db->createCommand($sql)->queryAll();
		
		return $this->render('list', array(
			'themes' => $themes
		));
	}
	
	public function actionCreate()
	{
		$form = new ThemeForm('insert');
		
		if ( $_POST['ThemeForm'] ) {
			if ( $form->post($_POST['ThemeForm'], true) ) {
				$this->setFlashMessage('success', '创建成功！点击<a href="'.$this->createUrl('create').'">继续创建</a>');
				return $this->redirect($this->createUrl('template/index', array('theme_id' => $form->id)));
			} else {
				$this->setFlashMessage('error', '创建失败！');
			}
		} else {
			$theme = new Theme();
			$theme->configs = ConfigType::getConfigDefaultValueForForm();
			$form->setAttributes($theme->getAttributes(), false);
		}
		
		return $this->render('//form_template', array(
				'model' => $form,
				'title' => '创建主题'	
			));
	}

	public function actionUpdate($id)
	{
		$form = new ThemeForm('update');
		
		if ( $_POST['ThemeForm'] ) {
			if ( $form->post($_POST['ThemeForm'], false) ) {
				$this->setFlashMessage('success', '更新成功！');
				return $this->redirect($this->createUrl('template/index', array('theme_id' => $form->id)));
			} else {
				$this->setFlashMessage('error', '更新失败！');
			}
		} else {
			$theme = Theme::model()->findByPk($id);
			if ( !$theme )
				throw new CHttpException(404);
			$theme->configs = ConfigType::filterConfigValueFromDbToForm($theme->configs);
			$form->setAttributes($theme->getAttributes(), false);
		}
	
		return $this->render('//form_template', array(
				'model' => $form,
				'title' => '更新主题'
		));
	}
	
	public function actionStyle($id)
	{
		$id = intval($id);
		$form = new ThemeStyleForm();
		
		if ( $_POST['ThemeStyleForm'] ) {
			if ( $form->post($_POST['ThemeStyleForm']) ) {
				$this->setFlashMessage('success', '样式更新成功！');
			} else {
				$this->setFlashMessage('error', '样式更新失败！');
			}
		} else {
			$form->id = $id;
			$form->css = Theme::getThemeStyle($id);
		}
		
		return $this->render('//form_template', array(
				'model' => $form,
				'view' => 'style'	
			));
		
	}
	
	public function actionScript($id)
	{
		$id = intval($id);
		$form = new ThemeScriptForm();
		
		if ( $_POST['ThemeScriptForm'] ) {
			if ( $form->post($_POST['ThemeScriptForm']) ) {
				$this->setFlashMessage('success', '脚本更新成功！');
			} else {
				$this->setFlashMessage('error', '脚本更新失败！');
			}
		} else {
			$form->id = $id;
			$form->js = Theme::getThemeScript($id);
		}
		
		return $this->render('//form_template', array(
				'model' => $form,
				'view' => 'script'	
			));
		
	}
	
	public function actionDelete($id)
	{
		$id = intval($id);
		
		if ( $id ) {
			$count = 0;
			$model = Theme::model()->findByPk($id);
				
			if ( $model ) {
				$transaction = Yii::app()->db->beginTransaction();
				try {
					
				//删除主题删除下的所有文件
				$fileCount = 0;
				$basePath = Theme::getThemeBasePath($model->name);
				if ( is_dir($basePath) )
					$this->deldir($basePath, $fileCount);
				
				//删除入口文件
				$entryfile = Theme::getEntryFile($model->entry);
				if ( is_file($entryfile) )
					unlink($entryfile);

				//删除模板记录
				$templateCount = ThemeTemplate::deleteTemplateByThemeId($model->id);
				
				//删除栏目
				list($channelCount, $channelAliasCount, $modelsCounts) = Channel::deleteChannelByThemeId($model->id);
				
				//删除导航
				$navCount = Nav::deleteNavByThemeId($model->id);
				
				$count = $model->delete();
				
				$transaction->commit();
				
				$message = "删除成功：共删除{$count}个主题，{$templateCount}个模板，{$fileCount}个文件，{$navCount}条导航记录，{$channelCount}个栏目，{$channelAliasCount}个栏目别名";
				
				foreach ($modelsCounts as $title => $c)
					$message .= "，{$c}条{$title}记录";
				
				$this->setFlashMessage('success', $message);
				
				} catch (Exception $e) {
					$transaction->rollback();
					$this->addError(null, $e->getMessage());
				}
			} else {
				$this->setFlashMessage('information', "该主题不存在或已经被删除！");
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
				'label' => $_GET['id'] ? '创建主题模板' : '创建全局模板',
				'url' => $this->createUrl('template/create', array('theme_id' => intval($_GET['id']))),
				'class' => 'popuplayer iframe',
				'pupuplayer' => '{"iframeWidth":900, "iframeHeight":510}'
			)
		);
		
		if ( $_GET['id'] ) {
			
			$themeName = Theme::getThemeName(intval($_GET['id']));
			if ( $themeName ) {
				Yii::import('ext.ckfinder.CKFinder');
				Yii::import('application.widgets.CKFinderThemeButton');
				CKFinder::registerAssets();
				CKFinderThemeButton::initCKFinderSession($themeName);
			}
			
			$buttons[] = array(
				'shortcut' => $this->asset('images/icons/tag.png'),
				'label' => '主题模板',
				'url' => $this->createUrl('template/index', array('theme_id' => intval($_GET['id'])))
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
	
	protected function deldir($dir, &$count = 0) 
	{
		$dh = opendir($dir);	//先删除目录下的文件：
		while ( false !== ($file=readdir($dh)) ) {
			if( $file != "." && $file != ".." ) {
				$fullpath = $dir."/".$file;
				if(!is_dir($fullpath)) {
					unlink($fullpath);
					$count++;
				} else {
					$this->deldir($fullpath, $count);
				}
			}
		}
		closedir($dh);	
		if( rmdir($dir) ) {	//删除当前文件夹：
			return true;
		} else {
			return false;
		}
	}
}
?>