<?php
echo $this->renderPartial('/blocks/breadcrumb', array(
        'topChannel' => $topChannel,
        'channel' => $channel
    ));
?>

<!-- 促销分类 -->
<div class="promotion-sort">
    <div class="sort-wrap">
        <?php
        $types = $channel->getTagTypes();
        if ($types) {
            foreach (Tag::fetchByTypes(array_keys($types)) as $type => $tags) {
                echo sprintf('<dl class="clearfix"><dt class="fl">%s：</dt><dd>', $types[$type]);
                $tagLinks = array();
                $tagLinks[] = sprintf(
                    '<a href="%s">%s</a>',
                    $channel->getTagViewUrl($type, 0),
                    '所有'
                );
                foreach ($tags as $tid => $title) {
                    $tagLinks[] = sprintf(
                        '<a href="%s" class="%s">%s</a>',
                        $channel->getTagViewUrl($type, $tid),
                        isset($_GET[$type]) ? ($_GET[$type]==$tid ? 'actived' : '') : '',
                        $title
                    );
                }
                echo implode('|', $tagLinks);
                echo '</dd></dl>';
            }
        }
        ?>
        <span class="link"><a href="#"><img src="images/promotion-sort-link.png" /></a></span>
    </div>
</div>
<!--促销列表-->
<div class="promotion-list">
    <ul class="clearfix">
        <?php
        $join = array(
            'promotion' => array(
                'on' => array('id' => 'id'),
                'fields' => array('discounts', 'start_time', 'end_time')
            )
        );

        list($archives, $total) = $this->getArchivesForPagingByChannel($channel, 12, $join);

        foreach ($archives as $archive) {
            echo sprintf(
                '<li><div class="pic"><a href="%s"><img src="%s" /></a></div><div class="title"><span class="name">%s</div><div class="other clearfix"><span class="discount fl">%s</span>%s</div></li>',
                $this->createUrl("archive/detail", array('id' => $archive['id'])),
                $archive['cover'], $archive['title'], $archive['discounts'] ? ($archive['discounts'] . '折') : '',
                date('m月d日', $archive['start_time']) . ' ~ ' . date('m月d日', $archive['end_time'])
            );
        }
        ?>
    </ul>
</div>