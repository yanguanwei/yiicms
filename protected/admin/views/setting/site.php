<?php $widget = $this->beginWidget('admin.widgets.Tabs', array(
	'title' => '配置',
	'tabs' => array(
		'base' => array('label' => '网站信息')
	),
	'defaultTab' => 'base'
));  ?>
	<?php $widget->beginTab('base');?>
	
	<?php 
	$form=$this->beginWidget('CActiveForm');
	?>

	<?php echo $form->errorSummary($model); ?>
	
	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name', array('class' => 'text-input medium-input')); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title', array('class' => 'text-input medium-input')); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'url'); ?>
		<?php echo $form->textField($model,'url', array('class' => 'text-input medium-input')); ?>
		<?php echo $form->error($model,'url'); ?>
	</div>
	
	<div class="row">
		<div class="column-left">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textField($model,'address', array('class' => 'text-input large-input')); ?>
		<?php echo $form->error($model,'address'); ?>
		</div>
		<div class="column-right">
		<?php echo $form->labelEx($model,'zipcode'); ?>
		<?php echo $form->textField($model,'zipcode', array('class' => 'text-input small-input')); ?>
		<?php echo $form->error($model,'zipcode'); ?>
		</div>
	</div>
	
	<div class="row">
		<div class="column-left">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone', array('class' => 'text-input large-input')); ?>
		<?php echo $form->error($model,'phone'); ?>
		</div>
		<div class="column-right">
		<?php echo $form->labelEx($model,'fax'); ?>
		<?php echo $form->textField($model,'fax', array('class' => 'text-input large-input')); ?>
		<?php echo $form->error($model,'fax'); ?>
		</div>
	</div>
	
	<div class="row">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email', array('class' => 'text-input medium-input')); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>
	
	
	<div class="row">
		<div class="column-left">
		<?php echo $form->labelEx($model,'keywords'); ?>
		<?php echo $form->textArea($model,'keywords'); ?>
		<?php echo $form->error($model,'keywords'); ?>
		<small>各关键字以半角逗号“,”分隔</small>
		</div>
		<div class="column-right">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description'); ?>
		<?php echo $form->error($model,'description'); ?>
		</div>
	</div>
	
	<div class="row submit">
		<?php echo CHtml::submitButton('提交', array('class' => 'button')); ?>
	</div>

<?php $this->endWidget(); ?>

	<?php $widget->endTab();?>
	
<?php $this->endWidget(); ?>