<?php

use Insomnia\Cms\Models\DatasourceFieldtype as DatasourceFieldtype;

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
}
