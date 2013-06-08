<?php
class UserController extends AdminController
{
	public $navigationCurrentItemKey = 'system/user';
	
	public function actionIndex()
	{
		$dataProvider = new CActiveDataProvider('User');	    

	    $this->render('index', array(
	    	'dataProvider' => $dataProvider
	    
	    ));
	}
	
	public function actionCreate()
	{
		if ( !$this->getUser()->isAdmin() ) {
			throw new CHttpException(401);
		}
		
		$model = new UserForm();
		
		if( isset($_POST['UserForm']) ) {
			if ($model->addUser($_POST['UserForm'])) {
				$this->setFlashMessage('success', '创建成功！');
				$this->redirect(array('index'));
			}
		}
	
		$this->render('//form_template', array(
			'form' => $model,
			'title' => '创建用户'
		));
	}
	
	public function actionUpdate()
	{
		$model = new UserForm('update');
		
		if(isset($_POST['UserForm'])) {
			if ($model->updateUser($_POST['UserForm'])) {
				$this->setFlashMessage('success', '更新成功！');
			}
		} else {
			$id = intval($_GET['id']);
			$user = User::model()->findByPk($id);
			
			if ( !$user )
				throw new CHttpException(404);
			
			$model->setAttributes($user->getAttributes(), false);
			$model->password = null;
		}
		
		if ( !$this->getUser()->isAdmin() && intval($this->getUser()->getId()) !== $id) {
			throw new CHttpException(401);
		}
		
		$this->render('//form_template', array(
			'form' => $model,
			'title' => '更新用户'
		));
	}
	
	public function actionDelete()
	{
		$id = intval($_GET['id']);
		$user = User::model()->findByPk($id);
		
		if ( !$user )
			throw new CHttpException(404);
		
		$count = $user->delete();
		
		$this->setFlashMessage(success, "共删除 {$count} 位用户！");
		
		$url = Yii::app()->getRequest()->getUrlReferrer();
		if ( $url )
			$this->redirect($url);
	}
	
	public function getShortcuts()
	{
		return array(
			array(
				'shortcut' => $this->asset('images/icons/users_group.png'),
				'label' => '用户列表',
				'url' => $this->createUrl('index')
			),
			array(
				'shortcut' => $this->asset('images/icons/user_add.png'),
				'label' => '创建用户',
				'url' => $this->createUrl('create')
			)
		);
	}
}
