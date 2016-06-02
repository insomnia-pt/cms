<?php
class Helpers {

    public static function getSlug($title, $model)
    {
        $slug = Str::slug($title);
        $slugCount = count( $model->whereRaw("slug REGEXP '^{$slug}(-[0-9]*)?$'")->get() );
     
        return ($slugCount > 0) ? "{$slug}-{$slugCount}" : $slug;
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
}