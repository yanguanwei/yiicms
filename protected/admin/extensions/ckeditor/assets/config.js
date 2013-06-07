/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
// Define changes to default configuration here. For example:
	config.language = 'zh-cn';
	//config.uiColor = '#AADC6E';
	config.font_names = '微软雅黑;宋体;楷体_GB2312;新宋体;黑体;隶书;幼圆;Arial;Comic Sans MS;Courier New;Tahoma;Times New Roman;Verdana';

	config.toolbar_Full = [
		['Source','-','Templates'],
		['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print'],
		['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],['ShowBlocks'],['Image','Capture','Flash'],['Maximize'],
		'/',
		['Bold','Italic','Underline','Strike','-'],
		['Subscript','Superscript','-'],
		['NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['Link','Unlink','Anchor'],
		['Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
		'/',
		['Styles','Format','Font','FontSize'],
		['TextColor','BGColor'],
		['attachment'],
	];
};
