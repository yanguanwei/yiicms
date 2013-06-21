<?php
echo $this->renderPartial('/blocks/breadcrumb', array(
    'topChannel' => $topChannel,
    'channel' => $channel
));
?>
<div class="merchant-nav" xmlns="http://www.w3.org/1999/html">
    <div class="nav-wrap">
        <ul class="clearfix">
            <?php
            foreach ($this->getTags('promotion_category') as $tag) {
                echo sprintf(
                    '<li class="clearfix"><a href="%s"><span class="pic fl"><img src="%s"/></span><span class="txt fl">%s</span></a></li>',
                    $this->createUrl('', array('promotion_category' => $tag->id)), $tag->cover, $tag->title
                );
            }
            ?>
        </ul>
    </div>
</div>
<!--商家列表-->
<?php
foreach ($this->getMerchants($channel, 5) as $archive) :
?>
 <div class="merchant-list">
        <h4 class="tt"><a href="<?php echo $archive->getViewUrl();?>" target="_blank"><span><?php echo $archive->title?></span></a></h4>
        <ul class="clearfix">
            <?php
            foreach ($archive->merchant->getTopPromotions() as $promotion) :
            ?>
            <li>
                <div class="pic"><a href="<?php echo $this->createArchiveUrl($promotion->id)?>" target="_blank"><img src="<?php echo $promotion->cover?>"/></a></div>
                <div class="title"><?php echo $promotion->promotion->getLocationTitle()?></div>
                <div class="other clearfix"><?php echo $promotion->title?></div>
            </li>
            <?php endforeach;?>
        </ul>
    </div>
<?php endforeach;?>
