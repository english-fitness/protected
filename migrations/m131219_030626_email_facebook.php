<?php

class m131219_030626_email_facebook extends CDbMigration
{
	public function up()
	{
		//Add to columns to tbl_user
		$this->addColumn('tbl_user', 'reset_password_code', "VARCHAR( 128 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL AFTER `activation_expired`");
		$this->addColumn('tbl_user', 'reset_password_expired', "DATETIME NULL DEFAULT NULL AFTER `reset_password_code`");
		
		//Create tbl_email_queue table
		$this->createTable('tbl_email_queue', array(
            'id' => 'pk',
            'subject' => 'VARCHAR(256) NOT NULL',
            'content' => 'TEXT NOT NULL',
			'receiver_address'=> 'VARCHAR(256) NOT NULL',
			'sent_date' => 'DATETIME NOT NULL',
			'created_date' => 'DATETIME NOT NULL',
			'status'=> "TINYINT(4) NOT NULL DEFAULT '0' COMMENT '0: pending; 1: sending; 2: sent'",
        ));
        
        //Create tbl_user_facebook table
		$this->createTable('tbl_user_facebook', array(
            'user_id' => 'int(11) NOT NULL',
            'facebook_id' => 'VARCHAR(20) DEFAULT NULL',
			'facebook_username' => 'VARCHAR(256) DEFAULT NULL',
            'facebook_access_token' => 'VARCHAR(128) DEFAULT NULL',
			'facebook_connected'=> "TINYINT(1) DEFAULT '0' COMMENT '0: not connected; 1: connected'",
			'facebook_name' => 'VARCHAR(256) DEFAULT NULL',
			'facebook_link_profile' => 'VARCHAR(256) DEFAULT NULL',
			'facebook_email'=> "VARCHAR(256) DEFAULT NULL",
			'created_date' => 'DATETIME NOT NULL',
			'modified_date' => 'DATETIME DEFAULT NULL',
			'PRIMARY KEY (`user_id`)',
  			'KEY `fk_facebook_user` (`user_id`)',
			'CONSTRAINT `fk_facebook_user` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION',
        ));
	}

	public function down()
	{
		echo "m131219_030626_email_facebook does not support migration down.\n";
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