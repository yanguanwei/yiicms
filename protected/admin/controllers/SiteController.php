<?php
class SiteController extends YController
{
	public $layout = '//layouts/main';
	
	public function actionIndex()
	{
		if ( $this->getUser()->isGuest ) {
			$this->redirect(array('login'));
		} else {
			$this->redirect(array('default/index'));
		}
	}
	
	public function actionLogin($return = null)
	{
		$model = new AdminLoginForm();
	
		if(isset($_POST['AdminLoginForm'])) {
			$model->setAttributes($_POST['AdminLoginForm'], false);
			if($model->validate()) {
				$identity = new UserIdentity($model->username, $model->password);
				if ($identity->authenticate()) {
						$duration = $model->rememberme ? 86400 : 0;
					if ( Yii::app()->user->login($identity, $duration) ) {
						return $this->redirect( $return ? $return : array('site/index'));
					} else {
						$this->setFlashMessage('error', '登录失败！');
					}
				} else {
					$this->setFlashMessage('error', '用户名或密码错误！');
				}
			}
		}
	
		$this->render('login', array(
			'model' => $model,
			'return' => $return
		));
	}
	
	public function actions()
	{
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
				'maxLength' => 4,
				'minLength' => 4,
				'height' => 28,
				'width' => 60,
				'padding' => 1,
				'offset' => 1
			)
		);
	}
	
	public function actionLogout()
	{
		Yii::app()->user->logout();
	
		$this->redirect(array('index'));
	}
}
?>