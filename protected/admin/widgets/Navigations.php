<?php
Yii::import('zii.widgets.CMenu');

class Navigations extends CMenu
{
	public $currentItemKey;
	public $topItemCssClass = 'nav-top-item';
	public $noItemCssClass = 'no-submenu';
	
	public function init()
	{
		$this->activeCssClass = 'current';
		$this->activateParents = true;
		$id = $this->htmlOptions['id'];
		
		if ( $this->currentItemKey ) {
			$this->currentItemKey = explode('/', $this->currentItemKey);
		} else {
			$this->currentItemKey = array();
		}

		foreach ($this->items as $key => &$item) {
			if ( !isset($item['itemOptions']) ) {
				$item['itemOptions'] = array();
			}
			
			if ( isset($item['itemOptions']['class']) ) {
				$item['itemOptions']['class'] .= ' ' . $this->topItemCssClass;
			} else {
				$item['itemOptions']['class'] = $this->topItemCssClass;
			}
			
			if ( !isset($item['items']) ) {
				$item['itemOptions']['class'] .= ' ' . $this->noItemCssClass;
			}
			
			if ( !isset($item['url']) ) {
				$item['url'] = '#';
			}
			
			if ( $this->currentItemKey && $this->currentItemKey[0] === ((string) $key) ) {
				$this->parseActiveItem($item);
			}
		}
		
		parent::init();
		
		if ($id)
			$this->htmlOptions['id'] = $id;
	}
	
	protected function parseActiveItem(&$item)
	{
		if ($item['items']) {
			foreach ($item['items'] as $key => &$itm) {
				if ( ((string) $key) === $this->currentItemKey[1]) {
					$item['active'] = $itm['active'] = true;
					break;
				}
			}
		} else {
			if ( count($this->currentItemKey) == 0 )
				$item['active'] = true;
		}
	}
}
?>