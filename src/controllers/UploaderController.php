<?php namespace Insomnia\Cms\Controllers;

use Insomnia\Cms\Classes\Uploader as Uploader;
use Insomnia\Cms\Models\Datasource as Datasource;

use Config;
use Response;
use Input;
use Validator;

class UploaderController extends AdminController {


  public function upload() {

    $uploader = new Uploader();

    $uploadDir = $_POST['folder']?'global/'.$_POST['folder']:'global';


    if(Input::get('compress')){
        $fileArray = array('image' => Input::file(Input::get('field'))[0]);
        $rules = array(
            'image' => 'mimes:jpeg,jpg,png,gif'
        );

        $validator = Validator::make($fileArray, $rules);
        if (!$validator->fails())
        {
            $file = $_FILES[$_POST['field']];
            $width = Input::get('compress'); // max width
            $height = Input::get('compress'); // max height
            $img = \Image::make($file["tmp_name"][0]);
            if($img->height() > Input::get('compress') || $img->width() > Input::get('compress')) {
                $img->height() > $img->width() ? $width=null : $height=null;
                $img->resize($width, $height, function ($constraint) {
                    $constraint->aspectRatio();
                });

                $a = filesize($file["tmp_name"][0]);
                $img->save();
                clearstatcache();
                $fileresize = filesize($file["tmp_name"][0]);   
            }
            
        }
    }
    

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
        if(@$fileresize) $files['metas'][0]['size'] = $fileresize;
        return Response::json($files);
    }

    if($data['hasErrors']){
        $errors = $data['errors'];
        print_r($errors);
    }

	}

}






// class UploaderController extends AdminController {


//   public function upload() {

//     $uploader = new Uploader();

//     $uploadDir = $_POST['folder']?'global/'.$_POST['folder']:'global';


//     $imgs = Input::files(Input::get('field'));

//     foreach($imgs as $img) {

//         $x = $img;

//         // $width = 600; // your max width
//         // $height = 600; // your max height
//         // $img = \Image::make($img);
//         // $img->height() > $img->width() ? $width=null : $height=null;
//         // $img->resize($width, $height, function ($constraint) {
//         //     $constraint->aspectRatio();
//         // });

//         // $img->save($x->getRealPath());
        

//         $data = $uploader->upload($x, array(
//             'limit' => null, //Maximum Limit of files. {null, Number}
//             'maxSize' => 30, //Maximum Size of files {null, Number(in MB's)}
//             'extensions' => null, //Whitelist for file extension. {null, Array(ex: array('jpg', 'png'))}
//             'required' => false, //Minimum one file is required for upload {Boolean}
//             'uploadDir' => Config::get('cms::config.elfinder_dir').'/'.$uploadDir.'/', //Upload directory {String}
//             'title' => array('slugname', 10), //New file name {null, String, Array} *please read documentation in README.md
//             'removeFiles' => true, //Enable file exclusion {Boolean(extra for jQuery.filer), String($_POST field name containing json data with file names)}
//             'replace' => false, //Replace the file if it already exists {Boolean}
//             'perms' => null, //Uploaded file permisions {null, Number}
//             'onCheck' => null, //A callback function name to be called by checking a file for errors (must return an array) | ($file) | Callback
//             'onError' => null, //A callback function name to be called if an error occured (must return an array) | ($errors, $file) | Callback
//             'onSuccess' => null, //A callback function name to be called if all files were successfully uploaded | ($files, $metas) | Callback
//             'onUpload' => null, //A callback function name to be called if all files were successfully uploaded (must return an array) | ($file) | Callback
//             'onComplete' => null, //A callback function name to be called when upload is complete | ($file) | Callback
//             'onRemove' => 'onFilesRemoveCallback' //A callback function name to be called by removing files (must return an array) | ($removed_files) | Callback
//         ));
    
//         if($data['isComplete']){
//             $files = $data['data'];
//           return Response::json($files);
//         }
    
//         if($data['hasErrors']){
//             $errors = $data['errors'];
//             print_r($errors);
//         }
//     }



// 	}

// }
