<?php
/**
 * 园区概况详细页
 * 
 * 根据栏目ID或文档ID详细显示文档
 * $channel_id或$archive_id要指定其一
 *
 * $channel_id int 栏目ID 根据栏目ID详细显示其栏目下的第一篇文档
 * $archive_id int 文档ID
 * $top_id int optional 顶级栏目ID；如果没指定，则根据$channel_id获取
 * $sidebar string optional 侧边栏导航的内容，默认为该文档所属父栏目的同级栏目列表
 */

if ( $channel_id ) {
	$archive_id = $this->getFirstArchiveIdByChannelId($channel_id);
}

if ( $archive_id ) {
	$archive = $this->getArchive($archive_id);
	$news = $this->getNews($archive_id);
	
	if ( !$channel_id )
		$channel_id = intval($archive['cid']);
} else {
	throw new CHttpException(404);
}

if ( !$top_id )
	$top_id = $this->getTopChannelId($channel_id);

$sidebar = isset($sidebar) ? $sidebar : $this->renderPartial('/news/sidebar', array('channel_id' => $channel_id), true);

//访问计数
$this->visitArchive($archive_id);
?>
<div class="layout">

	<div class="c2l" id="sidebar">
		<?php echo $sidebar;?>
		<?php $this->renderPartial('/sidebars/contactus');?>
	</div>
	
	<div class="c2r park" id="mainContent">
		<div class="ad">
			<img src="<?php echo $this->asset('images/ad.jpg')?>" />
		</div>
		
		<div class="hd">
			<div class="position">
				<label>您当前所在的位置：</label>
				<a href="<?php echo $this->createUrl('site/index')?>">首页</a>-
				<a href="<?php echo $this->createChannelUrl($channel_id)?>"><?php echo $this->getChannelTitle($top_id)?></a>
			</div>
			<div class="tit">
				<h2><?php echo $archive['title'];?></h2>
			</div>
		</div>
		
		<div class="bd">
			<div class="news">
				<h4 class="tit"><img src="<?php echo $this->asset('images/park_aboutus.jpg')?>" /></h4>
				<div class="cnt">
					<?php echo $news['content']?>
				</div>
				<h4 class="tit"><img src="<?php echo $this->asset('images/park_ourhome.jpg')?>" /></h4>
				<div class="ourhome">
					<img src="<?php echo $this->asset('images/park_ourhome_imgs.jpg')?>" />
				</div>
			</div>
		</div>
	</div>
	
</div>