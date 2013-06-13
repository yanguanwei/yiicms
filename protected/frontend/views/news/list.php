<?php
echo $this->renderPartial('/blocks/breadcrumb', array(
        'topChannel' => $topChannel,
        'channel' => $channel
    ));
?>

<div class="news clearfix">
    <div class="news-wrap fl">
        <?php

        list($archives, $total) = $this->getArchivesForPagerByChannelWithTags($channel, 10);

        echo $this->renderPartial('/news/newslist_block', array(
            'archives' => $archives,
            'hasPostTime' => true,
            'class' => 'news-list'
        ));?>

        <?php $this->renderPager($total, 'digg');?>
    </div>

    <?php echo $this->renderPartial('/channel/sidebar', array('topChannel' => $topChannel));?>
</div>