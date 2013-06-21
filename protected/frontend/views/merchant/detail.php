<?php
$merchant = $this->getMerchant($archive->id);
if (!$merchant) {
    throw new CHttpException(404);
}
$promotionChannel = $this->getChannel(5);
$tags = array();
foreach ($channel->tags as $type) {
    if (isset($_GET[$type]) && $_GET[$type]) {
        $tags[] = $this->getTagTitle($_GET[$type]);
    }
}
$current = implode(' & ', $tags);
?>
<div class="breadcrumb">
    <a href="/">首页</a>&lt;
    <a href="<?php echo $this->createUrl('channel/merchants')?>">商家</a>&lt;
    <a<?php echo $current ? '' : ' class="current"'?>><?php echo $archive->title?></a>
    <?php echo $current ? '&lt;<a class="current">'.$current.'</a>' : ''?>
</div>
<div class="merchant-nav">
    <div class="nav-wrap">
        <ul class="clearfix">
            <?php
            foreach ($this->getTags('promotion_category') as $tag) {
                echo sprintf(
                    '<li class="clearfix"><a href="%s"><span class="pic fl"><img src="%s"/></span><span class="txt fl">%s</span></a></li>',
                    $this->createUrl('', array('promotion_category' => $tag->id, 'id' => $archive->id)), $tag->cover, $tag->title
                );
            }
            ?>
        </ul>
    </div>
</div>
<!--商家列表-->
<?php

$join = array(
    'promotion' => array(
        'on' => array('id' => 'id'),
        'fields' => array('discounts', 'start_time', 'end_time'),
        'condition' => array(
            'promotion.phone=?' => $merchant->phone
        )
    )
);
list($archives, $total) = $this->getArchivesForPagingByChannel($promotionChannel, 12, $join);
?>
<div class="merchant-list">
    <h4 class="tt"><span><?php echo $archive->title?></span></h4>
    <ul class="clearfix">
        <?php
        foreach ($archives as $promotion) :
            ?>
            <li>
                <div class="pic"><a href="<?php echo $this->createArchiveUrl($promotion['id'])?>" target="_blank">
                        <img src="<?php echo $promotion['cover']?>"/></a>
                </div>
                <div class="title"><?php //echo $promotion->promotion->getLocationTitle()?></div>
                <div class="other clearfix"><?php echo $promotion['title']?></div>
            </li>
        <?php
        endforeach;
        ?>
    </ul>

    <?php $this->renderPager($total, 'flickr');?>
</div>

