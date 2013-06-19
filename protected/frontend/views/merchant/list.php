<!-- 商家展示 导航 -->
<div class="merchant-nav">
    <div class="nav-wrap">
        <ul class="clearfix">
            <li class="clearfix"><a href="#"><span class="pic fl"><img src="images/merchant-nav-img1.png"/></span><span
                        class="txt fl">食品海鲜</span></a></li>
            <li class="clearfix"><a href="#"><span class="pic fl"><img src="images/merchant-nav-img2.png"/></span><span
                        class="txt fl">文具礼品</span></a></li>
            <li class="clearfix"><a href="#"><span class="pic fl"><img src="images/merchant-nav-img3.png"/></span><span
                        class="txt fl">服饰家纺</span></a></li>
            <li class="clearfix"><a href="#"><span class="pic fl"><img src="images/merchant-nav-img4.png"/></span><span
                        class="txt fl">家电数码</span></a></li>
            <li class="clearfix"><a href="#"><span class="pic fl"><img src="images/merchant-nav-img5.png"/></span><span
                        class="txt fl">家居日用</span></a></li>
            <li class="clearfix"><a href="#"><span class="pic fl"><img src="images/merchant-nav-img6.png"/></span><span
                        class="txt fl">生活服务</span></a></li>
        </ul>
    </div>
</div>
<!--商家列表-->
<?php
$arhcives = $this->getMerchantsWithPromotions();
foreach ($arhcives as $archive) :
?>
 <div class="merchant-list">
        <h4 class="tt"><span><?php echo $archive->title?></span></h4>
        <ul class="clearfix">
            <?php
            foreach ($archive->merchant->getTopPromotions() as $promotion) :
            ?>
            <li>
                <div class="pic"><a href="#"><img src="<?php echo $promotion->cover?>"/></a></div>
                <div class="title"><?php echo $promotion->promotion->getLocationTitle()?></div>
                <div class="other clearfix"><?php echo $promotion->title?></div>
            </li>
            <?php
            endforeach;
            ?>
        </ul>
    </div>
<?php
endforeach;
?>
