<?php
Yii::import('system.web.widgets.CWidget');
class Shortcuts extends CWidget
{
	public $buttons = array();
	
	public function run()
	{
		echo '<ul class="shortcut-buttons-set">';
		foreach ($this->buttons as $button) {
			$class = isset($button['class'])?' '.$button['class']:'';
			$popuplayer = isset($button['pupuplayer'])?" popuplayer='{$button['pupuplayer']}'":'';
			echo sprintf('<li><a class="shortcut-button%s"%s href="%s" title="%s"><span><img src="%s" alt="icon" /><br />%s</span></a></li>', $class, $popuplayer, $button['url'], $button['label'], $button['shortcut'], $button['label']);
		}
		echo '</ul>';
	}
}
?>