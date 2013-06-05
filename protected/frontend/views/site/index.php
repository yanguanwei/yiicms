<?php
$this->register_assets(
	'focusimage/jquery.focusimage.js?20130110123',
	'focusimage/jquery.focusimage.css',
	'scrolling/jquery.scrolling.js'
);

$script = <<<code
$('#banner .focusimage').focusimage({
	effects: ['horizontal', 'thumbnail'],
	direction: 'left',
	easingImage: 'easeOutExpo',
	indexOnClick: true,
	hasCaption: false
});
$('#announcement .newslist').scrolling();
code;

$this->register_script('banner', $script);
?>
<div id="banner">
	<div class="focusimage focusimage-thumbnail">
		<div class="focusimage-list">
			<ul>
<?php
//焦点图片
$focusimages = $this->getArchivesByChannelId(61, 5);
foreach ( $focusimages  as $archive) {
	echo sprintf('<li><a href="%s" target="_blank"><img src="%s" /></a></li>',
		$this->createUrl('archive/detail', array('id' => $archive['id'])),
		$archive['cover']
	);
}				
?>
			</ul>
		</div>
		<div class="focusimage-index">
			<ul>
<?php
foreach ( $focusimages  as $archive) {
	echo sprintf('<li><div class="activedLayer"></div><div class="thumb"><img src="%s" /></div></li>',
		$archive['cover']
	);
}			
?>
			</ul>
		</div>
		<div class="focusimage-index-bg"></div>
	</div>
</div>

<div class="layout">

	<div class="c3l">
<?php //园区视频
$this->beginBlock('/blocks/block', array(
	'image' => $this->asset('images/block_tit_video.jpg'),
	'more' => $this->createUrl('channel/index', array('cid'=>69))
));
$video = $this->getArchive(72);
?>
<div style="width:320px; margin:10px auto 0 auto;">
	
<?php 
	$this->renderPartial('/blocks/flv', array(
		'archive_id' => 72,
		'width' => 320,
		'height' => 240,
		'autoplay' => 1
	));
?>
</div>
<?php $this->endBlock();?>

<?php $this->beginBlock('/blocks/block', array(
	'tabs' => array(
		'park' => '园区介绍',
		'enterprise' => '企业信息',
		'software' => '软件企业'
	)
));?>
		
	<div class="tab-content" id="tab-park">
<?php	//园区介绍
$archive = $this->getArchive(1);
?>	
		<div class="archive">
			<img src="<?php echo $archive['cover']?>" />
			<p><?php echo $archive['description']?></p>
		</div>
	</div>
	
	<div class="tab-content" id="tab-enterprise">
<?php	//企业信息
$this->renderPartial('/blocks/linklist', array(
	'data' => $this->getFriendLinksByChannelId(38, 8)
));
?>
	</div>
	
	
	<div class="tab-content" id="tab-software">
<?php	//软件企业
$this->renderPartial('/blocks/linklist', array(
	'data' => $this->getFriendLinksByChannelId(39, 8)
));
?>
	</div>
	
<?php $this->endBlock();?>

	</div>
	
	<div class="c3m">
<?php //宁波智慧园
$this->beginBlock('/blocks/block', array(
	'image' => $this->asset('images/block_tit_nbsp.jpg'),
	'more' => $this->createUrl('archive/detail', array('id' => 76))
));?>

<div class="img-holder">
	<a href="<?php echo $this->createUrl('archive/detail', array('id' => 76))?>"><img src="<?php echo $this->asset('img/1.gif')?>" /></a>
	<a href="<?php echo $this->createUrl('archive/detail', array('id' => 76))?>"><img src="<?php echo $this->asset('img/2.gif')?>" /></a>
</div>

<div class="news-holder">
	<h3><a href="<?php echo $this->createUrl('archive/detail', array('id' => 76))?>" target="_blank">区位优势</a></h3>
	<p>智慧园位于宁波国家高新区内，紧邻甬江；距市中心6公里...</p>
	<h3><a href="<?php echo $this->createUrl('archive/detail', array('id' => 76))?>" target="_blank">生活配套优势</a></h3>
	<p>商业配套：高富诺城市综合体<br />医疗配套：颐康医院、李惠利医院</p>
	<h3><a href="<?php echo $this->createUrl('archive/detail', array('id' => 76))?>" target="_blank">人才优势</a></h3>
	<p>宁波拥有两所国家级软件学院，位于宁波国家高新区内的浙江大学...</p>
</div>

<?php $this->endBlock();?>

<?php $this->beginBlock('/blocks/block', array(
	'tabs' => array(
		'industry' => '产业聚焦',
		'policy' => '产业政策',
		'service' => '服务指南'
	)
));?>
		
	<div class="tab-content" id="tab-industry">
<?php	//产业聚焦
$this->renderPartial('/blocks/newslist', array(
	'data' => $this->getArchivesByChannelId(19, 8)
));
?>	
	</div>
	
	
	<div class="tab-content" id="tab-policy">
<?php	//产业政策
$this->renderPartial('/blocks/newslist', array(
	'data' => $this->getArchivesByTopChannelId(16, 8)
));
?>
	</div>
	
	
	<div class="tab-content" id="tab-service">
<?php	//服务指南
$this->renderPartial('/blocks/newslist', array(
	'data' => $this->getArchivesByTopChannelId(17, 8)
));
?>
	</div>
	
<?php $this->endBlock();?>

	</div>

	<div class="c3r">
<?php 	//网站公告
$this->beginBlock('/blocks/block', array(
	'image' => $this->asset('images/block_tit_announcement.jpg'),
	'id' => 'announcement',
	'more' => $this->createUrl('channel/index', array('cid' => 66))
));
		$this->renderPartial('/blocks/newslist', array(
			'data' => $this->getArchivesByChannelId(66, 8),
			'hasPostTime' => true
		));
$this->endBlock();
?>

<?php //新闻中心
$this->beginBlock('/blocks/block', array(
	'image' => $this->asset('images/block_tit_news.jpg'),
	'more' => $this->createUrl('channel/news')
));

	$this->renderPartial('/blocks/newslist', array(
			'data' => $this->getArchivesByChannelId(29, 8),
			'hasPostTime' => true
		));

$this->endBlock();?>

	</div>
	
	<div class="c3l2 clear" id="partners">
<?php //合作伙伴
$this->beginBlock('/blocks/block', array(
	'image' => $this->asset('images/block_tit_partners.jpg')
));
	echo '<ul>';
foreach ($this->getFriendLinksByChannelId(57, 14) as $link) {
	echo sprintf('<li><a href="%s" target="_blank"><img src="%s" /></a></li>',
		$link['url'],
		$link['logo']
	);
}
	echo '</ul>';
$this->endBlock();
?>
		
	</div>
	
	<div class="c3r">
		<div class="block_btn">
			<a href="<?php echo $this->createUrl('archive/detail', array('id' => 10));?>" target="_blank"><img src="<?php echo $this->asset('images/btn_telephone.jpg')?>" /></a>
			<a href="http://rjy.nbhtz.gov.cn:81/abc/Login.aspx" target="_blank"><img src="<?php echo $this->asset('images/btn_login.jpg')?>" /></a>
			<a href="mailto: yuhj@nbhtz.gov.cn"><img src="<?php echo $this->asset('images/btn_mailbox.jpg')?>" /></a>
		</div>
	</div>
	
</div>