/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function (config) {
    if(!config.imagePatch){
        config.imagePatch = 'files';
    }
    config.language = 'ru';
    //config.uiColor = '#AADC6E';
    config.filebrowserUploadUrl = '/admin/upload/' + config.imagePatch+'?_token='+document.querySelector('meta[name="csrf-token"]').getAttribute('content')
    config.enterMode = CKEDITOR.ENTER_BR;
    config.shiftEnterMode = CKEDITOR.ENTER_BR;
};
