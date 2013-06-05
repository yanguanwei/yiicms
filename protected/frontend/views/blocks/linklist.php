<?php 
/**
 * $data array 友情链接列表数组，格式：
 * array(
 * 	array(
 * 		'url' => string 网址,
 * 		'title' => string 网站名称
 * 	)
 * )
 */
?>
<div class="linklist">
<ul>
<?php
foreach ( $data as $row) {
	echo sprintf('<li><a href="%s" target="_blank" title="%s">%s</a></li>',
		$row['url'],
		$row['title'],
		$row['title']
	);
}
?>
</ul>
</div>