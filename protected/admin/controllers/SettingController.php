<?php
class SettingController extends AdminController
{
	
	public function actionSite()
	{
		$this->performAction('site');
	}
	
	protected function performAction($key)
	{
		$formName = ucfirst($key).'SettingForm';
		$model = new $formName();
		
		if(isset($_POST[$formName])) {
			if($model->update($key, $_POST[$formName])) {
				Yii::app()->user->setFlash('message', array(
					'state' => 'success',
					'content' => '配置修改成功！'
				));
			}
		}
		
		//$model->attributes = Yii::app()->params->site;
		
		$this->render($key, array(
			'form' => $model
		));
	}
}
?>