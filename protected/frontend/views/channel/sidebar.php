<div class="news-sidebar fl">
    <h4 class="title">信息导航</h4>
    <div class="sidebar-wrap">
<?php
foreach ($topChannel->getSubChannels() as $i => $sub) {

    $n = ($i + 1) % 4 + 1;

    echo sprintf(
        '<dl class="sidebar-list sidebar-list%s"><dt><a href="%s">%s</a></dt><dd>',
        $n,
        $sub->getViewUrl(),
        $sub->title
    );

    if ($sub->tags) {
        echo '<ul>';
        foreach (Tag::fetchByTypes($sub->tags) as $type => $tags) {
            foreach ($tags as $tid => $title) {
                echo sprintf('<li><a href="%s">%s</a></li>', $sub->getTagViewUrl($type, $tid), $title);
            }
        }
        echo '</ul>';
    }


    echo '</dd></dl>';
}
?>
    </div>
</div>