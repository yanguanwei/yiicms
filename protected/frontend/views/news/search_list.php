<script type="text/javascript">
    $(function() {
        var key = '<?php echo $key;?>';
        if ( key ) {
            $('.news-list').find('a').each(function() {
                $(this).html( $(this).text().replace(key, '<b style="color:#f00">' + key + '</b>') );
            });
        }
    });
</script>
<div class="breadcrumb">
    <a href="/">首页</a>&lt;<a>搜索</a>&lt;<a class="current"><?php echo $_GET['key']?></a>
</div>
<div class="news clearfix">
    <div class="news-wrap fl">
        <?php
        echo $this->renderPartial('/news/newslist_block', array(
                'archives' => $archives,
                'hasPostTime' => true,
                'class' => 'news-list'
            ));?>

        <?php $this->renderPager($total, 'flickr');?>
    </div>
</div>