<?php

namespace Fuel\Migrations;

class Prefectures
{
    function up()
    {
        \DBUtil::create_table('roles', array(
            'id' => array('type' => 'int', 'auto_increment' => true),
            'name' => array('type' => 'varchar', 'constraint' => 255, 'comment' => 'role name'),
            'status' => array('type' => 'tinyint'),
            'created_at' => array('type' => 'timestamp', 'default' => \DB::expr('CURRENT_TIMESTAMP')),
            'updated_at' => array('type' => 'timestamp', 'null' => true, 'on update' => \DB::expr('CURRENT_TIMESTAMP')),
        ), array('id'));
    }

    function down()
    {
        \DBUtil::drop_table('roles');
    }
}
