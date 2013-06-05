<?php
class TestController extends FrontendController
{
	public function actionChannel()
	{
		$sql = "SELECT * FROM {{channel}} ORDER BY sort_id DESC, id ASC";
		
		$data = array();
		foreach (Yii::app()->db->createCommand($sql)->queryAll() as $row) {
			$data[$row['parent_id']][] = $row['id'];
		}
		
		header('Content-Type:text/html; charset:utf-8');
		
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
}
?>