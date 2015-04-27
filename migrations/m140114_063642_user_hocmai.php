<?php

class m140114_063642_user_hocmai extends CDbMigration
{
	public function up()
	{
		//Create tbl_user_hocmai table
		$this->createTable('tbl_user_hocmai', array(
			'user_id' => 'int(11) NOT NULL',
			'hocmai_id' => 'VARCHAR(25) DEFAULT NULL',
			'hocmai_email'=> "VARCHAR(256) DEFAULT NULL",
			'hocmai_username' => 'VARCHAR(256) DEFAULT NULL',
			'hocmai_password' => 'VARCHAR(256) DEFAULT NULL',
			'hocmai_user_type'=> "TINYINT(1) DEFAULT '2' COMMENT '1: phu huynh; 2: hoc sinh; 3: giao vien'",
			'hocmai_fullname' => 'VARCHAR(256) DEFAULT NULL',
			'hocmai_access_token' => 'VARCHAR(128) DEFAULT NULL',
			'hocmai_connected'=> "TINYINT(1) DEFAULT '0' COMMENT '0: not connected; 1: connected'",
			'hocmai_gender' => "TINYINT(1) DEFAULT '1' COMMENT '1: male; 0: female'",
			'hocmai_province' => 'VARCHAR(256) DEFAULT NULL',
			'hocmai_phone' => 'VARCHAR(20) DEFAULT NULL',
			'hocmai_mobile' => 'VARCHAR(20) DEFAULT NULL',
			'hocmai_address' => 'VARCHAR(256) DEFAULT NULL',
			'created_date' => 'DATETIME NOT NULL',
			'modified_date' => 'DATETIME DEFAULT NULL',
			'PRIMARY KEY (`user_id`)',
			'KEY `fk_hocmai_user` (`user_id`)',
			'CONSTRAINT `fk_hocmai_user` FOREIGN KEY (`user_id`) REFERENCES `tbl_user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION',
		));
	}

	public function down()
	{
		echo "m140114_063642_user_hocmai does not support migration down.\n";
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