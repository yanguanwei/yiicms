<?php
class UnlimitedSelect extends CInputWidget
{
	public $data = array();
	public $idColumn = 'id';
	public $titleColumn = 'title';
	public $parentColumn = 'parent_id';
	public $rootId = 0;

	/**
	 * hasChildren
	 * noChildren
	 * next
	 * end
	 * nextSpec
	 * space
	 * 
	 * @var array
	 */
	protected $_images = array();
	
	/**
	 * array(
	 * 		'$id'	=> $title
	 * )
	 * @var array
	 */
	protected $_options = array();
	/**
	 * array(
	 * 		'$parentID'	=> array(
	 * 			'$id'	=> '$title'
	 * 		)
	 * )
	 * @var array
	 */
	protected $_data = array();
	protected $_layer = array();
	
	/**
	 * $options = array(
	 * 	'idColumn'	=> 主键字段名,
	 * 	'titleColumn'	=> 分类名的字段名
	 * 	'parentColumn'=> 父分类的字段名
	 * )
	 */
	public function run() 
	{
		parent::run();
		
		$this->initImages();
		$this->initData();
		$this->orderChannel(0, $this->rootId);
		
		list($name, $id) = $this->resolveNameID();
		$this->htmlOptions['id'] = $id;
		$this->htmlOptions['encode'] = false;
			
		if($this->hasModel()) {
			$html = CHtml::activeDropDownList($this->model, $this->attribute, $this->_options, $this->htmlOptions);
		} else {
		 	$html = CHtml::dropDownList($name, $this->value, $this->_options, $this->htmlOptions);
        }
        
		$this->reset();
		
        echo $html;
	}
	
	protected function reset() 
	{
		$this->data = array();
		$this->_options = array();
		$this->_data = array();
		$this->_layer = array();
	}
	
	/**
	 * 频道树形分类列表设置
	 */
	protected function initImages() 
	{
		$this->_images = array(
			'hasChildren'	=> '',
			'noChildren'	=> '',
			'next'			=> '&nbsp;├&nbsp;',
			'end'			=> '&nbsp;└&nbsp;',
			'nextSpec'		=> '&nbsp;│',
			'space'			=> '&nbsp;&nbsp;&nbsp;'
		);
	}

	protected function initData()
	{
		foreach ($this->data as $rs) {
			$this->_data[$rs[$this->parentColumn]][$rs[$this->idColumn]] = $rs[$this->titleColumn];
		}
	}
	
	/**
	 * 对$this->_data进行树型排序
	 * 处理结果在$this->option数组中
	 * 
	 * @param $n 当前的层数
	 * @param $currentParentID 当前的父ID
	 */
	protected function orderChannel($n, $currentParentID)
	{
		if (isset($this->_data[$currentParentID])) {
			foreach ($this->_data[$currentParentID] as $id => $title) {
				unset($this->_data[$currentParentID][$id]);
				if ($this->_data[$currentParentID]) {
						$this->_layer[$n] = true;
					} else {
						$this->_layer[$n] = false;
				}
				$option = '';
				if ($n > 0) {
					for ($i = 0; $i <= $n; $i++) {
						if ($i == $n) {
							if ($this->_layer[$i]) {
								$option .= $this->_images['next'];
							} else {
								$option .= $this->_images['end'];
							}
						} else {
							if ($this->_layer[$i]) {
								$option  .= $this->_images['nextSpec'];
							} else {
								$option .= $this->_images['space'];
							}
						}
					}
				}
				if (isset($this->_data[$id])) {
					$option .= $this->_images['hasChildren'];
				} else {
					$option .= $this->_images['noChildren'];
				}
				
				$option .= $title;
				$this->_options[$id] = $option;
				$this->orderChannel($n+1, $id);
			}
		}
	}
}
?>