<?php namespace Insomnia\Cms\Controllers;

use Insomnia\Cms\Classes\Uploader as Uploader;
use Insomnia\Cms\Models\Datasource as Datasource;

use Config;
use Response;


class UploaderController extends AdminController {


  public function upload() {

    $uploader = new Uploader();

    $uploadDir = $_POST['folder']?'global/'.$_POST['folder']:'global';

    $data = $uploader->upload($_FILES[$_POST['field']], array(
        'limit' => null, //Maximum Limit of files. {null, Number}
        'maxSize' => 30, //Maximum Size of files {null, Number(in MB's)}
        'extensions' => null, //Whitelist for file extension. {null, Array(ex: array('jpg', 'png'))}
        'required' => false, //Minimum one file is required for upload {Boolean}
        'uploadDir' => Config::get('cms::config.elfinder_dir').'/'.$uploadDir.'/', //Upload directory {String}
        'title' => array('slugname', 10), //New file name {null, String, Array} *please read documentation in README.md
        'removeFiles' => true, //Enable file exclusion {Boolean(extra for jQuery.filer), String($_POST field name containing json data with file names)}
        'replace' => false, //Replace the file if it already exists {Boolean}
        'perms' => null, //Uploaded file permisions {null, Number}
        'onCheck' => null, //A callback function name to be called by checking a file for errors (must return an array) | ($file) | Callback
        'onError' => null, //A callback function name to be called if an error occured (must return an array) | ($errors, $file) | Callback
        'onSuccess' => null, //A callback function name to be called if all files were successfully uploaded | ($files, $metas) | Callback
        'onUpload' => null, //A callback function name to be called if all files were successfully uploaded (must return an array) | ($file) | Callback
        'onComplete' => null, //A callback function name to be called when upload is complete | ($file) | Callback
        'onRemove' => 'onFilesRemoveCallback' //A callback function name to be called by removing files (must return an array) | ($removed_files) | Callback
    ));

    if($data['isComplete']){
        $files = $data['data'];
      return Response::json($files);
    }

    if($data['hasErrors']){
        $errors = $data['errors'];
        print_r($errors);
    }

	}

}
