<?php

namespace Oauth\Model;

class RefreshTokens extends \Model\Oauth {

    protected static $_table_name = 'oauth_refresh_tokens';
    protected static $_fields = array(
        'refresh_token' => array(
            'data_type' => 'varchar',
            'label' => 'Access Token',
            'null' => false,
            'validation' => array(
                'required' => true,
                'max_length' => 255
            ),
        ),
        'access_token_id' => array(
            'data_type' => 'varchar',
            'label' => 'Access Token ID',
            'null' => false,
            'validation' => array(
                'required' => true,
                'max_length' => 50
            ),
        ),
        'expire_time' => array(
            'data_type' => 'int',
            'label' => 'Expire Time',
            'null' => false,
            'unsigned' => true,
            'validation' => array(
                'required' => true,
                'max_length' => 11
            ),
        ),
    );
    protected static $_relationships = array(
        'belongs_to' => array(
            'accessToken' => array(
                'key_from' => 'access_token_id',
                'model_to' => 'Oauth\\Model\\AccessTokens',
                'key_to' => 'id',
                'cascade_save' => false,
                'cascade_delete' => true,
            )
        ),
        'has_one' => array(),
        'has_many' => array(),
        'many_many' => array()
    );
}
