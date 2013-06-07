<?php
class ChannelController extends ChannelBehaviorController
{
	public function actionPromotions()
	{
		$channels = array (
  1 => 
  array (
    'id' => '5',
    'template' => '',
  ),
);
	
		if ( !$channels[Yii::app()->params['theme_id']] )
			throw new CHttpException(404);

		$current = $channels[Yii::app()->params['theme_id']];

		return $this->perform($current['template'], $current['id']);
	}
}	