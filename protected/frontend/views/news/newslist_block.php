<ul class="<?php echo isset($class) ? $class : ''; ?>">
    <?php
    foreach ($archives as $archive) {
        echo sprintf(
            '<li>%s<a href="%s" class="%s" target="_blank">%s</a></li>',
            $hasPostTime ? sprintf('<span class="fr time">%s</span>', date('Y-m-d H:i', $archive['update_time'])) : '',
            $this->createArchiveUrl($archive['id']),
            $archive['is_highlight'] ? 'highlight' : '',
            $archive['title']
        );
    }
    ?>
</ul>