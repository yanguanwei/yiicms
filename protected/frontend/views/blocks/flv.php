<?php
/**
 * $channel_id 栏目ID，该栏目下所有的视频
 * $archive_id 视频ID，如果没有指定栏目ID，则只播放该视频；否则优先播放该视频
 * $width 视频宽度
 * $height 视频高度
 * $autoplay 是否自动播放 默认为true
 */
$vcastr_file = array();
$vcastr_title = array();

$autoplay = (int) isset($autoplay) ? $autoplay : 1;
if ( isset($channel_id) && $channel_id ) {
	$videos = $this->getArchivesByChannelId($channel_id);
} else if ( isset($archive_id) && $archive_id ){
	$videos = array(
		$this->getArchive($archive_id)
	);
}
	
foreach ( $videos as $row) {
	if ( $row['id'] == $archive_id ) {
		array_unshift($vcastr_file, $row['cover']);
		array_unshift($vcastr_title, $row['title']);
	} else {
		$vcastr_file[] = $row['cover'];
		$vcastr_title[] = $row['title'];
	}
}
$vcastr_file = implode('|', $vcastr_file);
$vcastr_title = implode('|', $vcastr_title);
?>
<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="<?php echo $width;?>" height="<?php echo $height;?>">
	<param name="movie" value="<?php echo $this->assets('flv_player.swf')?>">
	<param name="quality" value="high">
	<param name="allowFullScreen" value="true" />
	<param name="FlashVars" value="vcastr_file=<?php echo $vcastr_file?>&vcastr_title=<?php echo $vcastr_title?>&vcastr_config=<?php echo $autoplay?>:自动播放|1:连续播放|5:默认音量|0:控制栏位置|3:控制栏显示|#77BB1A:主体颜色|60:主体透明度|0xffcc00:光晕颜色|0xffffff:图标颜色|0xffffff:文字颜色|nb.zhongsou.com:logo文字|nb.zhongsou.com:logo地址|:结束swf地址|1:是否显示时间" />
	<embed src="<?php echo $this->assets('flv_player.swf')?>" allowFullScreen="true" FlashVars="vcastr_file=<?php echo $vcastr_file?>&vcastr_title=<?php echo $vcastr_title?>&vcastr_config=<?php echo $autoplay?>:自动播放|1:连续播放|5:默认音量|0:控制栏位置|3:控制栏显示|#77BB1A:主体颜色|60:主体透明度|0xffcc00:光晕颜色|0xffffff:图标颜色|0xffffff:文字颜色|nb.zhongsou.com:logo文字|nb.zhongsou.com:logo地址|:结束swf地址|1:是否显示时间" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="<?php echo $width;?>" height="<?php echo $height;?>"></embed>
</object>