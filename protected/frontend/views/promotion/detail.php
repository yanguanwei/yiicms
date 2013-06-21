<?php
$promotion = $this->getPromotion($archive->id);
if (!$promotion) {
    throw new CHttpException(404);
}

$merchant = $promotion->getMerchant();
if ($merchant) {
    $merchantArchive = $this->getArchive($merchant->id);
    $merchantTitle = $merchantArchive->title;
    $merchantAddress = $merchant->address;
    $merchantContent = $merchant->content;
}

//访问计数
$this->visitArchive($archive->id);

echo $this->renderPartial('/blocks/breadcrumb', array(
    'topChannel' => $topChannel,
    'channel' => $channel,
    'current' => $archive->title
));
?>

<div class="promotion2-view">
    <div class="promotion2-info clearfix">
        <div class="pic fl"><img src="<?php echo $archive->cover?>" /></div>
        <div class="txt fl">
            <h4 class="t">商家介绍</h4>
            <p class="memo">
                <?php echo $merchantContent;?>
            </p>
            <p class="add">
                商家名称：<?php echo $merchantTitle?><br />
                商铺地址：<?php echo $merchantAddress?><br />
                促销时间：<?php echo $promotion->start_time?> — <?php echo $promotion->end_time?></p>
        </div>
        <div class="link fl">
            <a href="<?php echo $this->createUrl('apply/index')?>">
                <img src="<?php echo $this->asset('images/promotion2-img1.png')?>" />
            </a><br />
            <em class="color-red"><?php echo $promotion->phone?></em>
        </div>
    </div>
</div>
<div class="promotion2-viewBox clearfix">
    <div class="con fl">
        <dl>
            <dt>活动详情</dt>
            <dd class="act">
                <?php echo $promotion->content;?>
            </dd>
            <dt>地图</dt>
            <dd class="map"><img src="<?php echo $this->asset('images/map.png')?>" /></dd>
        </dl>
    </div>
    <div class="related fr">
        <h4 class="title"><img src="<?php echo $this->asset('images/related-title.png')?>" /></h4>
        <div class="related-wrap">
            <ul>
                <?php
                foreach ($promotion->getRelativePromotions(8) as $p) {
                    echo sprintf('<li><a href="%s">%s</a></li>', $p->getViewUrl(), $p->title);
                }
                ?>
            </ul>
        </div>
    </div>
</div>