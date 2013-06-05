<?php 
/**
 * 文档列表侧边栏
 * 
 * $channel_id int 栏目ID
 * $route string optional 路由名，默认为archive/detail
 */
?>
<div class="list">
	<ul>
	<?php
	$route = isset($route) ? $route : 'archive/detail';
	foreach ( $this->getArchivesByChannelId($channel_id) as $archive) {
		echo sprintf('<li><a href="%s">%s</a></li>',
			$this->createUrl($route, array('id' => $archive['id'])),
			$archive['title']
		);
	}
	?>
	</ul>
</div>