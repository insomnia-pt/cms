<?php
return array(

	/*
    |--------------------------------------------------------------------------
    | CMS URI
    |--------------------------------------------------------------------------
    */
    'uri' => 'cms',

    /*
    |--------------------------------------------------------------------------
    | PACKAGE ASSETS PATH
    |--------------------------------------------------------------------------
    */
    'assets_path' => 'packages/insomnia/cms/cms-res',

    /*
    |--------------------------------------------------------------------------
    | PACKAGE PACKAGES PATH
    |--------------------------------------------------------------------------
    */
    'packages_path' => 'packages/insomnia/cms/packages',

    /*
    |--------------------------------------------------------------------------
    | PREFIX FOR DATASOURCE TABLES
    |--------------------------------------------------------------------------
    */
    'datasource_table_prefix' => 'ds_',

    /*
    |--------------------------------------------------------------------------
    | ELFINDER UPLOAD DIR
    |--------------------------------------------------------------------------
    */
    'elfinder_dir' => 'uploads',

    /*
    |--------------------------------------------------------------------------
    | ELFINDER ROOTS
    |--------------------------------------------------------------------------
    */
    'elfinder_roots' => array(
         array(
            'driver' => 'LocalFileSystem',
            'path'   => 'uploads/',
            'URL'    => Config::get('app.url').'/uploads/',
            'alias'  => 'Raiz',
            'attributes' => array(
                array(
                    'pattern' => '/.tmb+/',
                    'hidden' => true
                ),
                 array(
                    'pattern' => '/.quarantine+/',
                    'hidden' => true
                ),
            )
        )
    ),
    
    /*
    |--------------------------------------------------------------------------
    | ELFINDER OPTIONS
    |--------------------------------------------------------------------------
    */
    'elfinder_options' => array(
        'bind' => array(
           'mkdir.pre mkfile.pre rename.pre archive.pre' => array(
               'Plugin.Sanitizer.cmdPreprocess'
           ),
           'upload.presave' => array(
               'Plugin.Sanitizer.onUpLoadPreSave'
           )
       ),
        'plugin' => array(
            'Sanitizer' => array(
                'enable' => true,
                'targets'  => array(' ','\\','/',':','*','?','"','<','>','|'), // target chars
                'replace'  => '_'    // replace to this
            )
        )
    ),
 
);