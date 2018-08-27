<?php

use Insomnia\Cms\Models\DatasourceFieldtype as DatasourceFieldtype;
use Insomnia\Cms\Models\Cmslog as Cmslog;
use Insomnia\Cms\Classes\JWT as JWT;

class Helpers {

    public static function getSlug($title, $model)
    {
        $slug = Str::slug($title);
        $slugCount = count( $model->whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->get() );

        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
    }

    public static function slugify($text)
    {
      // replace non letter or digits by -
      $text = preg_replace('~[^\pL\d]+~u', '-', $text);

      // transliterate
      $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

      // remove unwanted characters
      $text = preg_replace('~[^-\w]+~', '', $text);

      // trim
      $text = trim($text, '-');

      // remove duplicate -
      $text = preg_replace('~-+~', '-', $text);

      // lowercase
      $text = strtolower($text);

      if (empty($text)) {
        return 'n-a';
      }

      return $text;
    }

    public static function translateFieldTypes($id)
    {
        $fieldType = DatasourceFieldtype::find($id)->type;

        switch ($fieldType) {
        	case 'integer':
        		$dataType = "INT";
        		break;

        	case 'text':
        		$dataType = "TEXT";
        		break;

        	case 'date':
        		$dataType = "DATE";
        		break;

        	case 'dateTime':
        		$dataType = "DATETIME";
        		break;
        };


        return $fieldType;
    }

    public static function asset($path)
    {
        $path_override = str_replace(Config::get('cms::assets_path').'/assets', Config::get('cms::assets_path').'/assets_override', $path);
        if (File::exists($path_override)){
            return asset($path_override);
        } else {
            return asset($path);
        }
    }

    public static function thumb($imageFile, $sizeW = null, $sizeH = null)
    {
      $imageFile = ltrim($imageFile, '/');
      if(!File::exists($imageFile)){
        $imageFile = Config::get('cms::assets_path').'/assets/img/no-image.png';
      }

      $img = Image::cache(function($image) use ($imageFile, $sizeW, $sizeH) {

        if(!$sizeW){ $thumb = $image->make($imageFile)->heighten($sizeH); }
        else if(!$sizeH){ $thumb = $image->make($imageFile)->widen($sizeW); }
        else { $thumb = $image->make($imageFile)->fit($sizeW, $sizeH); }

          return $thumb->encode('data-url');
      }, 10);

      return $img;
    }

    public static function cmslog($action, $data, $datasource_id=null, $entry_id=null, $module=null, $user_id=null)
    {
        $log = new Cmslog;
        $log->action = $action;
        $log->data = is_array($data)?json_encode($data):$data;
        $log->entry_id = $entry_id;
        $log->datasource_id = $datasource_id;
        $log->module = $module;
        $log->user_id = $user_id?$user_id:\Sentry::getUser()->id;
        $log->save();
    }

    public static function checkPermission($requiredPermission) {

        switch (Config::get('cms::config.auth_type')) {
			case 'local':
				if (!\Sentry::getUser()->hasAccess($requiredPermission)){
					return false;
				}

				return true;
				break;

			case 'keycloak':

				$token = Session::get('token');
				$payload_data = JWT::decode($token, null, false);
				if(@!in_array($requiredPermission, $payload_data->resource_access->{Config::get('cms::config.auth_types.keycloak.clientId')}->roles)) {
					return false;
				}

				return true;
				break;

			default:
				return false;
		}
    }
}
