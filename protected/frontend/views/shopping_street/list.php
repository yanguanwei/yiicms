<?php

$tags = $this->getTags('location');
if (isset($_GET['tid'])) {
    $tid = intval($_GET['tid']);
} else {
    $tids = array_keys($tags);
    $tid = reset($tids);
}

if (!isset($tags[$tid])) {
    throw new CHttpException(404);
}

echo $this->renderPartial('/blocks/breadcrumb', array(
    'topChannel' => $topChannel,
    'channel' => $channel,
    'current' => $tags[$tid]->title
));
?>
<div class="shoppingStreet clearfix">
    <div class="shoppingStreet-left fl">
        <h4 class="title"><img src="<?php echo $this->asset('images/shoppingStreet-img0.png')?>" /></h4>
        <div class="shoppingStreet-nav">
            <ul>
                <?php
                foreach ($tags as $tag) {
                    echo sprintf('<li><a href="%s">%s</a></li>', $this->createUrl('', array('tid' => $tag->id)), $tag->title);
                }
                ?>
            </ul>
        </div>
    </div>
    <div class="shoppingStreet-right fr">
        <p class="lookMap"><a href="#"><img src="<?php echo $this->asset('images/shoppingStreet-img2.png')?>" /></a></p>
        <div class="shoppingStreet-pic">
            <ul class="clearfix">
                <?php
                foreach ($this->getArchivesByTag($tid, 'picture', 4, 6) as $pic) {
                    echo sprintf('<li><img src="%s" width="240" height="169" alt="%s" /></li>', $pic->cover, $pic->title);
                }
                ?>
            </ul>
        </div>
        <div class="shoppingStreet-news">
            <h4 class="title"><img src="<?php echo $this->asset('images/shoppingStreet-img4.png');?>" /></h4>
            <div class="news-list">
                <ul>
                    <?php
                    list($archives, $total) = $this->getArchivesForPagingByChannel($this->getChannel(5), 10);

                    foreach ($archives as $archive) {
                        echo sprintf(
                            '<li><a href="%s" target="_blank">%s</a></li>',
                            $this->createArchiveUrl($archive['id']), $archive['title']
                        );
                    }
                    ?>
                </ul>
            </div>
            <?php $this->renderPager($total, 'flickr');?>
        </div>
    </div>
</div>