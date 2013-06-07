<?php
/**
 * $channel_id int 栏目ID
 * $top_id int optional 顶级栏目ID；如果没指定，则根据$channel_id获取
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
        <ul class="news-list">
            <li><a href="#"><span class="fr time">2013-05-22 09:45</span>的说法角度看是垃圾克里夫绝对是卡垃圾快放假啊是放大看撒</a></li>
            <li><a href="#"><span class="fr time">2013-05-22 09:45</span>的说法角度看是垃圾克里夫绝对是卡垃圾快放假啊是放大看撒</a></li>
            <li><a href="#"><span class="fr time">2013-05-22 09:45</span>的说法角度看是垃圾克里夫绝对是卡垃圾快放假啊是放大看撒</a></li>
            <li><a href="#"><span class="fr time">2013-05-22 09:45</span>的说法角度看是垃圾克里夫绝对是卡垃圾快放假啊是放大看撒</a></li>
            <li><a href="#"><span class="fr time">2013-05-22 09:45</span>的说法角度看是垃圾克里夫绝对是卡垃圾快放假啊是放大看撒</a></li>
            <li><a href="#"><span class="fr time">2013-05-22 09:45</span>的说法角度看是垃圾克里夫绝对是卡垃圾快放假啊是放大看撒</a></li>
            <li><a href="#"><span class="fr time">2013-05-22 09:45</span>的说法角度看是垃圾克里夫绝对是卡垃圾快放假啊是放大看撒</a></li>
            <li><a href="#"><span class="fr time">2013-05-22 09:45</span>的说法角度看是垃圾克里夫绝对是卡垃圾快放假啊是放大看撒</a></li>
            <li><a href="#"><span class="fr time">2013-05-22 09:45</span>的说法角度看是垃圾克里夫绝对是卡垃圾快放假啊是放大看撒</a></li>
            <li><a href="#"><span class="fr time">2013-05-22 09:45</span>的说法角度看是垃圾克里夫绝对是卡垃圾快放假啊是放大看撒</a></li>
        </ul>
    </div>
    <div class="news-sidebar fl">
        <h4 class="title">信息导航</h4>
        <div class="sidebar-wrap">
            <dl class="sidebar-list sidebar-list1">
                <dt><a href="#">最新资讯</a></dt>
                <dd>
                    <ul>
                        <li><a href="#">开幕式</a></li>
                        <li><a href="#">闭幕式</a></li>
                        <li><a href="#">促销信息</a></li>
                        <li><a href="#">时装展览</a></li>
                    </ul>
                </dd>
            </dl>
            <dl class="sidebar-list sidebar-list2">
                <dt><a href="#">购物节</a></dt>
                <dd></dd>
            </dl>
            <dl class="sidebar-list sidebar-list3">
                <dt><a href="#">主题活动</a></dt>
                <dd></dd>
            </dl>
            <dl class="sidebar-list sidebar-list4">
                <dt><a href="#">活动预告</a></dt>
                <dd></dd>
            </dl>
        </div>
    </div>
</div>