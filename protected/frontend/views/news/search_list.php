<?php
/**
 * 文档搜索结果分页列表
 * 
 * $archives array 文档列表数组
 * $key string 搜索关键字
 * $total int 总记录数
 * $pageSize int 每页记录数
 */

$top_id = 18;	//新闻资讯
?>
<div class="layout">

	<div class="c2l" id="sidebar">
		<?php $this->renderPartial('/channel/sidebar', array(
			'channel_id' => $top_id
		));?>
	
		<?php $this->renderPartial('/sidebars/contactus');?>
	</div>
	
	<div class="c2r" id="mainContent">
		<div class="ad">
			<img src="<?php echo $this->asset('images/ad.jpg')?>" />
		</div>
		
		<div class="hd">
			<div class="position">
				<label>您当前所在的位置：</label>
				<a href="<?php echo $this->createUrl('site/index')?>">首页</a>-
				<a href="#">搜索结果</a>
			</div>
			<div class="tit">
				<h2><?php echo $key?></h2>
			</div>
		</div>
<script type="text/javascript">
$(function() {
	var key = '<?php echo $key;?>';
	if ( key ) {
		$('.newslist').find('a').each(function() {
			$(this).html( $(this).text().replace(key, '<b style="color:#f00">' + key + '</b>') );
		});
	}
});
</script>
		<div class="bd">
<?php
if ( $archives ) {
	$this->renderPartial('/blocks/newslist', array(
		'data' => $archives,
		'hasPostTime' => true
	));

	$this->widget('apps.ext.young.Pager', array(
		'style' => 'digg',
		'total' => $total,
		'pagesize' => $pageSize
	));
} else {
	echo '<h3 style="margin:10px 0; text-align:center; font-size:14px; color:#f00;">找不到关键字为 '.$key.' 的信息！</h3>';
}
?>
		</div>
		
	</div>
	
</div>