<?php $this->beginContent('//layouts/main'); ?>
<div id="body-wrapper">
	<div id="sidebar">
		<div id="sidebar-wrapper">
			<!-- Sidebar with logo and menu -->
			<h1 id="sidebar-title"><a href="#"><?php echo Yii::app()->name;?></a></h1>
			<!-- Logo (221px wide) -->
			<a href="<?php echo Yii::app()->getRequest()->getHostInfo();?>"><img id="logo" src="<?php echo $this->asset('images/logo.png')?>" alt="<?php echo Yii::app()->name?>" /></a>
			<!-- Sidebar Profile links -->
			<div id="profile-links">你好,&nbsp;
				<a href="<?php echo $this->createUrl('user/update', array('id'=>Yii::app()->user->getId()))?>">
					<?php echo Yii::app()->user->getName()?>
				</a>
				<br />
				<br />
				<a href="<?php echo $this->createUrl('user/update', array('id'=>Yii::app()->user->getId()))?>">修改密码</a>&nbsp;|&nbsp;
				<a href="<?php echo $this->createUrl('site/logout')?>">退出</a>
			</div>
			
			<?php
			$this->widget('application.widgets.Navigations', array(
				'items' => $this->getNavigations(),
				'htmlOptions' => array('id' => 'main-nav'),
				'currentItemKey' => $this->navigationCurrentItemKey
			));
			?>
			<!-- End #main-nav -->
		</div>
	</div>

	<div id="main-content">
		<?php
		$contentTitle = $this->contentTitle;
		if ( $contentTitle )
			echo "<h2>{$contentTitle}</h2>";
		
		$prompts = $this->getPrompts();
		if ( $prompts )
			$this->renderPartial('/blocks/prompts', array(
				'prompts' => $prompts
			));
		
		$shortcuts = $this->getShortcuts();
		if ($shortcuts) {
			$this->widget('application.widgets.Shortcuts', array(
				'buttons' => $shortcuts
			));
		}
		?>
		<div class="clear"></div>
		
		<?php if (Yii::app()->user->hasFlash('message')) {
			$message = Yii::app()->user->getFlash('message');
			$this->beginWidget('zii.widgets.CPortlet', array('skin' => $message['state']));
			echo '<a class="close" href="#"><img alt="close" title="关闭" src="'.$this->asset('images/icons/cross_grey_small.png').'"></a>';
			echo  $message['content'];
			$this->endWidget();
		}?>
		
		<div class="clear"></div>
		
		<?php echo $content; ?>
		
		<div class="clear"></div>
		
		<div id="footer">
			<small>
				&nbsp;&#169;&nbsp;Copyright 2013&nbsp;
				<a href="<?php echo Yii::app()->params['url']?>"><?php echo Yii::app()->params['name']?></a>&nbsp;|&nbsp;
				Powered by <a href="http://www.vanthink.net/" target="_blank">浙江凡想科技有限公司</a>&nbsp;|&nbsp;
				<a href="#">Top</a>
			</small>
		</div>
	</div>
</div>
<?php $this->endContent(); ?>