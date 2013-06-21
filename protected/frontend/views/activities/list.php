<?php
echo $this->renderPartial('/blocks/breadcrumb', array(
    'topChannel' => $topChannel,
    'channel' => $channel
));
?>
<div class="activity shoppingStreet clearfix">
    <div class="shoppingStreet-left fl">
        <div class="activity-nav">
            <?php
            foreach ($topChannel->getSubChannels() as $sub) :
            ?>
            <dl class="sidebar-list sidebar-list1">
                <dt<?php echo $sub->id == $channel->id ? ' class="current"' : ''?>>
                    <a href="<?php echo $this->createChannelUrl($sub->id)?>"><?php echo $sub->title?></a>
                </dt>
                <dd>
                    <?php
                    if ($sub->tags) {
                        echo '<ul>';
                        foreach (Tag::fetchByTypes($sub->tags) as $type => $tags) {
                            foreach ($tags as $tid => $title) {
                                echo sprintf('<li><a href="%s">%s</a></li>', $sub->getTagViewUrl($type, $tid), $title);
                            }
                        }
                        echo '</ul>';
                    }
                    ?>
                </dd>
            </dl>
            <?php
            endforeach;
            ?>
        </div>
    </div>
    <div class="shoppingStreet-right fr">
        <div class="shoppingStreet-pic">
            <ul class="clearfix">
                <?php
                foreach ($this->getLinksByChannelWithTags($channel) as $link) {
                    echo sprintf(
                        '<li><a href="%s" target="_blank"><img src="%s" width="240" height="169" /></a></li>',
                        $link->url,
                        $link->logo
                    );
                }
                ?>
            </ul>
        </div>
        <div class="shoppingStreet-news">
            <h4 class="title"><img src="<?php echo $this->asset('images/shoppingStreet-img4.png')?>" /></h4>
            <div class="news-list">
                <ul>
                    <?php
                    list($archives, $total) = $this->getArchivesForPagingByChannel($channel, 10);

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