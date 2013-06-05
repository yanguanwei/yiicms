<?php
class ArchiveController extends FrontendController
{
	public function actionDetail($id)
	{
		$template = Archive::getArchiveTemplate($id);
		
		if ( !$template )
			throw new CHttpException(404);

		$this->subTitle = Archive::getArchiveTitle($id);
		$this->activeNavKey = ChannelAlias::getChannelAlias(Channel::getTopChannelId(Archive::getChannelId($id)));
		
		return $this->render($template, array(
			'archive_id' => $id
		));
	}
	
	public function actionSearch($key)
	{
		$keystr = strtr($key, array("\\" => "\\\\", '_' => '\_', '%' => '\%', "'" => "\\'"));
		
		$f = "FR"."OM {{archive}} WHE"."RE model_id in('1') AND status='".Archive::STATUS_PUBLISHED."' AND `title` LIKE '%{$keystr}%' ORD"."ER BY update_time DESC, id DESC";
		
		$total = Yii::app()->db->createCommand("SE"."LECT count(id) ".$f)->queryScalar();
	
		$page = intval($_GET['page']);
		$page = $page ? $page : 1;
		$pageSize = 20;
		$offset = ($page-1)*$pageSize;
		$sql = "SE"."LECT * {$f} LIMIT {$offset}, {$pageSize}";
		
		$archives = Yii::app()->db->createCommand($sql)->queryAll();
		
		return $this->render('/news/search_list', array(
				'archives' => $archives,
				'total' => $total,
				'key' => $key,
				'pageSize' => $pageSize
			));
	}
}
?>