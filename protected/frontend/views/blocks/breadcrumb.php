<?php
$breadcrumb = array(
    '<a href="/">首页</a>',
    '<a href="' . $this->createChannelUrl($top_id) . '" class="current">'. $this->getChannelTitle($top_id) .'</a>'
);

if (isset($channel_id)) {
    $breadcrumb[] = '<a href="' . $this->createChannelUrl($channel_id) . '" class="current">'. $this->$channel_id($top_id) .'</a>';
}

?>
<div class="breadcrumb">
    <?php echo implode('&lt;', $breadcrumb)?>
</div>