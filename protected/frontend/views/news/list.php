<?php
/**
 * 根据栏目ID显示该栏目下所有文档分页列表
 * 
 * $channel_id int 栏目ID
 * $top_id int optional 顶级栏目ID，没有指定，根据$channel_id获取
 */

if ( !isset($top_id) )
	$top_id = $this->getTopChannelId($channel_id);
?>

<?php
echo $this->renderPartial('/blocks/breadcrumb.php', array(
        'top_id' => $top_id,
        'channel_id' => $channel_id
    ));
?>

<div class="news clearfix">
    <div class="news-wrap fl">
        <?php

        list($archives, $total) = $this->getArchivesForPagerByChannelId($channel_id, 10);

        echo $this->renderPartial('/blocks/newslist', array(
            'data' => $archives,
            'hasPostTime' => true,
            'class' => 'news-list'
        ));?>

        $this->renderPager($total, 'digg');
    </div>

    <?php echo $this->renderPartial('/channel/sidebar', array('top_id' => $top_id, 'channel_id' => $channel_id));?>

</div>