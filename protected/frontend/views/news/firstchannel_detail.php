<?php
/**
 * 根据栏目ID详细显示其第一个子栏目下的第一篇文档
 * 侧边边显示的是该栏目下所有子栏目列表
 * 
 * $channel_id int 栏目ID
 */
$this->renderPartial('/news/channel_detail', array(
	'top_id' => $channel_id,
	'channel_id' => $this->getFirstSubChannelId($channel_id)
))
?>