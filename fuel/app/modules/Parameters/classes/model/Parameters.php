<?php
/**
 * Created by PhpStorm.
 * User: mrussell
 * Date: 7/4/14
 * Time: 10:04 PM
 */

namespace Parameters\Model;

class Parameters extends \Model\Module{

    protected static $_table_name = 'parameters';
    protected static $_fields = array(
        'data_type' => array(
            'data_type' => 'varchar',
            'label' => 'Data Type',
            'null' => false,
            'validation' => array(
                'required' => true,
                'max_length' => 50
            ),
            'form' => array(
                'type' => 'select',
                'class' => 'select2',
                'options' => array(),
                'collection' => 'dataTypes'
            )
        ),
        'api_type' => array(
            'data_type' => 'varchar',
            'label' => 'API Type',
            'validation' => array(
                'required' => true,
                'max_length' => 50
            ),
            'form' => array(
                'type' => 'select',
                'class' => 'select2',
                'options' => array(),
                'collection' => 'apiTypes'
            )
        ),
        'description' => array(
            'data_type' => 'varchar',
            'label' => 'Description',
            'validation' => array(
                'max_length' => 500
            ),
            'form' => array(
                'type' => 'textarea'
            ),
        ),
        'url_param' => array(
            'data_type' => 'tinyint',
            'label' => 'URL Parameter?',
            'default' => 0,
            'validation' => array(
                'required' => true,
            ),
            'form' => array(
                'type' => 'checkbox'
            ),
        ),
        'deprecated' => array(
            'data_type' => 'tinyint',
            'label' => 'Deprecated',
            'default' => 0,
            'validation' => array(
                'required' => true,
            ),
            'form' => array(
                'type' => 'checkbox',
                'disabled' => 'disabled'
            ),
        ),
        'version_id' => array(
            'data_type' => 'varchar',
            'label' => 'Version ID',
            'validation' => array(
                'max_length' => 50
            ),
            'form' => false,
        ),
    );
    protected static $_relationship_properties = array(
        'entryPoints' => array(
            'required' => array(
                'data_type' => 'tinyint',
                'label' => 'Required',
                'default' => 0,
                'validation' => array(),
                'form' => array(
                    'type' => 'checkbox'
                ),
            ),
            'order' => array(
                'data_type' => 'int',
                'label' => 'Order',
                'default' => 0,
                'validation' => array(),
                'form' => array(
                    'type' => 'text'
                ),
            ),
            'login_pane' => array(
                'data_type' => 'varchar',
                'label' => 'URL Parameter?',
                'default' => "normal",
                'validation' => array(
                    'max_length' => 10,
                ),
                'form' => array(
                    'type' => 'select',
                    'options' => array(
                        array(
                            'key' => 'normal',
                            'value' => 'normal'
                        ),
                        array(
                            'key' => 'advanced',
                            'value' => 'advanced'
                        )
                    )
                ),
            )
        )
    );
    protected static $_relationships = array(
        'belongs_to' => array(
            'data_type' => array(
                'key_from' => 'data_type',
                'model_to' => 'ParameterTypes\\Model\\ParameterTypes',
                'key_to' => 'id',
                'cascade_save' => true,
                'cascade_delete' => false,
            ),
            'api_type' => array(
                'key_from' => 'api_type',
                'model_to' => 'ParameterTypes\\Model\\ParameterTypes',
                'key_to' => 'id',
                'cascade_save' => true,
                'cascade_delete' => false,
            ),
        ),
        'has_one' => array(
            'version' => array(
                'key_from' => 'version_id',
                'model_to' => 'Versions\\Model\\Parameters',
                'key_to' => 'id',
                'cascade_save' => true,
                'cascade_delete' => false,
            ),
        ),
        'many_many' => array(
            'entryPoints' => array(
                'key_from' => 'id',
                'key_through_from' => 'parameter_id',
                'table_through' => 'entryPoint_parameters',
                'key_through_to' => 'entryPoint_id',
                'model_to' => 'EntryPoints\\Model\\EntryPoints',
                'key_to' => 'id',
                'cascade_save' => true,
                'cascade_delete' => false,
            ),
        )
    );

    public function getEntryPointParam($param,$entryPoint,$url=""){
        $query = \DB::select('P.id','P.data_type','P.api_type','P.name','P.description','P.url_param',array('EPP.id','related_entryPoint'),'EPP.required','EPP.order','EPP.login_pane')->from(array('parameters','P'));
        $query->join(array('entryPoint_parameters','EPP'),'INNER')->on('P.id','=','EPP.parameter_id');
        $query->where('P.id',$param);
        $query->and_where('EPP.entryPoint_id',$entryPoint);
        if ($url!=""){
            if ($url==false){
                $query->and_where('P.url_param',"0");
            }else{
                $query->and_where('P.url_param',"1");
            }
        }
        return $query->execute(self::$_connection)->as_array();
    }
} 