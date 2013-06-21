<?php
class ChannelController extends ChannelBehaviorController
{
	public function actionActivities()
	{
		$channels = array (
  1 => 
  array (
    'id' => '1',
    'template' => '1',
  ),
);
	
		if ( !$channels[Yii::app()->params['theme_id']] )
			throw new CHttpException(404);

		$current = $channels[Yii::app()->params['theme_id']];

		return $this->perform($current['template'], $current['id']);
	}
	public function actionMerchants()
	{
		$channels = array (
  1 => 
  array (
    'id' => '3',
    'template' => '/merchant/list',
  ),
);
	
		if ( !$channels[Yii::app()->params['theme_id']] )
			throw new CHttpException(404);

		$current = $channels[Yii::app()->params['theme_id']];

		return $this->perform($current['template'], $current['id']);
	}
	public function actionPromotions()
	{
		$channels = array (
  1 => 
  array (
    'id' => '5',
    'template' => '/promotion/list',
  ),
);
	
		if ( !$channels[Yii::app()->params['theme_id']] )
			throw new CHttpException(404);

		$current = $channels[Yii::app()->params['theme_id']];

		return $this->perform($current['template'], $current['id']);
	}
	public function actionNews()
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

		return $this->perform($current['template'], $current['id']);
	}
}	