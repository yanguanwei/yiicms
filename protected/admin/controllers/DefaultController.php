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
				'shortcut' => $this->asset('images/icons/info_48.png'),
				'label' => '发布公告',
				'url' => $this->createUrl('news/create', array('cid' => 66))
			),
			array(
				'shortcut' => $this->asset('images/icons/paper_content_pencil_48.png'),
				'label' => '发布新闻',
				'url' => $this->createUrl('news/create', array('cid' => 29))
			),
			array(
				'shortcut' => $this->asset('images/icons/favorite.png'),
				'label' => '友情链接',
				'url' => $this->createUrl('link/create', array('cid' => 51))
			),
			array(
				'shortcut' => $this->asset('images/icons/video.png'),
				'label' => '发布视频',
				'url' => $this->createUrl('video/create', array('cid' => 69))
			),
			array(
				'shortcut' => $this->asset('images/icons/settings_48.png'),
				'label' => '网站配置',
				'url' => $this->createUrl('config/theme', array('id' => 1))
			)
		);
	}
}
?>