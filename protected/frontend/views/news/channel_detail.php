<?php
/**
 * 根据栏目ID详细显示其栏目下的第一篇文档
 * 并在侧边栏显示该栏目下所有文档列表
 * 
 * $channel_id int 栏目ID
 * $top_id int optional 顶级栏目ID；如果没指定，则根据$channel_id获取
 */

if ( !$top_id ) {
	if ( $channel_id )
		$top_id = $this->getTopChannelId($channel_id);
	else if ( $archive_id )
		$top_id = $this->getTopChannelId($this->getChannelIdByArchiveId($archive_id));
}
	
$this->renderPartial('/news/detail', array(
	'channel_id' => $channel_id,
	'archive_id' => $archive_id,
	'top_id' => $top_id,
	'sidebar' =>  $this->renderPartial('/channel/sidebar', array('channel_id' => $top_id), true)
));
?>