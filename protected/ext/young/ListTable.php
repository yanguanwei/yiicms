<?php
class ListTable extends CWidget
{
	/**
	 * @var DataProvider
	 */
	public $dataProvider;
	public $row;
	public $data;
	public $htmlOptions = array();
	public $columns = array();
	public $titles = array();
	public $primaryKey = 'id';
	public $selectable = false;
	
	protected $selectableAllClass = 'selectable-all';
	protected $selectableCellClass = 'selectable-cell';
	protected $selectableCheckboxClass = 'selectable-checkbox';
	
	public function init()
	{
		Yii::import('apps.ext.young.UniversalDataCell');
		
		echo CHtml::openTag('table', $this->htmlOptions);
		
		if ( $this->titles )
			$this->renderHead($this->titles);
		
		
		if ( $this->selectable ) {
			$this->selectableCheckboxClass .= $this->getId();
			$script = <<<code
$('.{$this->selectableAllClass}').click(function() {
	$('.{$this->selectableCheckboxClass}').attr('checked', $(this).is(':checked'));
});
code;
			Yii::app()->getClientScript()->registerScript($this->getId(), $script);
		}
	}
	
	public function run()
	{
		echo CHtml::closeTag('table');
	}
	
	public function renderTitle($name, $title)
	{
		echo '<th class="'.$name.'-column">'.$title.'</th>';
	}
	
	public function renderTitles($titles)
	{
		foreach ($this->titles = $titles as $name => $title) {
			$this->renderTitle($name, $title);
		}
	}
	
	public function cell($name, $cell = null, array $htmlOptions = array())
	{
		if ( $cell === null )
			$cell = array();
		
		if ( is_array($cell) ) {
			$cell['data'] = $this->data;
			$cell['name'] = $name;
			$cell = new UniversalDataCell($cell);
			$cell = $cell->getValue();
		}
		
		if ( !isset($htmlOptions['class']) )
			$htmlOptions['class'] = "{$name}-column";
		
		return CHtml::tag('td', $htmlOptions, $cell);
	}
	
	public function renderCell($name, $cell = null, array $htmlOptions = array())
	{
		echo $this->cell($name, $cell, $htmlOptions);
	}
	
	public function nextRow()
	{
		$this->data = $this->dataProvider->next();
		$this->row = $this->dataProvider->row();
		
		if ( $this->data )
			return true;
		
		return false;
	}
	
	public function beginHead()
	{
		echo '<thead><tr>';
	}
	
	public function endHead()
	{
		echo '</tr></thead>';
	}
	
	public function renderHead($titles)
	{
		$this->beginHead();
			if ( $this->selectable )
				echo CHtml::tag('th', array(
					'class' => $this->selectableCellClass
				),  CHtml::checkBox('', false, array('class' => $this->selectableAllClass)));
			$this->renderTitles($titles);
		$this->endHead();
	}
	
	public function beginBody()
	{
		echo '<tbody>';
	}
	
	public function endBody()
	{
		echo '</tbody>';
	}
	
	public function renderRow()
	{
		$this->beginRow();
		
		$cells = func_get_args();
		foreach ($cells as $cell) {
			echo $cell;
		}
		
		$this->endRow();
	}
	
	public function beginRow(array $htmlOptions = array())
	{
		echo CHtml::openTag('tr', $htmlOptions);
		
		if ( $this->selectable )
			echo CHtml::tag('td', array('class' => $this->selectableCellClass), CHtml::checkBox('id[]', false, array(
				'value' => $this->data[$this->primaryKey],
				'class' => $this->selectableCheckboxClass
			)));
	}
	
	public function endRow()
	{
		echo CHtml::closeTag('tr');
	}
	
	public function renderPager()
	{
		return $this->widget('apps.ext.young.Pager', array(
				'total' => $this->dataProvider->total(),
				'page' => $this->dataProvider->page(),
				'pagesize' => $this->dataProvider->pagesize()
			));
	}
	
	public function beginFoot()
	{
		$count = count($this->titles);
		if ( $this->selectable )
			$count++;
		
		echo '<tfoot><tr><td colspan="'.$count.'">';
	}
	
	public function endFoot()
	{
		echo '</td></tr></tfoot>';
	}
	
	public function updateButton($action = 'update')
	{
		$controller = $this->owner;
		return CHtml::link(
			CHtml::image($this->asset('update.png'), '更新'),
			$controller->createUrl($action, array($this->primaryKey => $this->data[$this->primaryKey])),
			array('title' => '更新')
		);
	}
	
	public function deleteButton($action = 'delete')
	{
		$controller = $this->owner;
		return CHtml::link(
				CHtml::image($this->asset('delete.png'), '删除'),
				$controller->createUrl($action, array($this->primaryKey => $this->data[$this->primaryKey])),
				array('title' => '删除', 'class' => 'delete')
		);
	}
	
	protected function asset($path)
	{
		return Yii::app()->getAssetManager()->publish(dirname(__FILE__) . '/assets/' . $path);
	}
}
?>