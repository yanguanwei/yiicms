<?php
Yii::import('zii.widgets.CPortlet');

class Tabs extends CPortlet
{
	/**
	 * @var array(
	 * 	array(
	 * 		'label' => 
	 * 		'url'	=>
	 *  )
	 * )
	 */
	public $tabs = array();
	public $defaultTab;
	public $float;
	public $width;
	
	public $htmlOptions = array(
		'class' => 'content-box'
	);
	
	public $titleCssClass = 'content-box-header';
	public $contentCssClass = 'content-box-content';
	
	private $_currentTabKey;
	private $_tabContent;
	
	public function init()
	{
		$title = "<h3>{$this->title}</h3>";
		if ($this->tabs) {
			$title .= '<ul class="content-box-tabs">';
			foreach ($this->tabs as $key => $tab) {
				$title .= '<li>' . CHtml::link($tab['label'], isset($tab['url'])?$tab['url']:'#'.$key, array('class' => $this->defaultTab==$key?'default-tab':'')).'</li>';
			}
			$title .= '</ul>';
		}
		
		$this->title = $title;
		
		if ($this->float) {
			if ($this->float == 'left') {
				$this->htmlOptions['class'] .= ' column-left';
			} elseif ($this->float == 'right') {
				$this->htmlOptions['class'] .= ' column-right';
			}
		}
		if ($this->width) {
			$this->htmlOptions['style'] = "width:{$this->width}";
		}
		
		parent::init();
		
		
	}
	
	protected function renderDecoration()
	{
		echo "<div class=\"{$this->titleCssClass}\">{$this->title}</div>\n";
	}
	
	protected function renderContent()
	{
		if ($this->tabs) {
			echo $this->_tabContent;
		} else {
			$content = ob_get_clean();
			ob_start();
			echo '<div class="tab-content default-tab">'.$content.'</div>';
		}
	}
	
	public function beginTab($key)
	{
		ob_start();
		$this->_currentTabKey = $key;
	}
	
	public function endTab()
	{
		$this->_tabContent .= sprintf('<div class="tab-content%s" id="%s">%s</div>', $this->_currentTabKey==$this->defaultTab?' default-tab':'', $this->_currentTabKey, ob_get_clean());
	}
}
?>