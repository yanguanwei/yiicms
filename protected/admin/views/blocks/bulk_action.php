<?php 
$name = isset($name) ? $name : 'id';
$options = isset($options) ? $options : array();
$hasOrder = isset($hasOrder) ? $hasOrder : false;
?>
<script type="text/javascript">
$(function() {
	var $form = $('#bulk-actions-form');

	<?php if ( $options ) { ?>
	$('#bulk-actions-button').click(function() {
		var hasSelected = false;
		$('input:checkbox[name="<?php echo $name?>[]"]').each(function() {
			if( $(this).attr("checked") ){  
				hasSelected = true;
				$form.append('<input type="hidden" name="<?php echo $name?>[]" value="'+$(this).val()+'" />');
			}
		});
		
		if ( hasSelected ) {
			if ( !confirm('确定要执行该操作吗？') ) {
				return false;
			}
			
			var action = $('#bulk-actions-select').val();
			if ( action ) {
				$form.attr('action', action);
				$form.submit();
			} else {
				alert('请选择操作！');
			}
		} else {
			alert("请选择要操作的选项！");
		}
	});
	<?php }?>

	<?php if ( $hasOrder ) {?>
	$('#bulk-action-sort').click(function() {
		$('.sort_id').each(function() {
			$(this).clone().appendTo($form);
		});

		$form.attr('action', '<?php echo $this->createUrl('updateSort');?>');
		$form.submit();
		return false;
	});
	<?php }?>
});
</script>
<div class="bulk-actions">
<?php if ($options) {?>
	<select id="bulk-actions-select">
		<option value="">请选择操作</option>
<?php 
foreach ($options as $key => $value)
	echo sprintf('<option value="%s">%s</option>', $value, $key);
?>
	</select>
	<a href="javascript:;" id="bulk-actions-button" class="button">应用</a>
<?php }?>
	<?php if ( $hasOrder ) {?>
	<a href="javascript:;" id="bulk-action-sort" class="button">保存排序</a>
	<?php }?>
</div>

<form method="post" id="bulk-actions-form" style="display:none">
	<input type="submit" />
</form>