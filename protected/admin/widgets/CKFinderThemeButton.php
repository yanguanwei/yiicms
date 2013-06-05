<?php
class CKFinderThemeButton extends CWidget
{
	public $startupPath = 'Images:/';
	public $theme;
	
	public static function initCKFinderSession($theme)
	{
		// 上传相对URL
		$uploadPath = Yii::app()->getThemeManager()->getBasePath() . "/{$theme}/assets/";
		// 上传相对URL
		$uploadUrl = THEMES_URL . "/{$theme}/assets/";
	
		Yii::import('ext.ckfinder.CKFinder');
    	 
    	CKFinder::initSession($uploadPath, $uploadUrl);
	}
	
	public function run()
	{
		self::initCKFinderSession($this->theme);
		
		$id = $this->getId();
		$cs = Yii::app()->getClientScript();
		$baseDir = dirname(__FILE__);
		$basePath = Yii::app()->getAssetManager()->publish($baseDir.DIRECTORY_SEPARATOR.'assets');
		
		$cs->registerScriptFile($basePath . '/ckfinder.js');
		
		echo <<<code
<script type="text/javascript">
$('#{$id}').click(function() {
	var finder;
	finder = new CKFinder();
	finder.basePath = '{$basePath}';
	finder.startupPath = '{$this->startupPath}';
	finder.popup();
});
</script>
code;
	}
}
?>