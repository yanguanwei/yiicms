<?php
class SiteController extends FrontendController
{
	public $activeNavKey = 'home';
	
	public function getPageTitle()
	{
		return Yii::app()->params['title'];
	}
	
	public function actionIndex()
	{	
		$this->render('index');
	}
}
?>