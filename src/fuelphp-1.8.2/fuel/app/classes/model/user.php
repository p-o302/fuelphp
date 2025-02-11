<?php

class Model_User extends Orm\Model
{
    protected static $_properties = [
        'id',
        'username' => [
            'data_type' => 'varchar',
            'label' => 'Name',
            'validation' => [
                'required',
                'min_length' => [2],
                'max_length' => [255],
            ],
        ],
        'password' => [
            'data_type' => 'varchar',
            'label' => 'Password',
            'validation' => [
                'required',
                'min_length' => [6],
            ],
        ],
        'role_id',
        'group_id',
        'email' => [
            'data_type' => 'varchar',
            'label' => 'Email',
            'validation' => [
                'required',
                'valid_email',
                'unique',
            ],
        ],
        'last_login',
        'previous_login',
        'login_hash',
        'reset_token',
        'reset_token_expires_at',
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

    protected static $_to_array_exclude = [
        'password'
    ];
    protected static $_timestamp = true;

    protected static $_has_many = [
        'booking_list' => [
            'key_from' => 'id',
            'model_to' => 'Model_Booking',
            'key_to' => 'user_id',
            'cascade_save' => true,
            'cascade_delete' => false,
        ],
    ];

    protected static $_belongs_to = [
        'role' => [
            'key_from' => 'role_id', 
            'model_to' => 'Model_Role',
            'key_to' => 'id',
            'cascade_save' => true,
            'cascade_delete' => false,
        ],
    ];

}
