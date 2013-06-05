<?php $this->beginContent('//layouts/main'); ?>
<style type="text/css">
body { background-image:none;}
</style>

<div id="body-wrapper">
		
		<?php if (Yii::app()->user->hasFlash('message')) {
			$message = Yii::app()->user->getFlash('message');
			$this->beginWidget('zii.widgets.CPortlet', array('skin' => $message['state']));
			echo '<a class="close" href="#"><img alt="close" title="关闭" src="'.$this->asset('images/icons/cross_grey_small.png').'"></a>';
			echo  $message['content'];
			$this->endWidget();
		}?>
		
		<div class="clear"></div>
		
		<?php echo $content; ?>
		
</div>
<?php $this->endContent(); ?>