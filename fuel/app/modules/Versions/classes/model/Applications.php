<?php
/**
 * Created by PhpStorm.
 * User: mrussell
 * Date: 3/17/15
 * Time: 10:08 AM
 */

namespace Versions\Model;


class Applications extends \Model\Versions{

    protected static $_table_name = 'application_versions';
    protected static $_relationships = array(
        'belongs_to' => array(
            'application' => array(
                'key_from' => 'id',
                'model_to' => 'Applications\\Model\\Applications',
                'key_to' => 'version_id',
                'cascade_save' => true,
                'cascade_delete' => false,
            )
        ),
        'has_one' => array(
            'related_module' => array(
                'key_from' => 'related_id',
                'model_to' => 'Applications\\Model\\Applications',
                'key_to' => 'id',
                'cascade_save' => true,
                'cascade_delete' => false,
            )
        )
    );

}