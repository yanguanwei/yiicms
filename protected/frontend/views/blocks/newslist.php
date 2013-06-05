<?php 
/**
 * $hasPostTime: bool 是否显示时间
 * $data array 文档列表数组，格式：
 * 	array(
 * 		array(
 * 			'title' => string 标题,
 * 			'is_highlight' => bool 是否高亮,
 * 			'update_time' => int 时间戳
 * 		)
 * 	)
 */
?>
<div class="newslist">
<ul>
<?php
foreach ( $data as $row) {
	echo sprintf('<li class="%s"><p><a href="%s" target="_blank" title="%s" class="%s">%s</a></p>%s</li>',
		$hasPostTime ? 'time' : '',
		$this->createUrl('archive/detail', array('id' => $row['id'])),
		$row['title'],
		$row['is_highlight'] ? 'highlight' : '',
		$row['title'],
		$hasPostTime ? sprintf( '<span>%s</span>', date('Y-m-d', $row['update_time']) ) : ''
	);
}
?>
</ul>
</div>