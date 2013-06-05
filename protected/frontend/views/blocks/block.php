<?php
/**
 * $id: string div块的ID属性
 * $more string optional 更多页的URL
 * $image string optional 标题区显示的图片URL
 * $tabs: array optional 选项卡信息数据，格式array( 选项卡ID=>array(
 * 		'label' => 标签名,
 * 		'actived' => bool是否激活,
 * 		'url' => optional 跳转到其他URL
 * ) )
 * 注意：在设置了$tabs后，要手动增加对应的内容DIV，
 * <div class="tab-content" id="tab-选项卡ID">对应选项卡的内容</div>
 */
?>
<div class="block"<?php if ( isset($id) ) echo ' id="'.$id.'"'?>>
	<div class="hd">
		<div class="hdwrap">
			<div class="tit">
<?php
if ( $tabs ) {
	echo '<ul class="tabs">';
	foreach ( $tabs as $id => $tab ) {
		if ( !is_array($tab) )
			$tab = array('label' => $tab);
		echo sprintf('<li class="%s"><a href="%s" target="_blank">%s</a></li>',
			$tab['actived'] ? 'actived' : '',
			$tab['url'] ? $tab['url'] : '#' . $id,
			$tab['label']
		);
	}
	echo '</ul>';
} if ( $image ) {
	echo '<img class="titimg" src="'.$image.'" />';
}
?>
			</div>
<?php
if ( $more ) {
	echo '
<div class="more">
	<a href="'.$more.'" target="_blank"><img src="' . $this->asset('images/more.gif') .'" /></a>
</div>
	';
}
?>
		</div>
	</div>
	<div class="bd">
		<div class="bdwrap">