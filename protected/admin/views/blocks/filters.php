<style type="text/css">
    .table-filters { border-bottom: 1px solid #CCCCCC; padding: 5px 0; margin: 0 0 10px 0;}
    .table-filters label { font-size: bold; display: inline-block; margin-right: 10px;}

</style>

<?php
if ($filters) {
    echo '<form class="table-filters" method="get">';
    echo '<input type="hidden" name="r" value="' . $_GET['r'] .'" />';
    echo '<input type="hidden" name="cid" value="' . $_GET['cid'] .'" />';
    echo '<label>筛选: </label>';
    echo implode("\n", $filters) . "\n";
    echo '<input type="submit" value="搜索" class="button" /></form>';
}