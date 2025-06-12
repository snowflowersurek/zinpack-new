/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.resize_enabled = true;
	config.enterMode = CKEDITOR.ENTER_BR;
	config.shiftEnterMode = CKEDITOR.ENTER_P;
	config.startupFocus = true;
	config.uiColor = '#EEEEEE';
	config.toolbarCanCollapse = false;
	config.menu_subMenuDelay = 0;
	config.allowedContent = true;
	config.height = 400;
	config.font_names = '맑은고딕;돋움;굴림;궁서;바탕;' + CKEDITOR.config.font_names;
	//config.toolbar = [['Bold','Italic','Underline','Strike','-','Subscript','Superscript','-','TextColor','BGColor','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','Link','Unlink','-','Find','Replace','SelectAll','RemoveFormat','-','Image','Table','SpecialChar'],'/',['Source','-','ShowBlocks','-','Font','FontSize','Undo','Redo','-','About']];
	//config.filebrowserUploadUrl       = "/include/ckeditor/upload.php?type=Images";
	config.filebrowserImageUploadUrl  = "/include/ckeditor/upload.php?type=Images";
	//config.filebrowserFlashUploadUrl  = "/include/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Flash";
};