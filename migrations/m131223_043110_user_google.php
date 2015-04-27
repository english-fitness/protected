<?php

class m131223_043110_user_google extends CDbMigration
{
	public function up()
	{
		//Change column to tbl_email_queue
		$this->alterColumn('tbl_email_queue', 'sent_date', "DATETIME NULL DEFAULT NULL");
		//Create tbl_user_google table
		$this->createTable('tbl_user_google', array(
			'user_id' => 'int(11) NOT NULL',
			'google_id' => 'VARCHAR(25) DEFAULT NULL',
			'google_name' => 'VARCHAR(256) DEFAULT NULL',
			'google_email'=> "VARCHAR(256) DEFAULT NULL",
			'google_access_token' => 'VARCHAR(128) DEFAULT NULL',
			'google_connected'=> "TINYINT(1) DEFAULT '0' COMMENT '0: not connected; 1: connected'",
			'google_link' => 'VARCHAR(256) DEFAULT NULL',
			'google_picture_link' => 'VARCHAR(256) DEFAULT NULL',			
			'created_date' => 'DATETIME NOT NULL',
			'modified_date' => 'DATETIME DEFAULT NULL',
			'PRIMARY KEY (`user_id`)',
			'KEY `fk_google_user` (`user_id`)',
			'CONSTRAINT `fk_google_user` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION',
		));
	}

	public function down()
	{
		echo "m131223_043110_user_google does not support migration down.\n";
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