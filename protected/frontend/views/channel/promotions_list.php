<div class="promotion-list">
  <ul class="clearfix">
    <?php
    list( $archives, $total ) = $this->getArchivesForPagerByChannelIdWithTags($channel_id, 12);
    foreach ( $archives as $row ) {
      echo sprintf('
    <li>
      <div class="pic"><a href="%s"><img src="%s" /></a></div>
      <div class="title"><span class="name">银泰百货</span>满<em class="color-red">1000</em>元减<em class="color-red">200</em>元</div>
      <div class="other clearfix"><span class="discount fl">4.2折</span>07月12日~07月15日</div>
    </li>',
        $this->createUrl('archive/' . $row['id']),
        $row['cover'],
        $row['title'],
        $row['description'],
        $row['cover']
      );
    }
    ?>
    <li>
      <div class="pic"><a href="#"><img src="images/promotion-list-img.png" /></a></div>
      <div class="title"><span class="name">银泰百货</span>满<em class="color-red">1000</em>元减<em class="color-red">200</em>元</div>
      <div class="other clearfix"><span class="discount fl">4.2折</span>07月12日~07月15日</div>
    </li>
  </ul>
</div>

<?php
$this->renderPager($total, 'digg');
?>