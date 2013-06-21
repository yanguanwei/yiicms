<?php
$breadcrumb = array(
    '<a href="/">首页</a>',
    '<a href="' . $this->createChannelUrl($topChannel->id) . '">'. $topChannel->title .'</a>'
);

if (!isset($current)) {
    $tags = array();
    foreach ($channel->tags as $type) {
        if (isset($_GET[$type]) && $_GET[$type]) {
            $tags[] = $this->getTagTitle($_GET[$type]);
        }
    }
    $current = implode(' & ', $tags);
}

if (isset($channel) && $channel->id != $topChannel->id) {
    $breadcrumb[] = sprintf(
        '<a href="%s"%s>%s</a>',
        $this->createChannelUrl($channel->id),
        $current ? '' :  ' class="current"',
        $channel->title
    );
}

if ($current) {
    $breadcrumb[] = '<a class="current">'.$current.'</a>';
}
?>
<div class="breadcrumb">
    <?php echo implode('&lt;', $breadcrumb)?>
</div>