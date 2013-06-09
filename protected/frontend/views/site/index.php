<!-- banner 最新动态 -->
<div class="index-column1 clearfix">
    <div class="index-banner fl">
        <div id="xxx" class="index-banner-wrap">
            <script>
                var box =new PPTBox();
                box.width = 667; //宽度
                box.height = 293;//高度
                box.autoplayer = 5;//自动播放间隔时间
                //box.add({"url":"图片地址","title":"悬浮标题","href":"链接地址"})

<?php
//幻灯片
foreach ($this->getTopArchivesByChannelId(20) as $archive) {
    echo sprintf(
        'box.add({"url":"%s","href":"%s","title":"%s"});',
        $archive->cover,
        $archive->getViewUrl(),
        $archive->title
    );
}
?>
                box.show();
            </script>
        </div>
    </div>
    <div class="index-trends fr">
        <h4 class="title"><a href="#" class="more fr">更多</a></h4>
        <div class="trends-wrap">
<?php
$ul = array();
foreach ($this->getArchivesByChannelId(array(17, 18), 6) as $i => $archive) {
    if ($i==0) {
        echo sprintf(
            '<div class="trends-first clearfix"><img class="fl pic" src="%s" /><p>%s<a href="%s">[详细]</a></p></div>',
            $archive->cover,
            $archive->description,
            $archive->getViewUrl()
        );
    } else {
        $ul[] = sprintf('<li><a href="%s">%s</a></li>', $archive->getViewUrl(), $archive->title);
    }
}
?>
            <ul class="trends-list">
                <?php echo implode("\n", $ul)?>
            </ul>
        </div>
    </div>
</div>
<!-- 最热购物街 -->
<div class="index-column2 index-hottest">
    <h4 class="title"><img src="<?php echo $this->asset('images/index-hottest-title.png');?>" /></h4>
    <div class="hottest-wrap clearfix">
        <script>
            function switchTab(tabid,tabbox,events,effect,num){
                var n=num;
                $(tabid+">li").eq(n).attr("class","current").siblings(":not(.none)").attr("class","normal");
                $(tabbox+">div").eq(n).show().siblings().hide();
                $(tabid+" li:not(.none)").bind(events,function(){
                    $(this).attr("class","current").siblings(":not(.none)").attr("class","normal");
                    $n=$(tabid+">li").index($(this)[0]);
                    switch(effect){
                        case "slide":
                            $(tabbox+">div").eq($n).slideDown().siblings().hide();
                        case "show":
                            $(tabbox+">div").eq($n).show().siblings().hide();
                        case "fadeIn":
                            $(tabbox+">div").eq($n).fadeIn().siblings().hide();
                        default:
                            $(tabbox+">div").eq($n).show().siblings().hide();
                    }
                })
            }
        </script>

<?php
//最热购物街
$lis = $imgs = array();
foreach ($this->getTopArchivesByChannelId(4, 6) as $archive) {
    $lis[] = sprintf('<li>%s</li>', $archive->title);
    $imgs[] = sprintf('<div class=""><img src="%s" /></div>', $archive->cover);
}
?>

        <ul class="hottest-tabLi fl" id="hottest-tabLi">
            <?php echo implode("\n", $lis)?>
        </ul>
        <div class="hottest-changeWrap fl" id="hottest-changeWrap">
            <?php echo implode("\n", $imgs)?>
        </div>
        <script>switchTab("#hottest-tabLi","#hottest-changeWrap","mouseover","fadeIn","0");</script>
    </div>
</div>
<!-- 快乐购物 -->
<div class="index-column3 clearfix">
    <div class="index-happy fl">
        <h4 class="title"><a href="#" class="more fr">更多</a><img src="<?php echo $this->asset('images/index-happy-title.png'); ?>" /></h4>
        <div class="happy-wrap">
            <ul class="clearfix">
                <li><a href="#"><img src="<?php echo $this->asset('images/index-happy-img1.jpg')?>" /></a></li>
                <li><a href="#"><img src="<?php echo $this->asset('images/index-happy-img2.jpg')?>" /></a></li>
                <li><a href="#"><img src="<?php echo $this->asset('images/index-happy-img3.jpg')?>" /></a></li>
                <li><a href="#"><img src="<?php echo $this->asset('images/index-happy-img4.jpg')?>" /></a></li>
                <li><a href="#"><img src="<?php echo $this->asset('images/index-happy-img5.jpg')?>" /></a></li>
                <li><a href="#"><img src="<?php echo $this->asset('images/index-happy-img6.jpg')?>" /></a></li>
            </ul>
        </div>
    </div>
    <div class="index-section fl">
        <h4 class="title"><img src="<?php echo $this->asset('images/index-section-title.png')?>" /></h4>
        <div class="section-wrap" id="splitadver_section">
            <dl>
                <dt class="t"><a href="#">食品海鲜</a></dt>
                <dd class="d"><a href="#"><img src="<?php echo $this->asset('images/index-section-d.png')?>" /></a></dd>
                <dt class="t"><a href="#">文具礼品</a></dt>
                <dd class="d"><a href="#"><img src="<?php echo $this->asset('images/index-section-d.png')?>" /></a></dd>
                <dt class="t"><a href="#">服饰家纺</a></dt>
                <dd class="d"><a href="#"><img src="<?php echo $this->asset('images/index-section-d.png')?>" /></a></dd>
                <dt class="t"><a href="#">家电数码</a></dt>
                <dd class="d"><a href="#"><img src="<?php echo $this->asset('images/index-section-d.png')?>" /></a></dd>
                <dt class="t"><a href="#">家居日用</a></dt>
                <dd class="d"><a href="#"><img src="<?php echo $this->asset('images/index-section-d.png')?>" /></a></dd>
            </dl>
            <script type="text/javascript">$('#splitadver_section').AdAdvance();</script>
        </div>
    </div>
</div>
<!-- 支持单位 -->
<div class="index-column4 clearfix">
    <div class="index-unit fl">
        <h4 class="title"><img src="<?php echo $this->asset('images/index-unit-title.png')?>" /></h4>
        <div class="unit-wrap clearfix">
            <?php
            foreach ($this->getFriendLinksByChannelId(7, 7) as $link) {
                echo sprintf('<span><a href="%s"><img src="%s" alt="%s" /></a></a></span>', $link['url'],$link['logo'], $link['title']);
            }
            ?>
        </div>
    </div>
    <div class="index-merchant fl">
        <h4 class="title">商家展示</h4>
        <div class="merchant-wrap" id="demo">
            <div class="merchant-box" id="demo1">
                <ul>
                    <li><a href="#">史翠英</a></li>
                    <li><a href="#">史翠英</a></li>
                    <li><a href="#">史翠英</a></li>
                    <li><a href="#">史翠英</a></li>
                    <li><a href="#">史翠英</a></li>
                    <li><a href="#">史翠英</a></li>
                    <li><a href="#">史翠英</a></li>
                    <li><a href="#">史翠英</a></li>
                    <li><a href="#">史翠英</a></li>
                    <li><a href="#">史翠英</a></li>
                    <li><a href="#">史翠英</a></li>
                    <li><a href="#">史翠英</a></li>
                    <li><a href="#">史翠英</a></li>
                    <li><a href="#">史翠英</a></li>
                    <li><a href="#">史翠英</a></li>
                    <li><a href="#">史翠英</a></li>
                </ul>
            </div>
            <div id="demo2"></div>
            <script language="javascript">
                var speed=30
                demo2.innerHTML=demo1.innerHTML
                function Marquee(){
                    if(demo2.offsetTop-demo.scrollTop<=0)
                        demo.scrollTop-=demo1.offsetHeight
                    else{
                        demo.scrollTop++
                    }
                }
                var MyMar=setInterval(Marquee,speed)
                demo.onmouseover=function() {clearInterval(MyMar)}
                demo.onmouseout=function() {MyMar=setInterval(Marquee,speed)}
            </script>
        </div>
    </div>
</div>