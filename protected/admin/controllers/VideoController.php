<?php
class VideoController extends AdminController
{
	public function actionIndex($cid)
	{
		$cid = intval($cid);
	
		$title = Channel::getChannelTitle($cid);
	
		if ( !$title )
			throw new CHttpException(404);
	
		$criteria =  new CDbCriteria(array(
				'alias' => 'archive',
				'order' => 'archive.update_time DESC, archive.id DESC'
		));
	
		$subs = Channel::getSubChannelTitles($cid);
	
		$ids = array_keys($subs);
		$ids[] = $cid;
		
		Yii::import('apps.ext.young.SelectSQL');
		Yii::import('apps.ext.young.SelectDataProvider');
		
		$sql = new SelectSQL();
		$sql->from('{{archive}}', '*')
		->in('cid', $ids)
		->order('`is_top` DESC, update_time DESC, id DESC');
		
		$dataProvider = new SelectDataProvider(Yii::app()->db, $sql);
	
		$this->render('list', array(
				'dataProvider' => $dataProvider,
				'channel_id' => $cid,
				'channel_topid' => $cid,
				'channels' => $subs,
				'title' => $title
		));
	}
	
	public function actionList($cid)
	{
		$cid = intval($cid);
	
		$channel = Channel::model()->findByPk($cid);
		if ( !$channel )
			throw new CHttpException(404);
	
		//获取一级栏目ID
		$topid = Channel::getTopChannelId($cid);
		if ( $topid == $cid )
			return $this->redirect(array('index', 'cid' => $topid));
	
		$this->navigationCurrentItemKey = 'theme_' . Channel::getThemeId($topid) . '/' . $topid;
	
		$subs = Channel::getSubChannelTitles($channel->parent_id);
	
		Yii::import('apps.ext.young.SelectSQL');
		Yii::import('apps.ext.young.SelectDataProvider');
		
		$sql = new SelectSQL();
		$sql->from('{{archive}}', '*')
		->where('cid=?', $cid)
		->order('`is_top` DESC, update_time DESC, id DESC');
		
		$dataProvider = new SelectDataProvider(Yii::app()->db, $sql);
	
		$this->render('list', array(
				'dataProvider' => $dataProvider,
				'channel_id' => $cid,
				'channel_topid' => $topid,
				'channels' => $subs,
				'title' => Channel::getChannelTitle($topid)
		));
	}
	
	public function actionCreate($cid)
	{
		$form = new ArchiveForm('insert');
		
		if ( isset($_POST['ArchiveForm']) ) {
				
			if ( $form->post($_POST['ArchiveForm'], true) ) {
				$this->setFlashMessage('success', '创建成功！点击<a href="'.$this->createUrl('create', array('cid' => $form->cid)).'">继续创建</a>');
				$this->redirect($this->createUrl('list', array('cid' => $form->cid)));
			} else {
				$this->setFlashMessage('error', "创建失败！");
			}
		} else {
			$form->cid = intval($cid);
		}
		
		$form->update_time = date('Y-m-d H:i', time());
		
		$topid = Channel::getTopChannelId($cid);
		$this->navigationCurrentItemKey = 'theme_' . Channel::getThemeId($topid) . '/' . $topid;
		
		$this->render('//form_template', array(
				'model' => $form,
				'title' => '创建视频'
		));
	}
	
	public function actionUpdate($id)
	{
		$form = new ArchiveForm('update');
	
		if ( isset($_POST['ArchiveForm']) ) {
				
			if ( $form->post($_POST['ArchiveForm'], false) ) {
				$this->setFlashMessage('success', '更新成功！');
				$this->redirect($this->createUrl('list', array('cid' => $form->cid)));
			} else {
				$this->setFlashMessage('error', "更新失败！");
			}
		} else {
			$model = Archive::model()->findByPk($id);
			if ( !$model ) {
				$this->setFlashMessage('error', "没有找到ID为{$id}的记录！");
			}
				
			$form->setAttributes($model->getAttributes(), false);
		}
	
		$topid = Channel::getTopChannelId($model->cid);
		$this->navigationCurrentItemKey = 'theme_' . Channel::getThemeId($topid) . '/' . $topid;
	
		$_GET['cid'] = $form->cid;
	
		$this->render('//form_template', array(
				'model' => $form,
				'title' => '更新视频'
		));
	}

	public function actionDelete()
	{
		if ( isset($_POST['id']) ) {
			$id = $_POST['id'];
		} else {
			$id = intval($_GET['id']);
		}
		
		$count = Archive::deleteArchives($id);
	
		if ( Yii::app()->request->isAjaxRequest ) {
			echo 'ok';
		} else {
			if ( $count ) {
				$this->setFlashMessage('success', "删除成功：共删除 {$count} 条记录！");
			} else {
				$this->setFlashMessage('information', "该记录不存在或已经被删除！");
			}
				
			$url = Yii::app()->getRequest()->getUrlReferrer();
			if ( $url )
				$this->redirect($url);
		}
	}

	public function getShortcuts()
	{
		if ($_GET['cid']) {
			return array(
					array(
							'shortcut' => $this->asset('images/icons/video_list.png'),
							'label' => '视频列表',
							'url' => $this->createUrl('list', array('cid' => $_GET['cid']))
					),
					array(
							'shortcut' => $this->asset('images/icons/video.png'),
							'label' => '创建视频',
							'url' => $this->createUrl('create', array('cid' => $_GET['cid']))
					)
			);
		}
	
		return array();
	}
}
?>