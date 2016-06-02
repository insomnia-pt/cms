<?php namespace Insomnia\Cms\Models;

use Eloquent;

class ModelBuilder extends Eloquent {

    protected static $_table;
    protected $guarded = array();


    public static function fromTable($table, $parms = Array()){
        $ret = null;
        if (class_exists($table)){
            $ret = new $table($parms);
        } else {
            $ret = new static($parms);
            $ret->setTable($table);
        }
        return $ret;
    }

    public function setTable($table)
    {
        static::$_table = $table;
    }

    public function getTable()
    {
        return static::$_table;
    }
}