<?php
Yii::app()->getClientScript()
->registerCssFile($this->asset('css/reset.css'))
->registerCssFile($this->asset('css/style.css'))
->registerCssFile($this->asset('css/invalid.css'))
->registerCssFile($this->asset('css/custom.css'))
->registerCssFile($this->asset('css/login.css'))
->registerScriptFile($this->asset('scripts/simpla.jquery.configuration.js'))
->registerScriptFile($this->asset('scripts/fancybox/jquery.fancybox.js'))
->registerScriptFile($this->asset('scripts/fancybox/jquery.easing-1.3.pack.js'))
->registerScriptFile($this->asset('scripts/fancybox/jquery.mousewheel.js'))
->registerCssFile($this->asset('scripts/fancybox/jquery.fancybox.css'));

$this->pageTitle = '用户登录 - ' . Yii::app()->name;
?>
<script type="text/javascript">
$(function() {
	$('#login').height($(document).height());
})
</script>
<div id="login">
<div id="login-wrapper" class="png_bg">
	<div id="login-top">
		<h1><?php echo Yii::app()->name?></h1>
		<!-- Logo (221px width) -->
		<img id="logo" src="<?php echo $this->asset('images/logo.png')?>" alt="<?php echo Yii::app()->name?>" />
	</div>
		<!-- End #logn-top -->
	<div id="login-content">
		<?php 
		if (Yii::app()->user->hasFlash('message')) {
			$message = Yii::app()->user->getFlash('message');
			$this->beginWidget('zii.widgets.CPortlet', array('skin' => $message['state']));
			echo '<a class="close" href="#"><img alt="close" title="关闭" src="'.$this->asset('images/icons/cross_grey_small.png').'"></a>';
			echo  $message['content'];
			$this->endWidget();
		}?>
		
		<?php $form=$this->beginWidget('CActiveForm'); ?>
		<?php if (Yii::app()->user->hasFlash('message')) {
			$message = Yii::app()->user->getFlash('message');
			$this->beginWidget('zii.widgets.CPortlet', array('skin' => $message['state'])); 
			echo  $message['content'];
			$this->endWidget();
		}?>
		<input type="hidden" name="return" value="<?php echo $return;?>" />
		<div class="row">
			<?php echo $form->labelEx($model,'username'); ?>
			<div class="yf"><?php echo $form->textField($model,'username', array('class' => 'text-input')); ?></div>
			<?php echo $form->error($model,'username'); ?>
		</div>
		
		<div class="clear"></div>
		<div class="row">
			<?php echo $form->labelEx($model,'password'); ?>
			<div class="yf"><?php echo $form->passwordField($model,'password', array('class' => 'text-input')); ?></div>
			<?php echo $form->error($model,'password'); ?>
		</div>

	
		<div class="clear"></div>
		
		<div id="submit">
			<p id="remember-password">
				<?php echo $form->checkBox($model,'rememberme'); ?>  记住我 
			</p>
			<?php echo CHtml::submitButton('登录', array('class' => 'button')); ?>
		</div>
		
		<?php $this->endWidget(); ?>
	</div>
		<!-- End #login-content -->
</div>
<!-- End #login-wrapper -->
</div>