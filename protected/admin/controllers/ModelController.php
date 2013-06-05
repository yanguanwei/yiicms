<?php
class ModelController extends AdminController
{
	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('ChannelModel');
		
		$this->render('list', array(
			'dataProvider' => $dataProvider  
		));
	}
	
	public function actionCreate()
	{
		$model = new ChannelModel();
		
		if ( isset($_POST['ChannelModel']) ) {
			$model->setAttributes($_POST['ChannelModel'], false);
			$model->id = null;
			if ( $model->save() ) {
				$this->setFlashMessage('success', '创建成功！点击<a href="'.$this->createUrl('create').'">继续创建</a>');
				return $this->redirect($this->createUrl('update', array('id' => $model->id)));
			} else {
				$this->setFlashMessage('error', "创建失败");
			}
		}
		
		$this->render('//form_template', array(
			'model' => $model,
			'title' => '创建模型'
		));
	}
	
	public function actionUpdate($id)
	{
		$model = ChannelModel::model()->findByPk($id);
		
		if ( !$model ) {
			$this->setFlashMessage('error', "没有找到ID为{$id}的记录！");
			$this->redirect(array('index'));
		}
		
		if ( isset($_POST['ChannelModel']) ) {
			$model->setAttributes($_POST['ChannelModel'], false);
				
			if (  $model->validate() ) {
				if ( $model->save() ) {
					$this->setFlashMessage('success', '更新成功！');
				} else {
					$this->setFlashMessage('error', "更新失败");
				}
			}
		}
		
		$this->render('//form_template', array(
			'model' => $model,
			'title' => '更新模型'
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
	
		$count = ChannelModel::model()->deleteAll($criteria);
	
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
	
	protected function beforeRender($view)
	{
		$this->layout = '/layouts/iframe';
		
		return parent::beforeRender($view);
	}
}
?>