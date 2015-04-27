<?php

class m140509_063812_user_permission extends CDbMigration
{
	public function up()
	{
		//tbl_permission table
		$this->createTable('tbl_permission', array(
            'id' => 'pk',
            'title' => 'VARCHAR(256) NOT NULL',
			'controller' => 'VARCHAR(80) NOT NULL',
			'action' => 'VARCHAR(80) NOT NULL',
			'description' => 'TEXT NULL',
        ));
        //tbl_user_permission table
		$this->createTable('tbl_user_permission', array(
            'user_id' => 'int(11) NOT NULL',
            'permission_id' => 'INT(11) NOT NULL',
            'PRIMARY KEY (`user_id`, `permission_id`)',
        ));
	}

	public function down()
	{
		echo "m140509_063812_user_permission does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}