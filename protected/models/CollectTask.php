<?php
class CollectTask extends CActiveRecord
{
	public $id;
	public $title;
	public $type_id;
	public $cid;
	public $configs;
	public $data;
	public $is_repeat;
	public $update_time;
	
	const TYPE_DB = 0;
	
	/**
	 * Returns the static model of the specified AR class.
	 * @return CActiveRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{collect_task}}';
	}
	
	public function primaryKey()
	{
		return 'id';
	}
	
	public function rules()
	{
		return array(
			array('id', 'required', 'on' => 'update'),
			array('title, type_id, cid', 'required')		
		);
	}
	
	protected function afterFind()
	{
		$this->configs = unserialize($this->configs);
		if ( !$this->configs )
			$this->configs = array();
		
		if ( $this->data ) {
			$this->data = explode(',', $this->data);
		} else {
			$this->data = array();
		}
	}
	
	protected function beforeSave()
	{
		$this->update_time = time();
		
		if ( is_array( $this->configs) )
			$this->configs = serialize($this->configs);
		
		if ( is_array($this->data) )
			$this->data = implode(',', $this->data);
		
		return parent::beforeSave();
	}
	
	public function getCollectTaskTypeIdSelectOptions()
	{
		return array(
			self::TYPE_DB => '数据库采集'		
		);
	}
	
	public static function collectForLinkByDb($id, $offset = 0, $count = 50)
	{
		$task = self::model()->findByPk($id);
		if ( !$task )
			return '采集任务不存在！';
		
		$cid = $task->cid;
		
		if ( $task->is_repeat && $offset == 0 )
			$task->data = array();
		
		$pk = $task->configs['pk'];
		$title = $task->configs['title'];
		$url = $task->configs['url'];
		$order = $task->configs['order'];
		$logo = $task->configs['logo'];
		$table = $task->configs['table'];
		$where = $task->configs['where'];
		
		$sqlCount = "SELECT COUNT({$pk}) FROM {$table}";
		if ( $where )
			$sqlCount .= " WHERE {$where}";
		$total = intval(Yii::app()->db->createCommand($sqlCount)->queryScalar());
		
		if ( !$total )
			return '没有可采集的数据！';
		
		$fields = array();
		foreach (array($title, $order, $logo, $url) as $field)
			if ( $field )
				$fields[] = "`{$field}`";
		$fields = implode(',', $fields);
		$sql = "SELECT `{$pk}`, {$fields} FROM `{$table}`";
		if ( $where )
			$sql .= " WHERE {$where}";
		
		$sql .= " ORDER BY `{$pk}` ASC LIMIT {$offset}, {$count}";
		
		$data = array();
		foreach (Yii::app()->db->createCommand($sql)->queryAll() as $i => $row) {
			if ( $task->is_repeat || !in_array($row[$pk], $task->data) ) {
				if ($row[$logo]) 
					$row[$logo] = '/uploadfile/links/' . $row[$logo];
				$data[$row[$pk]] = array(
						'title' => $row[$title],
						'url' => $row[$url],
						'logo' => $row[$logo],
						'sort_id' => intval($row[$order])
				);
			}
		}
		
		if ( !$data )
			return array($total, 0);
		
		$task->data = array_merge($task->data, array_keys($data));
			
		$count = count($task->data);
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			foreach ($data as $id => $row) {
				$link = new FriendLink();
				$link->setAttributes($row, false);
				$link->cid = $cid;
				$link->visible = 1;
				if ( !$link->save() ) {
					throw new CException('数据插入失败：');
				}
			}
			$task->save();
			$transaction->commit();
		} catch ( CException $e ) {
			$transaction->rollback();
			return $e->getMessage();
		}
		
		return array($total, $count);
	}
	
	public static function collectForNewsByDb($id, $offset = 0, $count = 50)
	{
		$task = self::model()->findByPk($id);
		if ( !$task )
			return '采集任务不存在！';
		
		$cid = $task->cid;
		
		if ( $task->is_repeat && $offset == 0 )
			$task->data = array();
		
		/*
		$dsn = "mysql:dbname={$task->configs['db']};host={$task->configs['host']}";
	
		try {
			$pdo = new PDO($dsn, $task->configs['user'], '');//$task->configs['pass']
		} catch ( PDOException $e ) {
			return $e->getMessage();
		}
		*/
		
		$pk = $task->configs['pk'];
		$title = $task->configs['title'];
		$content = $task->configs['content'];
		$time = $task->configs['time'];
		$table = $task->configs['table'];
		$where = $task->configs['where'];
		$source = $task->configs['source'];
		
		$sqlCount = "SELECT COUNT({$pk}) FROM {$table}";
		if ( $where )
			$sqlCount .= " WHERE {$where}";
		$total = intval(Yii::app()->db->createCommand($sqlCount)->queryScalar());
		
		if ( !$total )
			return '没有可采集的数据！';
		
		$fields = array();
		foreach (array($title, $content, $time, $source) as $field)
			if ( $field )
				$fields[] = "`{$field}`";
		$fields = implode(',', $fields);
		$sql = "SELECT `{$pk}`, `{$title}`, `{$content}`, `{$time}`, `{$source}` FROM `{$table}`";
		if ( $where )
			$sql .= " WHERE {$where}";
		
		$sql .= " ORDER BY `{$pk}` ASC LIMIT {$offset}, {$count}";
	
		$data = array();
		foreach (Yii::app()->db->createCommand($sql)->queryAll() as $i => $row) {
			if ( $task->is_repeat || !in_array($row[$pk], $task->data) ) {
				$row[$content] = iconv('gbk', 'utf-8', $row[$content]);
				$data[$row[$pk]] = array(
					'title' => $row[$title],
					'content' => $row[$content],
					'update_time' => $row[$time],
					'source' => $row[$source]
				);
			}
		}
	
		if ( !$data )
			return array($total, 0);
		
		$task->data = array_merge($task->data, array_keys($data));
			
		$count = count($task->data);
		
		$transaction = Yii::app()->db->beginTransaction();
		try {
			foreach ($data as $id => $row) {
				$archive = new Archive();
				$archive->setAttributes($row, false);
				$archive->cid = $cid;
				$archive->model_id = 1;
				if ( $archive->save() ) {
					$news = new News();	
					$row['id'] = $archive->id;
					$news->setAttributes($row, false);
					if ( !$news->save() ) {
						throw new CException('数据插入失败：');
					}
				} else {
					throw new CException('数据插入失败：');
				}
			}
			
			$task->save();
			
			$transaction->commit();
		} catch ( CException $e ) {
			$transaction->rollback();
			return $e->getMessage();
		}
		
		return array($total, $count);
	}
}

?>