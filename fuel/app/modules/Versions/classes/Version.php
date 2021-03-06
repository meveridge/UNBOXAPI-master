<?php
/**
 * Created by PhpStorm.
 * User: mrussell
 * Date: 3/17/15
 * Time: 12:00 AM
 */

namespace Versions;


class Version extends \UNBOXAPI\Module {

    protected static $_name = "Versions";
    protected static $_label = 'Version';
    protected static $_label_plural = 'Versions';
    protected static $_enabled = false;
    protected static $_options = "";

    public $version;
    public $related_module;
    public $related_id;

    function __construct($module){
        $model = static::model($module);
        $this->model = new $model();
    }

    public static function model($module = null){
        if ($module == null){
            return "Model\\Versions";
        }
        $name = static::$_name;
        return "$name\\Model\\$module";
    }
}