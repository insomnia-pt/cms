/**
 * @license Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.html or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	config.filebrowserBrowseUrl = $ocms_url+'/elfinder/ckeditor';
	config.allowedContent = true;

	config.autoParagraph = false;
    config.enterMode = CKEDITOR.ENTER_BR;

    config.toolbar = $ocms_ckeditor_toolbar;
	config.toolbar_Full =
	[
	    { name: 'document', items : [ 'Source','-','DocProps','Preview','Print' ] },
	    { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
	    { name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
	    { name: 'styles', items : [ 'Format','FontSize' ] },
	    { name: 'colors', items : [ 'TextColor','BGColor' ] },
	    { name: 'forms', items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
	    '/',
	    { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
	    { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },
	    { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
	    { name: 'insert', items : [ 'Image','Table','Accordion','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
	    { name: 'tools', items : [ 'ShowBlocks', 'Maximize' ] }

	   
	];

	config.toolbar_Mini =
	[
	    { name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
	    { name: 'editing', items : [ 'Find','Replace','-','SelectAll' ] },
	    { name: 'links', items : [ 'Link','Unlink','Anchor' ] },
	    { name: 'insert', items : [ 'Image','Table','Accordion','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },
	    { name: 'tools', items : [ 'ShowBlocks', 'Maximize' ] },
	    '/',
	    { name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','-','RemoveFormat' ] },
	    { name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'] },
	    { name: 'styles', items : [ 'Format'] }
	    

	   
	];

	config.extraPlugins = 'accordion';

};
