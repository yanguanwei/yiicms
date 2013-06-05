<?php
class CKFinder
{
	/**
	 * 初始化ckfinder的上传目录的绝对路径和上传目录的URL路径
	 * 
	 * @param string $uploadPath
	 * @param string $uploadUrl
	 */
	public static function initSession($uploadPath, $uploadUrl)
	{
		$lo_session = new CHttpSession();
		$lo_session->open();
		$lo_session['CKFinder_auth'] = ! Yii::app()->user->isGuest;
		$lo_session['CKFinder_upload_path'] = $uploadPath;
		$lo_session['CKFinder_upload_url'] = $uploadUrl;
	}
	
	/**
	 * 发布ckfinder的资源，并在当前页引入ckfinder.js文件，
	 * 同时添加了CKFinderPopup函数以供调用，
	 * 同时返回ckfinder资源URL路径
	 * 
	 * @return string
	 */
	public static function registerAssets()
	{
		$cs = Yii::app()->getClientScript();
    	
    	$basePath = Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('ext.ckfinder.assets'));
    	
    	$cs->registerScriptFile($basePath.'/ckfinder.js');
    	
    	$code = <<<code
function CKFinderPopup(o)
{
	var finder;
	finder = new CKFinder();
	finder.basePath = '{$basePath}';
	finder.startupPath = 'Images:/';
	finder.popup();
	return finder;
}
code;
    	$cs->registerScript('CKFinderPopup', $code, CClientScript::POS_HEAD);
    	
    	return $basePath;
	}
}
?>