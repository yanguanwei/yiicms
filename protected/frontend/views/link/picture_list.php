<?php
/**
 * $channel_id int 栏目ID
 * $top_id int optional 顶级栏目ID；如果没指定，则根据$channel_id获取
 */

if ( !isset($top_id) )
	$top_id = $this->getTopChannelId($channel_id);
?>

<div class="layout">

	<div class="c2l" id="sidebar">
		<?php $this->renderPartial('/channel/sidebar', array(
			'channel_id' => $top_id
		));?>
	
		<?php $this->renderPartial('/sidebars/contactus');?>
	</div>
	
	<div class="c2r picture" id="mainContent">
		<div class="ad">
			<img src="<?php echo $this->asset('images/ad.jpg')?>" />
		</div>
		
		<div class="hd">
			<div class="position">
				<label>您当前所在的位置：</label>
				<a href="<?php echo $this->createUrl('site/index')?>">首页</a>-
				<a href="<?php echo $this->createChannelUrl($top_id)?>"><?php echo $this->getChannelTitle($top_id)?></a>
			</div>
			<div class="tit">
				<h2><?php echo $this->getChannelTitle($channel_id);?></h2>
			</div>
		</div>
		
		<div class="bd">
			<ul class="picturelist">
<?php
foreach ( $this->getFriendLinksByChannelId($channel_id) as $row ) {
	echo sprintf('<li><a href="%s" title="%s" target="_blank"><img src="%s" /></a></li>',
		$row['url'],
		$row['title'],
		$row['logo']
	);
}
?>
			</ul>
		</div>
		
	</div>
	
</div>