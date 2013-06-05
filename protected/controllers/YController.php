<?php
class YController extends CController
{	
	protected function beforeRender($view)
	{
		$cs=Yii::app()->getClientScript();
		$cs->registerCoreScript('jquery');
		
		return parent::beforeRender($view);
	}
	
	public function asset($path)
	{
		$baseUrl = null;
		if ( null !== $theme = Yii::app()->getTheme() ) {
			$baseUrl = $theme->getBaseUrl();
		} else {
			$baseUrl = Yii::app()->request->getBaseUrl();
		}
		return $baseUrl . '/assets/' . $path;
	}
	
	public function assets($path)
	{
		return Yii::app()->request->getBaseUrl() . '/assets/' . $path;
	}
	
	/**
	 * 设置消息
	 *
	 * @param string $status 消息状态，success、error、information、attention
	 * @param string $message 消息内容
	 */
	public function setFlashMessage($status, $message)
	{
		Yii::app()->user->setFlash('message', array(
			'state' => $status,
			'content' => $message)
		);
	}
	
	/**
	 * @return CWebUser
	 */
	public function getUser()
	{
		return Yii::app()->user;
	}
	
}
?>