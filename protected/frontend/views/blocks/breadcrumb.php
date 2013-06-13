<?php
$breadcrumb = array(
    '<a href="/">首页</a>',
    '<a href="' . $this->createChannelUrl($topChannel->id) . '" class="current">'. $topChannel->title .'</a>'
);

if (isset($channel) && $channel->id != $topChannel->id) {
    $breadcrumb[] = '<a href="' . $this->createChannelUrl($channel->id) . '" class="current">'. $channel->title .'</a>';
}

?>
<div class="breadcrumb">
    <?php echo implode('&lt;', $breadcrumb)?>
</div>