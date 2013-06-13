<?php
/**
 * $class 样式
 * $hasPostTime 是否显示时间
 * $data array 文档列表数组，格式：
 *  array(
 *    array(
 *      'title' => string 标题,
 *      'is_highlight' => bool 是否高亮,
 *      'update_time' => int 时间戳
 *    )
 *  )
 */
?>
<ul class="<?php echo isset($class) ? $class : ''; ?>">
    <?php
    foreach ($archives as $archive) {
        echo sprintf(
            '<li><a href="%s" class="%s">%s%s</a></li>',
            $this->createUrl('archive/detail', array('id' => $archive['id'])),
            $archive['is_highlight'] ? 'highlight' : '',
            $hasPostTime ? sprintf('<span class="fr time">%s</span>', date('Y-m-d H:i', $archive['update_time'])) : '',
            $archive['title']
        );
    }
    ?>
</ul>