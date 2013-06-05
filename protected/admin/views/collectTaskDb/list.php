<?php
$widget = $this->beginWidget('application.widgets.Tabs', array(
		'title' => $title,
		'tabs' => array(
				'base' => array('label' => '任务列表')
		),
		'defaultTab' => 'base'
));

$widget->beginTab('base');
?>

<script type="text/javascript">

var collect = function( id, url, offset ) {
	offset = offset || 0;
	var newurl = url + (url.indexOf('?') >= 0 ? '&' : '?') + 'offset=' + offset + '&_t=' + new Date().getTime();
	var $tr = $('#collect_' + id);
	
	$.ajax({
		dataType: 'json',
		url: newurl,
		success: function( response ) {
			if ( response.status == 'error' ) {
				alert(response.message);
				$tr.find('.collect').text('采集');
				$(this).attr('disabled', false);
			} else if ( response.status == 'success' ) {
				if ( response.next ) {
					$tr.find('.count').text(response.count + '/' + response.total);
					collect(id, url, response.offset);
				} else {
					$tr.find('.count').text(response.count);
					$tr.find('.collect').text('采集');
					$(this).attr('disabled', false);
				}
			}
		},
		error: function() {
			alert('请求错误！');
			$tr.find('.collect').text('采集');
			$(this).attr('disabled', false);
		}
	});

}

$(function(){
	$('.collect').each(function() {
		$(this).click(function() {
			var url = $(this).attr('href');
			$(this).text('正在采集...');
			$(this).attr('disabled', true);
			collect( $(this).attr('id'), url );
			return false;
		});
	});
});
</script>
<table class="table">
	<thead>
		<th style="text-align:center; width:10px;">ID</th>
		<th style="text-align:center;">任务名</th>
		<th style="text-align:center; width:120px;">已采集数</th>
		<th style="text-align:center; width:120px;">采集</th>
		<th style="text-align:center; width:120px;">更新时间</th>
		<th style="text-align:center; width:80px;">操作</th>
	</thead>
	
	<tbody>
<?php
foreach ($data as $row) {
	$collecturl = $this->createUrl($row['collect'], array('id' => $row['id']));
	$updateurl = $this->createUrl('update', array('id' => $row['id']));
	$deleteurl = $this->createUrl('delete', array('id' => $row['id']));
	echo <<<code
		<tr id="collect_{$row['id']}">
			<td style="text-align:center;">{$row['id']}</td>
			<td style="text-align:center;">{$row['title']}</td>
			<td class="count" style="text-align:center;">{$row['count']}</td>
			<td style="text-align:center;">
				<a href="{$collecturl}" class="collect" id="{$row['id']}">采集</a>
			</td>
			<td style="text-align:center;">{$row['update_time']}</td>
			<td style="text-align:center;">
				<a href="{$updateurl}">编辑</a>
				<a href="{$deleteurl}" class="delete">删除</a>
			</td>
		</tr>
code;
}
?>
	</tbody>
</table>

<?php
$widget->endTab();

$this->endWidget();
?>