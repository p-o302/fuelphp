<?php

class Model_Role extends Orm\Model
{
    protected static $_properties = [
        'id',
        'name' => [
            'data_type' => 'varchar',
            'label' => 'Role name',
            'validation' => [
                'required',
                'min_length' => [2],
                'max_length' => [255],
            ],
        ],
        'status' => [
            'data_type' => 'tinyint',
            'label' => 'Status',
            'validation' => [
                'required',
                'in_array' => [[0, 1]],
            ],
            'default' => 1,
        ],
        'created_at' => [
            'data_type' => 'timestamp',
            'label' => 'Created At',
            'form' => [
                'type' => false,
            ],
        ],
        'updated_at' => [
            'data_type' => 'timestamp',
            'label' => 'Updated At',
            'form' => [
                'type' => false,
            ],
        ],
    ];

    protected static $_has_many = [
        'users' => [
            'key_from' => 'id',
            'model_to' => 'Model_User',
            'key_to' => 'role_id',
            'cascade_save' => true,
            'cascade_delete' => false,
        ],
    ];
}