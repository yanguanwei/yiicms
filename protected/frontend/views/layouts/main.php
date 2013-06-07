<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="<?php echo $this->getConfig('description');?>" />
<meta name="keywords" content="<?php echo $this->getConfig('keywords');?>" />
<title><?php echo CHtml::encode($this->pageTitle); ?></title>

<script type="text/javascript" src="<?php echo $this->assets('js/pptBox.js'); ?>"></script>
<script type="text/javascript" src="<?php echo $this->assets('js/jquery-extend-AdAdvance.js'); ?>"></script>
</head>

<body>
<div class="header">
	<div class="header-wrap header-index clearfix">
		<div class="search clearfix"><input type="text" value="搜索购物节活动" class="input-txt fl" /><input type="button" value="搜索" class="input-btn fl" /></div>
	</div>
</div>
<div class="navigation clearfix">
	<span class="nav-left fl">&nbsp;</span>
	<ul class="fl">
		<li class="goShopping fr"><a href="#">走进购物节</a></li>

    <?php
    $navs = $this->getMainNavigations();
    if ( $navs && $navs[0] ) {
      $navs_num = count($navs[0]) - 1;
      foreach ( $navs[0] as $i => $nav) {
        $nav['class'] = array();
        if ( $i === 0 )
          $nav['class'][] = 'first';
        else if ( $i === $navs_num ) {
          $nav['class'][] = 'last';
        }

        if ( $nav['active'] )
          $nav['class'][] = 'active';

        $nav['class'] = implode(' ', $nav['class']);
        echo sprintf(
          '<li class="%s"><a href="%s">%s</a></li>',
          $nav['class'], $nav['url'], $nav['title']
        );
      }
    }
    ?>
	</ul>
	<span class="nav-right fr">&nbsp;</span>
</div>
<div class="container">

  <?php echo $content; ?>

	<div class="footer clearfix">
		<img class="fl" src="<?php echo $this->asset('images/footer-bg.png')?>" /><div class="footer-box fl">
主办单位：宁波市人民政府<br />
承办单位：宁波市贸易局、宁波市旅游局、海曙、江东、江北、鄞州、北仑、镇海六区政府<br />
技术支持：宁波凡想科技有限公司  <?php echo $this->getConfig('icp');?></div>
	</div>
</div>
</body>
</html>