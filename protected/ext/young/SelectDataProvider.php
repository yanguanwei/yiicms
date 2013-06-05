<?php
Yii::import('apps.ext.young.DataProvider');

class SelectDataProvider implements DataProvider
{
	protected $SQL;
	protected $conn;
	protected $data = array();
	protected $row = 0;
	protected $total = 0;
	protected $count = 0;
	protected $pagesize;
	protected $page;
	
	public function __construct(CDbConnection $conn, SelectSQL $SQL, $pagesize = 10)
	{
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		
		$this->page = intval($page);
		$this->pagesize = $pagesize;
		
		$SQL->paging($page, $pagesize);
		$this->SQL = $SQL;
		$this->data = $conn->createCommand($SQL->toSQL())->queryAll();
		$this->count = count($this->data);
		$this->total = intval($conn->createCommand($SQL->toTotalCountSQL())->queryScalar());
	}
	
	public function next()
	{
		$data = current($this->data);
		$this->row = key($this->data);
		next($this->data);
		return $data;
	}
	
	public function row()
	{
		return $this->row;
	}
	
	public function total()
	{
		return $this->total;	
	}
	
	public function count()
	{
		return $this->count;
	}
	
	public function pagesize()
	{
		return $this->pagesize;
	}
	
	public function page()
	{
		return $this->page;
	}
}
?>