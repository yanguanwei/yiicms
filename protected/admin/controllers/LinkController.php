<?php
class LinkController extends AdminController
{
	public function actionIndex($cid)
	{
		$cid = intval($cid);
		
		$title = Channel::getChannelTitle($cid);
		
		if ( !$title )
			throw new CHttpException(404);
		
		$subs = Channel::getSubChannelTitles($cid);
		$cids = array_keys($subs);
		$cids[] = $cid;

		Yii::import('apps.ext.young.SelectSQL');
		Yii::import('apps.ext.young.SelectDataProvider');
		
		$sql = new SelectSQL();
		$sql->from('{{link}}', '*')
		->in('cid', $cids)
		->order('sort_id DESC, id DESC');
		
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
	
		$topid = Channel::getTopChannelId($cid);
	
		if ( $topid == $cid )
			return $this->redirect(array('index', 'cid' => $topid));
	
		$this->navigationCurrentItemKey = 'theme_' . Channel::getThemeId($topid) . '/' . $topid;
	
		$subs = Channel::getSubChannelTitles($channel->parent_id);
		
		Yii::import('apps.ext.young.SelectSQL');
		Yii::import('apps.ext.young.SelectDataProvider');
		
		$sql = new SelectSQL();
		$sql->from('{{link}}', '*')
		->in('cid', $cid)
		->order('sort_id DESC, id DESC');
		
		$dataProvider = new SelectDataProvider(Yii::app()->db, $sql);
	
		$this->render('list', array(
			'dataProvider' => $dataProvider,
			'channel_id' => $cid,
			'channel_topid' => $topid,
			'channels' => $subs,
			'title' => Channel::getChannelTitle($topid)
		));
	}
	
	public function actionUpdateSort()
	{
		return $this->doUpdateSort('link');
	}
	
	public function actionCreate($cid = 0)
	{
		$model = new FriendLink();
	
		if( isset($_POST['FriendLink']) ) {
			$model->setAttributes($_POST['FriendLink'], false);
			$model->id = null;
			if( $model->save() ) {
				$this->setFlashMessage('success', '创建成功！点击<a href="'.$this->createUrl('create', array('cid'=>$cid)).'">继续添加</a>');
				$this->redirect($this->createUrl('list', array('cid' => $model->cid)));
			}
		} else {
			$model->cid = $cid;
			$model->url = 'http://';
		}
		
		$topid = Channel::getTopChannelId($cid);
		$this->navigationCurrentItemKey = 'theme_' . Channel::getThemeId($topid) . '/' . $topid;
		
		$this->render('//form_template', array(
			'model' => $model,
			'title' => '创建友情链接'
		));
	}
	
	public function actionUpdate($id)
	{
		$model = FriendLink::model()->findByPk($id);
		if ( !$model )
			throw new CHttpException(404);
		
		if( isset($_POST['FriendLink']) ) {
			$model->setAttributes($_POST['FriendLink'], false);
			if( $model->save() ) {
				$this->setFlashMessage('success', '更新成功！');
				return $this->redirect($this->createUrl('list', array('cid' => $model->cid)));
			} else {
				$this->setFlashMessage('error', "更新失败！");
			}
		}
		
		$topid = Channel::getTopChannelId($model->cid);
		$this->navigationCurrentItemKey = 'theme_' . Channel::getThemeId($topid) . '/' . $topid;
		
		$_GET['cid'] = $model->cid;
		
		$this->render('//form_template', array(
			'model' => $model,
			'title' => '更新友情链接'
		));
	}
	
	public function actionDelete()
	{
		if ( isset($_POST['id']) ) {
			$id = $_POST['id'];
		} else {
			$id = array(intval($_GET['id']));
		}
		
	
		$count = 0;
		$criteria = new CDbCriteria();
		$criteria->addInCondition('id', $id);
		
		$count = FriendLink::model()->deleteAll($criteria);
		
		if ( Yii::app()->request->isAjaxRequest ) {
			echo 'ok';
		} else {
			if ($count) {
				$this->setFlashMessage('success', "共删除 {$count} 条友情链接！");
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
		return array(
			array(
				'shortcut' => $this->asset('images/icons/favorite.png'),
				'label' => '友情链接列表',
				'url' => $this->createUrl('list', array('cid' => $_GET['cid']))
			),
			array(
				'shortcut' => $this->asset('images/icons/add.png'),
				'label' => '创建友情链接',
				'url' => $this->createUrl('create', array('cid' => $_GET['cid']))
			)
		);
	}
}
?>