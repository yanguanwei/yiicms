<div class="news">
	<div class="tit">
		<h3><?php echo $archive['title']?></h3>
		<p>
			<label>发布日期：</label><?php echo date('Y-m-d H:i', $archive['update_time'])?>
			<label>访问次数：</label><?php echo $archive['visits']?>
			<?php if ( $news['source'] ) {?>
				<label>来源：</label><?php echo $news['source']?>
			<?php }?>
			<label>字体大小：</label>【<a href="javascript:setFontSize('#news-content', 16);">大</a>
			<a href="javascript:setFontSize('#news-content', 14);">中</a>
			<a href="javascript:setFontSize('#news-content', 12);">小</a>】
		</p>
	</div>
				
	<div class="cnt" id="news-content">
		<?php echo $news['content']?>
	</div>
</div>