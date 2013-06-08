<?php
class ConfigController extends AdminController
{
	public $navigationCurrentItemKey = 'system/config';
	
	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('ConfigType',array(
			'criteria'=>array(
				'order'=>'sort_id DESC, id ASC'
			)
		));
		
		$this->prompts = array(
			'此配置信息作用于每一个主题',
			'通过设置排序可调整主题信息配置页面每个配置项的顺序',
			'在模板中，可通过调用 $this->getConfig(key) 来获取配置值'
		);
		
		$this->render('list', array(
			'dataProvider' => $dataProvider
		));
	}
	
	public function actionCreate()
	{
		$model = new ConfigType();
		
		if ( $_POST['ConfigType'] ) {
			$model->setAttributes($_POST['ConfigType'], false);
			$model->id = null;
			
			if ( $model->save() ) {
				$this->setFlashMessage('success', '创建成功！点击<a href="'.$this->createUrl('create').'">继续创建</a>');
				return $this->redirect($this->createUrl('index'));
			} else {
				$this->setFlashMessage('error', '创建失败！');
			}
		}
		
		return $this->render('//form_template', array(
				'form' => $model,
				'title' => '创建配置'	
			));
	}
	
	public function actionUpdate($id)
	{
		$model = ConfigType::model()->findByPk($id);
		
		if ( !$model )
			throw new CHttpException(404);
		
		if ( $_POST['ConfigType'] ) {
			$model->setAttributes($_POST['ConfigType'], false);
				
			if ( $model->save() ) {
				$this->setFlashMessage('success', '更新成功！');
				return $this->redirect($this->createUrl('index'));
			} else {
				$this->setFlashMessage('error', '更新失败！');
					
			}
		}
		return $this->render('//form_template', array(
			'form' => $model,
			'title' => '更新配置'
		));
	}
	
	public function actionTheme($id)
	{
		$form = new ThemeConfigForm('update');
		
		if ( $_POST['ThemeConfigForm'] ) {
			$form->setAttributes($_POST['ThemeConfigForm'], false);
			if ( $form->validate() ) {
				Theme::updateThemeConfigs($form->id, ConfigType::filterConfigValueFromFormToDb($form->configs));
				$this->setFlashMessage('success', '更新成功！');
			}
		} else {
			$configs = Theme::getThemeConfigs($id);
			if ( !$configs )
				throw new CHttpException(404);
			$form->id = $id;
			$form->configs = ConfigType::filterConfigValueFromDbToForm($configs);
		}
		
		$this->navigationCurrentItemKey = 'config/theme_' . $id;
		
		return $this->render('//form_template', array(
			'view' => 'theme',
			'form' => $form,
			'title' => Theme::findThemeTitle($id) . '配置'
		));
		
	}
	
	public function actionDelete()
	{
		if ( isset($_POST['id']) ) {
			$id = $_POST['id'];
		} else {
			$id = array(intval($_GET['id']));
		}
	
		$count = 0;
		$criteria = new CDbCriteria();
		$criteria->addInCondition('id', $id);
	
		$count = ConfigType::model()->deleteAll($criteria);
	
		if ( Yii::app()->request->isAjaxRequest ) {
			echo 'ok';
		} else {
			if ( $count ) {
				$this->setFlashMessage('success', "删除成功：共删除 {$count} 条记录！");
			} else {
				$this->setFlashMessage('information', "该记录不存在或已经被删除！");
			}
	
			$url = Yii::app()->getRequest()->getUrlReferrer();
			if ( $url )
				$this->redirect($url);
		}
	}
	
	public function actionUpdateSort()
	{
		return $this->doUpdateSort('config_type');
	}
}
