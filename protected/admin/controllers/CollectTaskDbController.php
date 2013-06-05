<?php
class CollectTaskDbController extends AdminController
{
	public $navigationCurrentItemKey = 'system/collect';
	
	public function actionIndex($type_id = null)
	{
		$sql = "LECT id, title, data, cid, update_time FROM {{collect_task}}";
		
		if ( $type_id !== null )
			$sql .= " WHERE type_id='{$type_id}'";
		
		$data = Yii::app()->db->createCommand("SE" . $sql)->queryAll();
		
		foreach ($data as &$row) {
			if ( $row['data'] )
				$row['data'] = explode(',', $row['data']);
			else 
				$row['data'] = array();
			
			$row['count'] = count($row['data']);
			$row['update_time'] = date('Y-m-d H:i', $row['update_time']);
			
			switch ( Channel::getChannelModelId($row['cid'])) {
				case 2:
					$collect = 'collectLink';
					break;
				default:
					$collect = 'collect';
			}
			$row['collect'] = $collect;
		}
		
		return $this->render('list', array(
				'data' => $data	
			));
	}
	
	public function actionCreate($type)
	{
		$form = new CollectTaskDbForm();
		$form->setType($type);
		
		if ( isset($_POST['CollectTaskDbForm']) ) {
			if ( $form->post($_POST['CollectTaskDbForm'], true) ) {
				$this->setFlashMessage('success', '创建数据库采集任务成功！点击<a href="'.$this->createUrl('create').'">继续创建</a>');
				return $this->redirect(array('index', 'type_id' => $form->type_id));
			} else {
				$this->setFlashMessage('error', '创建失败！');
			}
		}
		
		return $this->render('//form_template', array(
				'model' => $form,
				'title' => '创建数据库采集任务',
				'view' => $form->getFormView()
			));
	}
	
	public function actionUpdate($id)
	{
		$model = CollectTask::model()->findByPk($id);
		if ( !$model )
			throw new CHttpException(404);
		
		$form = new CollectTaskDbForm();
		$form->setTypeByChannelModelId(Channel::getChannelModelId($model->cid));
		
		if ( isset($_POST['CollectTaskDbForm']) ) {
			if ( $form->post($_POST['CollectTaskDbForm'], false) ) {
				$this->setFlashMessage('success', '更新数据库采集任务成功！');
				return $this->redirect(array('index', 'type_id' => $form->type_id));
			} else {
				$this->setFlashMessage('error', '更新失败！');
			}
		} else {
			
			$form->setAttributes( $model->getAttributes(), false );
		}
		
		return $this->render('//form_template', array(
				'model' => $form,
				'title' => '更新数据库采集任务',
				'view' => $form->getFormView()
		));
	}
	
	public function actionCollectLink($id, $offset = 0)
	{
		$count = 50;
		$result = CollectTask::collectForLinkByDb($id, $offset, $count);
		$this->doCollect($result, $count);
	}
	
	public function actionCollect($id, $offset = 0)
	{
		$count = 50;
		$result = CollectTask::collectForNewsByDb($id, $offset, $count);
		$this->doCollect($result, $count);
	}
	
	protected function doCollect($result, $count)
	{
		$response = array();
		
		if ( is_string($result) ) {
			$response['status'] = 'error';
			$response['message'] = $result;
		} else if ( is_array($result) ) {
			list($total, $insertCount) = $result;
			$offset = $offset + $count;
			$response['status'] = 'success';
			$response['next'] = $offset < $total ? true : false;
			$response['count'] = $insertCount;
			$response['offset'] = $offset;
			$response['total'] = $total;
		} else {
			$response['status'] = 'error';
			$response['message'] = '未知错误！';
		}
		
		echo CJSON::encode($response);
	}
	
	public function actionDelete($id)
	{
		$id = intval($_GET['id']);
		$model = CollectTask::model()->findByPk($id);
		
		if ( !$model )
			throw new CHttpException(404);
		
		$count = $model->delete();
		
		$this->setFlashMessage(success, "共删除 {$count} 项任务！");
		
		$url = Yii::app()->getRequest()->getUrlReferrer();
		if ( $url )
			$this->redirect($url);
	}
	
	public function getShortcuts()
	{
		return array(
			array(
				'shortcut' => $this->asset('images/icons/favorite.png'),
				'label' => '采集任务列表',
				'url' => $this->createUrl('index')
			),
			array(
				'shortcut' => $this->asset('images/icons/add.png'),
				'label' => '创建新闻采集',
				'url' => $this->createUrl('create', array('type' => CollectTaskDbForm::TYPE_ARCHIVE))
			),
			array(
				'shortcut' => $this->asset('images/icons/add.png'),
				'label' => '创建友链采集',
				'url' => $this->createUrl('create', array('type' => CollectTaskDbForm::TYPE_LINK))
			)
		);
	}
}
?>