<?php

$news = $this->getNews($archive->id);
if (!$news) {
    throw new CHttpException(404);
}

//访问计数
$this->visitArchive($archive->id);

echo $this->renderPartial('/blocks/breadcrumb', array(
    'topChannel' => $topChannel,
    'channel' => $channel
));
?>


<div class="news clearfix">
    <div class="news-wrap fl">
        <?php $this->renderPartial('/news/content_block', array(
            'news' => $news,
            'archive' => $archive
        ))?>
    </div>

    <?php echo $this->renderPartial('/channel/sidebar', array('topChannel' => $topChannel, 'channel' => $channel));?>
</div>
