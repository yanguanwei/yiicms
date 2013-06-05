<?php
/**
 * $channelId int 父栏目的ID
 */
?>
<div class="list">
	<ul>
	<?php
	foreach ( $this->getSubChannels($channel_id) as $id => $title) {
		echo sprintf('<li><a href="%s">%s</a></li>',
			$this->createChannelUrl($id),
			$title
		);
	}
	?>
	</ul>
</div>