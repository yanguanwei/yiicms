<?php
/**
 * 图片分布列表页
 * 
 * $channel_id int 栏目ID
 * $top_id int optional 顶级栏目ID，没有指定，根据$channel_id获取
 */
if ( !isset($top_id) )
	$top_id = $this->getTopChannelId($channel_id);
	
$this->register_assets(
	'popuplayer/jquery.popuplayer.css',
	'popuplayer/jquery.popuplayer-v1.2.js'
);

$script = <<<code
//popuplayer
$('.poplayer').popuplayer({
	hasTitle: false,
	hasControl: false,
	hasOverlay: false,
	//transition: 'elastic',
	skin: 'fancy'
});
code;
$this->register_script('poplayer', $script);
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
list( $archives, $total ) = $this->getArchivesForPagerByChannelId($channel_id, 10);
foreach ( $archives as $row ) {
	echo sprintf('<li><a href="%s" title="%s" class="poplayer" description="%s"><img src="%s" /></a></li>',
		$row['cover'],
		$row['title'],
		$row['description'],
		$row['cover']
	);
}
?>
			</ul>
<?php
$this->renderPager($total, 'digg');
?>
		</div>
		
	</div>
	
</div>