<?php

namespace Fuel\Migrations;

class Hotels
{
    function up()
    {
        \DBUtil::create_table('hotels', array(
            'id' => array('type' => 'int', 'auto_increment' => true),
            'name' => array('type' => 'varchar', 'constraint' => 255),
            'prefecture_id' => array('type' => 'int', 'null' => true),
            'file_path' => array('type' => 'text', 'null' => true),
            'status' => array('type' => 'tinyint'),
            'created_at' => array('type' => 'timestamp', 'default' => \DB::expr('CURRENT_TIMESTAMP')),
            'updated_at' => array('type' => 'timestamp', 'null' => true, 'on update' => \DB::expr('CURRENT_TIMESTAMP')),
        ), array('id'));
    }

    function down()
    {
        \DBUtil::drop_table('hotels');
    }
}

