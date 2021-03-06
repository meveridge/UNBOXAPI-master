<?php
/**
 * Created by PhpStorm.
 * User: mrussell
 * Date: 3/17/15
 * Time: 12:40 AM
 */

namespace Model;


class Oauth extends \Orm\Model{

    protected static $_connection = 'default';
    protected static $_table_name;
    protected static $_primary_key = array('id');
    //fields array is an override array for properties
    protected static $_fields = array(
        'id' => array(
            'data_type' => 'varchar',
            'label' => 'ID',
            'null' => false,
            'validation' => array(
                'required' => true,
                'max_length' => 50
            ),
        ),
        'date_created' => array(
            'data_type' => 'datetime',
            'label' => 'Date Created',
            'validation' => array(),
            'form' => array(
                'type' => 'text',
                'disabled' => 'disabled'
            ),
        ),
        'date_modified' => array(
            'data_type' => 'datetime',
            'label' => 'Date Created',
            'validation' => array(),
            'form' => array(
                'type' => 'text',
                'disabled' => 'disabled'
            ),
        ),
    );
    protected static $_properties = array();

    //Some relatioships require extra properties on the table
    // Define them in the $_relationship_properties array
    // Key = relationship name
    // Value = Array of field properties, similar to $_properties/$_fields
    protected static $_relationship_properties = array();
    //relationshps is an override array for all relationship arrays
    protected static $_relationships = array(
        'belongs_to' => array(),
        'has_one' => array(),
        'has_many' => array(),
        'many_many' => array()
    );
    protected static $_belongs_to = array();
    protected static $_has_one = array();
    protected static $_has_many = array();
    protected static $_many_many = array();
    //hooks is an override array for extra observers
    protected static $_hooks = array();
    protected static $_observers = array(
        'Orm\\Observer_CreatedAt' => array(
            'events' => array('before_insert'),
            'mysql_timestamp' => true,
            'property' => 'date_created',
            'overwrite' => false,
        ),
        'Orm\\Observer_UpdatedAt' => array(
            'events' => array('before_save'),
            'mysql_timestamp' => true,
            'property' => 'date_modified'
        ),
        '\\UNBOXAPI\\Observer_Guid' => array(
            'events' => array('before_insert'),
        ),
    );

    public static function properties(){
        self::$_properties = array_merge(self::$_fields,static::$_fields);
        return parent::properties();
    }
    public static function relations($specific = false){
        if (isset(static::$_relationships['belongs_to'])) {
            self::$_belongs_to = array_merge(self::$_relationships['belongs_to'],static::$_relationships['belongs_to']);
        }else{
            self::$_belongs_to = self::$_relationships['belongs_to'];
        }
        if (isset(static::$_relationships['has_one'])) {
            self::$_has_one = array_merge(self::$_relationships['has_one'],static::$_relationships['has_one']);
        }else{
            self::$_has_one = self::$_relationships['has_one'];
        }
        if (isset(static::$_relationships['has_many'])) {
            self::$_has_many = array_merge(self::$_relationships['has_many'],static::$_relationships['has_many']);
        }else{
            self::$_has_many = self::$_relationships['has_many'];
        }
        if (isset(static::$_relationships['many_many'])) {
            self::$_many_many = array_merge(self::$_relationships['many_many'],static::$_relationships['many_many']);
        }else{
            self::$_many_many = self::$_relationships['many_many'];
        }
        return parent::relations($specific);
    }
    public static function relationship_properties($relationship = null){
        if ($relationship!==null){
            return static::$_relationship_properties[$relationship];
        }
        return static::$_relationship_properties;
    }
    public static function fields(){
        $properties = static::properties();
        $fields = array();
        foreach ($properties as $field=>$attributes){
            $fields[$field] = array(
                'data_type' => $attributes['data_type'],
                'label' => $attributes['label'],
                'auto_inc' => isset($attributes['auto_inc'])?$attributes['auto_inc']:false,
                'required' => isset($attributes['validation']['required'])?$attributes['validation']['required']:false,
                'validation' => isset($attributes['validation'])?$attributes['validation']:array(),
                'form' => isset($attributes['form'])?$attributes['form']:array()
            );
        }
        return $fields;
    }
    public static function relationships(){
        $relations = static::relations();
        $relationships = array();
        foreach ($relations as $relationshipName=>$relationshipObject){
            if (strpos(get_class($relationshipObject),"ManyMany")) {
                $type = "ManyMany";
            }else if (strpos(get_class($relationshipObject),"HasMany")) {
                $type = "HasMany";
            }else if (strpos(get_class($relationshipObject),"HasOne")) {
                $type = "HasOne";
            }else if (strpos(get_class($relationshipObject),"BelongsTo")) {
                $type = "BelongsTo";
            }
            $model = $relationshipObject->__get("model_to");
            $arr = explode($model,'\\');
            $module = $arr[0];
            $relationships[$relationshipName] = array(
                'type' => $type,
                'module' => $module
            );
            if (isset(static::$_relationship_properties[$relationshipName])) {
                $relationships[$relationshipName]['fields'] = static::$_relationship_properties[$relationshipName];
            }
            unset($arr);
        }
        return $relationships;
    }

}