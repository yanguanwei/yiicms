<?php
class ChannelController extends AdminController
{
	public $navigationCurrentItemKey = 'system/channel';
	
	public function actionIndex($theme_id = 0)
	{
		$theme_id = intval($theme_id);
		
		if ( !$theme_id ) {
			$row = Yii::app()->db->createCommand("SE"."LECT id FROM {{theme}} ORD"."ER BY id ASC LIMIT 1")->queryRow();
			if ( $row )
				$theme_id = intval($row['id']);
			if ( $theme_id )
				$_GET['theme_id'] = $theme_id;
		}
		
		$conn = Yii::app()->db;
		$command = $conn->createCommand("SE"."LECT c.id, c.sort_id, c.title, c.parent_id, c.visible, c.theme_id, am.title as archive_model FROM {{channel}} c LEFT JOIN {{channel_model}} am ON am.id=c.model_id WHERE theme_id='{$theme_id}' ORD"."ER BY c.sort_id DESC, c.id ASC");
	
		$channels = array();
		$title = Theme::getThemeTitle($theme_id);
		foreach ($command->queryAll() as $row) {
			$row['visible'] = $this->getSelected($row['visible']);
			$channels[] = $row;
		}
		
	    $this->render('index', array(
	    	'channels' => $channels,
	    	'theme_id' => $theme_id,
	    	'title' => $title
	    ));
	}
	
	protected function getSelected($value)
	{
		return $value?'<span style="color:#090">√</span>' : '<span style="color:#900">×</span>';
	}
   
	public function actionCreate($theme_id, $parent_id = 0)
	{
		$parent_id = intval($parent_id);
		$theme_id = intval($theme_id);
		
		$model = new ChannelForm('insert');
		
		if(isset($_POST['ChannelForm'])) {
			$model->setAttributes($_POST['ChannelForm'], false);
			if (  $model->post($_POST['ChannelForm'], true) ) {
				$this->setFlashMessage('success', '栏目创建成功！点击<a href="'.$this->createUrl('create', array('theme_id'=> $theme_id, 'parent_id'=> $parent_id)).'">继续创建</a>');
				return $this->redirect($this->createUrl('index', array('theme_id' => $model->theme_id)));
			}
		} else {
			$model->parent_id = $parent_id;
			$model->theme_id = $theme_id;
			if ( $parent_id )
				$model->model_id = Channel::getChannelModelId($parent_id);
		}
		
		$this->render('//form_template', array(
			'model' => $model,
			'title' => '创建栏目'
		));
	}
	
	public function actionUpdate($id)
	{
		$model = new ChannelForm('update');
		
		if( isset($_POST['ChannelForm']) ) {
			if( $model->post($_POST['ChannelForm'], false) ) {
				$this->setFlashMessage('success', '更新成功！');
				return $this->redirect($this->createUrl('index', array('theme_id' => $model->theme_id)));
			} else {
				$this->setFlashMessage('error', '更新失败！');
			}
		} else {
			$channel = Channel::model()->findByPk($id);
			if ($channel) {
				$model->setAttributes($channel->getAttributes(), false);
				
				//栏目别名
				$alias = ChannelAlias::model()->findByPk($channel->id);
				if ( $alias )
					$model->alias = $alias->alias;
				
			} else {
				$this->setFlashMessage('error', "没有找到ID为{$id}的栏目！");
			}
		}
		
		$_GET['theme_id'] = $model->theme_id;
		
		$this->render('//form_template', array(
			'model' => $model,
			'title' => '更新栏目'
		));
	}
	
	public function actionDelete()
	{
		if (isset($_GET['id'])) {
			$id = intval($_GET['id']);
			$count = 0;
			if (Channel::model()->count("parent_id='{$id}'")) {
				$this->setFlashMessage('error', "删除失败：请先删除该栏目下的子栏目！");
			} else {
				$channel = Channel::model()->findByPk($id);
				if ( $channel ) {
					if ( Channel::countFromModel($id) ) {
						$this->setFlashMessage('error', "删除失败：请先删除该栏目下的信息！");
					} else {
						$count = $channel->delete();
						$this->setFlashMessage('success', "删除成功：共删除 {$count} 个栏目");
					}
				} else {
					$this->setFlashMessage('information', "该栏目不存在或已经被删除！");
				}
			}
		}
		
		$url = Yii::app()->getRequest()->getUrlReferrer();
		if ( $url )
			$this->redirect($url);
	}
	
	public function getShortcuts()
	{
		$buttons = array();
		
		foreach ( Theme::getThemeSelectOptions() as $id => $title ) {
			$buttons[] = array(
				'shortcut' => $this->asset('images/icons/folder_empty.png'),
				'label' => $title,
				'url' => $this->createUrl('channel/index', array('theme_id' => $id))
			);
		}
		
		if ( $_GET['theme_id'] ) {
			$buttons[] = array(
				'shortcut' => $this->asset('images/icons/folder_add.png'),
				'label' => '创建栏目',
				'url' => $this->createUrl('channel/create', array('theme_id' => $_GET['theme_id']))
			);
		}
		
		$buttons[] = array(
				'shortcut' => $this->asset('images/icons/google_Chrome.png'),
				'label' => '创建模型',
				'url' => $this->createUrl('model/create'),
				'class' => 'popuplayer iframe',
				'pupuplayer' => '{"iframeWidth":900, "iframeHeight":510}'
			);
		
		return $buttons;
	}

	public function actionUpdateSort()
	{
		return $this->doUpdateSort('channel');
	}
}
?>