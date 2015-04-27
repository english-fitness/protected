<?php

class m140602_031731_preregister_user extends CDbMigration
{
	public function up()
	{
		//Add some column to student user
		$this->addColumn('tbl_student', 'sale_status', "VARCHAR(80) NULL AFTER `father_name`");
		$this->addColumn('tbl_student', 'sale_note', "TEXT NULL AFTER `father_name`");
		$this->addColumn('tbl_student', 'sale_user_id', "INT NULL AFTER `father_name`");
		$this->addColumn('tbl_student', 'last_sale_date', "DATE NULL AFTER `father_name`");
		$this->addColumn('tbl_student', 'care_status', "TINYINT NOT NULL DEFAULT 0 AFTER `father_name`");
		
		//tbl_preregister_user table
		$this->createTable('tbl_preregister_user', array(
            'id' => 'pk',
			'email' => 'VARCHAR(128) NULL',
            'fullname' => 'VARCHAR(256) NOT NULL',
			'birthday' => 'DATE NULL',
			'gender' => 'TINYINT NULL DEFAULT NULL',
			'address' => 'VARCHAR(256) NULL',
			'phone' => 'VARCHAR(20) NULL',
			'class_name' => "VARCHAR(80) NULL COMMENT 'Khối lớp đang học'",
			'parent_name' => 'VARCHAR(256) NULL',
			'parent_phone' => 'VARCHAR(80) NULL',
			'subject_note' => "VARCHAR(256) NULL COMMENT 'Môn học muốn được gia sư'",
			'objective' => "VARCHAR(256) NULL COMMENT 'Mục tiêu học tập'",
			'content_request' => "TEXT NULL COMMENT 'Nội dung học tập'",
			'teacher_request' => "TEXT NULL COMMENT 'Yêu cầu giáo viên'",
			'user_type' => "TINYINT NULL DEFAULT 0 COMMENT '0: Học sinh, 1: Giáo viên, 2: Phụ huynh'",
			'status' => 'TINYINT NOT NULL DEFAULT 0',
			'care_status' => "TINYINT NOT NULL DEFAULT 0",
			'sale_status' => "VARCHAR(80) NULL COMMENT 'Trạng thái sale'",
			'sale_note' => "TEXT NULL",
			'sale_user_id' => "INT NULL",
			'last_sale_date' => "DATE NULL",
			'refer_user_id' => "INT NULL",
			'deleted_flag' => "TINYINT NOT NULL DEFAULT '0'",
			'created_user_id' => 'INT NULL',
			'modified_user_id' => 'INT NULL',
			'created_date' => "DATETIME NOT NULL",
			'modified_date' => "DATETIME NULL",
        ));
        
        //tbl_user_sales_history table
		$this->createTable('tbl_user_sales_history', array(
            'id' => 'pk',
			'user_id' => 'INT NULL',
            'preregister_user_id' => 'INT NULL',
			'sale_date' => 'DATETIME NULL',
			'next_sale_date' => 'DATE NULL',
			'sale_note' => 'VARCHAR(256) NULL',
			'sale_status' => 'VARCHAR(80) NULL DEFAULT NULL',
			'sale_question' => 'VARCHAR(256) NULL',
			'user_answer' => "VARCHAR(256) NULL",
			'created_user_id' => 'INT NULL',
			'modified_user_id' => 'INT NULL',
			'deleted_flag' => "TINYINT NOT NULL DEFAULT '0'",
			'created_date' => "DATETIME NOT NULL",
			'modified_date' => "DATETIME NULL",
        ));
	}

	public function down()
	{
		echo "m140602_031731_preregister_user does not support migration down.\n";
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