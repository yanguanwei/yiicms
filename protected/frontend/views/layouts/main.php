<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="description" content="<?php echo $this->getConfig('description');?>" />
<meta name="keywords" content="<?php echo $this->getConfig('keywords');?>" />
<title><?php echo CHtml::encode($this->pageTitle); ?></title>

</head>
<body>

<div id="wrap">
	<div id="header" class="layout">
		<div id="top">
			<div id="logo">
				<a href="<?php echo $this->createUrl('site/index')?>"><img src="<?php echo $this->getConfig('logo');?>" /></a>
			</div>
			
			<div id="topBanner">
				<ul>
					<li class="sethome">
						<a href="javascript:;" onclick="SetHome(this, '<?php echo $this->getConfig('url')?>');">设为首页</a>
					</li>
					<li class="space">|</li>
					<li class="addfavorites">
						<a href="javascript:;" onclick="AddFavorite('<?php echo $this->getConfig('url')?>','<?php echo $this->getConfig('name')?>');">加入收藏</a>
					</li>
				</ul>
				<div id="search">
					<form id="searchForm" action="<?php echo $this->createUrl('archive/search');?>" method="get">
						<input type="hidden" name="r" value="archive/search" />
						<span class="input"><input type="text" id="searchInput" name="key" maxlength="200" value="<?php echo $_GET['key']?>" /></span>
						<input type="submit" class="submit" value="&nbsp;" />
					</form>
				</div>
			</div>
			
		</div>
		
		<div id="nav" class="nav">
			<ul>
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
		</div>
	</div>
	
	<?php echo $content;?>
	
	<div id="friendLinks" class="layout">
		<ul>
		<?php
			foreach (array(52, 53, 54, 55) as $channelId) {
				echo '<li><select><option value="">------' . $this->getChannelTitle($channelId) . '------</option>';
				foreach ($this->getFriendLinksByChannelId($channelId) as $link) {
					echo '<option value="'.$link['url'].'">'.$link['title'].'</option>';
				}
				echo '</select>&nbsp;</li>';
			}
		?>
		</ul>
	</div>
	
	<div id="footer">
		<div class="layout">
			<div id="footerNav">
			<?php
			$navs = $this->getFooterNavigations();
			if ( $navs && $navs[0] ) {
				foreach ( $navs[0] as $i => $nav) {
					if ( $i > 0 )
						echo '<span class="space">|</span>';
					echo sprintf('<a href="%s">%s</a>', $nav['url'], $nav['title']);
				}
			}
			?>
			</div>
			
			<div class="copyright">
				<p>
					招商热线：<?php echo $this->getConfig('hotline');?>
					<span>地址：</span><?php echo $this->getConfig('address');?>
				</p>
				<p>
					ICP备案号：<a href="http://www.miibeian.gov.cn/" target="_blank"><?php echo $this->getConfig('icp');?></a>
					<span>技术支持：</span><a href="http://www.zhongsou.net/" target="_blank">浙江中搜在线信息技术有限公司</a>
				</p>
			</div>
			
		</div>
	</div>

</div>

</body>
</html>
