<?php
/**
 * 根据栏目ID显示其下第一个子栏目的所有文档分页列表
 * 
 * $channel_id int 栏目ID
 */
$this->renderPartial('/news/list', array(
	'top_id' => $channel_id,
	'channel_id' => $this->getFirstSubChannelId($channel_id)
))
?>