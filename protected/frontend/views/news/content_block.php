<div class="news-view-title">
    <h4 class="view-t1"><?php echo $archive->title?></h4>
    <h5 class="view-t2">
        <span><?php echo $archive->update_time?></span>
        <span>查看：<?php echo $archive->visits?></span>
        <?php if ($news->source) {?>
            <label>来自：<?php echo $news->source?></label>
        <?php }?>
    </h5>
</div>
<div class="news-view-details"><?php echo $news->content?></div>