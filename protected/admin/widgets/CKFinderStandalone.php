<?php
class CKFinderStandalone extends CWidget
{
	public $htmlOptions = array();
	
	public function init()
	{
		Yii::import('application.widgets.CKFinderInput');
		
		CKFinderInput::initCKFinderSession();
	}
	
	public function run()
    {
		//Yii::import('ext.ckfinder.CKFinder');
    	$cs = Yii::app()->getClientScript();
    	
    	$basePath = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.ckfinder.assets'));
    	
    	$cs->registerScriptFile($basePath.'/ckfinder.js');
    	
		$script = <<<code
<script type="text/javascript">
$(function() {
var finder = new CKFinder();
finder.basePath = '$basePath';
finder.height = 600;
//finder.create();
});
</script>
code;
		echo CHtml::tag('div', $this->htmlOptions, $script);
    }
}
?>