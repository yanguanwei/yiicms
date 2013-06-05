<?php
class ChannelController extends ChannelBehaviorController
{
	public function actionPark()
	{
		$channels = array (
  1 => 
  array (
    'id' => '14',
    'template' => '/news/park_detail',
  ),
);
	
		if ( !$channels[Yii::app()->params['theme_id']] )
			throw new CHttpException(404);
		
		$current = $channels[Yii::app()->params['theme_id']];
		if ( !$current['template'] )
			throw new CHttpException(404);
			
		if ( $current['template'] === '1' )
			return $this->redirect($this->createChannelUrl($this->getFirstSubChannelId($current['id'])));
			
		$this->activeNavKey = ChannelAlias::getChannelAlias(Channel::getTopChannelId($current['id']));

		return $this->render($current['template'], array(
				'channel_id' => $current['id']
			));
	}
	public function actionAboutus()
	{
		$channels = array (
  1 => 
  array (
    'id' => '15',
    'template' => '/news/detail',
  ),
);
	
		if ( !$channels[Yii::app()->params['theme_id']] )
			throw new CHttpException(404);
		
		$current = $channels[Yii::app()->params['theme_id']];
		if ( !$current['template'] )
			throw new CHttpException(404);
			
		if ( $current['template'] === '1' )
			return $this->redirect($this->createChannelUrl($this->getFirstSubChannelId($current['id'])));
			
		$this->activeNavKey = ChannelAlias::getChannelAlias(Channel::getTopChannelId($current['id']));

		return $this->render($current['template'], array(
				'channel_id' => $current['id']
			));
	}
	public function actionPolicy()
	{
		$channels = array (
  1 => 
  array (
    'id' => '16',
    'template' => '1',
  ),
);
	
		if ( !$channels[Yii::app()->params['theme_id']] )
			throw new CHttpException(404);
		
		$current = $channels[Yii::app()->params['theme_id']];
		if ( !$current['template'] )
			throw new CHttpException(404);
			
		if ( $current['template'] === '1' )
			return $this->redirect($this->createChannelUrl($this->getFirstSubChannelId($current['id'])));
			
		$this->activeNavKey = ChannelAlias::getChannelAlias(Channel::getTopChannelId($current['id']));

		return $this->render($current['template'], array(
				'channel_id' => $current['id']
			));
	}
	public function actionService()
	{
		$channels = array (
  1 => 
  array (
    'id' => '17',
    'template' => '/news/firstchannel_detail',
  ),
);
	
		if ( !$channels[Yii::app()->params['theme_id']] )
			throw new CHttpException(404);
		
		$current = $channels[Yii::app()->params['theme_id']];
		if ( !$current['template'] )
			throw new CHttpException(404);
			
		if ( $current['template'] === '1' )
			return $this->redirect($this->createChannelUrl($this->getFirstSubChannelId($current['id'])));
			
		$this->activeNavKey = ChannelAlias::getChannelAlias(Channel::getTopChannelId($current['id']));

		return $this->render($current['template'], array(
				'channel_id' => $current['id']
			));
	}
	public function actionNews()
	{
		$channels = array (
  1 => 
  array (
    'id' => '18',
    'template' => '1',
  ),
);
	
		if ( !$channels[Yii::app()->params['theme_id']] )
			throw new CHttpException(404);
		
		$current = $channels[Yii::app()->params['theme_id']];
		if ( !$current['template'] )
			throw new CHttpException(404);
			
		if ( $current['template'] === '1' )
			return $this->redirect($this->createChannelUrl($this->getFirstSubChannelId($current['id'])));
			
		$this->activeNavKey = ChannelAlias::getChannelAlias(Channel::getTopChannelId($current['id']));

		return $this->render($current['template'], array(
				'channel_id' => $current['id']
			));
	}
	public function actionIndustry()
	{
		$channels = array (
  1 => 
  array (
    'id' => '19',
    'template' => '/news/detail',
  ),
);
	
		if ( !$channels[Yii::app()->params['theme_id']] )
			throw new CHttpException(404);
		
		$current = $channels[Yii::app()->params['theme_id']];
		if ( !$current['template'] )
			throw new CHttpException(404);
			
		if ( $current['template'] === '1' )
			return $this->redirect($this->createChannelUrl($this->getFirstSubChannelId($current['id'])));
			
		$this->activeNavKey = ChannelAlias::getChannelAlias(Channel::getTopChannelId($current['id']));

		return $this->render($current['template'], array(
				'channel_id' => $current['id']
			));
	}
	public function actionEnterprise()
	{
		$channels = array (
  1 => 
  array (
    'id' => '20',
    'template' => '1',
  ),
);
	
		if ( !$channels[Yii::app()->params['theme_id']] )
			throw new CHttpException(404);
		
		$current = $channels[Yii::app()->params['theme_id']];
		if ( !$current['template'] )
			throw new CHttpException(404);
			
		if ( $current['template'] === '1' )
			return $this->redirect($this->createChannelUrl($this->getFirstSubChannelId($current['id'])));
			
		$this->activeNavKey = ChannelAlias::getChannelAlias(Channel::getTopChannelId($current['id']));

		return $this->render($current['template'], array(
				'channel_id' => $current['id']
			));
	}
}	