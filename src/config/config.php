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
    | CMS AUTH TYPE (local, keycloak)
    |--------------------------------------------------------------------------
    */
    'auth_type' => 'local',

    'auth_types' => array(

        'keycloak' => array(
            'authServerUrl'         => null,
            'realm'                 => null,
            'clientId'              => null,
            'clientUuid'            => null,
            'clientSecret'          => null,
            'redirectUri'           => null,
            'encryptionAlgorithm'   => null,                        // optional
            'encryptionKeyPath'     => null,                        // optional
            'encryptionKey'         => null                         // optional
        )
    ),



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
                array(
                    'pattern' => '/global+/',
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
                'targets'  => array(
									' ','\\','/',':','*',
									'?','"','<','>','|',
									'(',')','~','^','º','ª',
									'ã','à','á','ä','â',
									'ẽ','è','é','ë','ê',
									'ì','í','ï','î',
									'õ','ò','ó','ö','ô',
									'ù','ú','ü','û',
									'ñ','ç','¸',',',':',';'), // target chars
                'replace'  => '_'    // replace to this
            )
        )
    ),

);
