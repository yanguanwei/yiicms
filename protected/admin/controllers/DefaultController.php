<?php
class DefaultController extends AdminController
{
	public function actionIndex()
	{
		$this->contentTitle = Yii::app()->name . '  后台管理系统';
		
		return $this->render('index');
	}
	
	public function getShortcuts()
	{
		return array(
			array(
				'shortcut' => $this->asset('images/icons/paper_content_pencil_48.png'),
				'label' => '发布促销',
				'url' => $this->createUrl('promotion/create', array('cid' => 5))
			),
			array(
				'shortcut' => $this->asset('images/icons/user_add.png'),
				'label' => '创建商家',
				'url' => $this->createUrl('merchant/create', array('cid' => 3))
			),
			array(
				'shortcut' => $this->asset('images/icons/favorite.png'),
				'label' => '创建支持单位',
				'url' => $this->createUrl('link/create', array('cid' => 7))
			),
			array(
				'shortcut' => $this->asset('images/icons/picture.png'),
				'label' => '创建幻灯片',
				'url' => $this->createUrl('news/create', array('cid' => 20))
			),
			array(
				'shortcut' => $this->asset('images/icons/settings_48.png'),
				'label' => '网站配置',
				'url' => $this->createUrl('config/theme', array('id' => 1))
			)
		);
	}
}
