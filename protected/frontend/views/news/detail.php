<?php
/**
 * 根据栏目ID或文档ID详细显示文档
 * $channel_id或$archive_id要指定其一
 * 
 * $channel_id int 栏目ID 根据栏目ID详细显示其栏目下的第一篇文档
 * $archive_id int 文档ID 
 * $top_id int optional 顶级栏目ID；如果没指定，则根据$channel_id获取
 * $sidebar string optional 侧边栏导航的内容，默认为该文档所属父栏目的同级栏目列表
 */

if ( $channel_id ) {
	$archive_id = $this->getFirstArchiveIdByChannelId($channel_id);
}

if ( $archive_id ) {
	$archive = $this->getArchive($archive_id);
	$news = $this->getNews($archive_id);
	
	if ( !$channel_id )
		$channel_id = intval($archive['cid']);
} else {
	throw new CHttpException(404);
}

if ( !$top_id )
	$top_id = $this->getTopChannelId($channel_id);

$sidebar = isset($sidebar) ? $sidebar : $this->renderPartial('/news/sidebar', array('channel_id' => $channel_id), true);

//访问计数
$this->visitArchive($archive_id);

echo $this->renderPartial('/blocks/breadcrumb.php', array(
    'top_id' => $top_id,
    'channel_id' => $channel_id
));
?>


<div class="news clearfix">
    <div class="news-wrap fl">
        <?php $this->renderPartial('/news/content_block', array(
            'news' => $news,
            'archive' => $archive
        ))?>
    </div>

    <?php echo $this->renderPartial('/channel/sidebar', array('top_id' => $top_id, 'channel_id' => $channel_id));?>
</div>
