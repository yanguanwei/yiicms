<?php
/*
 * EImageFinder widget
 * Based on CKFinder (http://ckfinder.com/)
 *
 * @usage $this->widget('ext.finder.EImageFinder',array('fieldName'=>'my_field'));
 *
 * @author: Cassiano Surek <cass@surek.co.uk>
 */

class CKFinderInput extends CInputWidget
{
	public $startupPath;
	public $buttonHtmlOptions = array();
    public $hasPreview = true;
	
    public static function initCKFinderSession()
    {
    	// 上传绝对路径
    	//$uploadPath = dirname(dirname(Yii::app()->request->scriptFile)) . '/uploads/';
    	$uploadPath = Yii::getPathOfAlias('wwwroot.uploads') . '/';
    
    	// 上传相对URL
    	$uploadUrl = FRONTEND_URL . '/uploads/';
    	
    	Yii::import('ext.ckfinder.CKFinder');
    	 
    	CKFinder::initSession($uploadPath, $uploadUrl);
    }
 
    public function init()
    {
    	self::initCKFinderSession();
    }
    
 	public function run()
    {
    	$cs = Yii::app()->getClientScript();
    	$basePath = CKFinder::registerAssets();
    	$cs->registerScriptFile($basePath.'/input.js');
    	
    	list($name, $id) = $this->resolveNameID();
    	$value = $this->hasModel() ? CHtml::resolveValue($this->model, $this->attribute) : $this->value;
    	
    	$this->htmlOptions['id'] = $id;
    	$this->buttonHtmlOptions['id'] = 'select_' . $id;
    	
		echo <<<code
<script type="text/javascript">
$(function() {
	new CKFinderInput('{$basePath}', '{$this->startupPath}', '{$id}');
});
</script>
code;
		echo CHtml::textField($name, $value, $this->htmlOptions);
		echo '&nbsp;';
		echo CHtml::button("选择", $this->buttonHtmlOptions);
		if ( $this->hasPreview )
		echo <<<code
<div id="{$id}_preview" style="display:none" class="preview"></div>	
code;
    }
}