<?php

class m140211_094550_preregister_course extends CDbMigration
{
	public function up()
	{
		$this->addColumn('tbl_session', 'payment_type', "TINYINT NOT NULL DEFAULT 0 COMMENT '0: free, 1: paid, 2: refund' AFTER `type`");
		//Create tpl_preregister_course table
		$this->createTable('tbl_preregister_course', array(
		  'id' => 'INT NOT NULL',
		  'student_id' => 'INT NOT NULL',
		  'subject_id' => 'INT NOT NULL',
		  'title' => 'VARCHAR(256) NOT NULL',
		  'note' => 'TEXT NULL',
		  'total_of_student' => 'TINYINT NOT NULL DEFAULT 1',
		  'start_date' => 'DATETIME NOT NULL',
		  'total_of_session' => 'INT NOT NULL',
		  'session_per_week' => 'TEXT NOT NULL',
		  'status' => "TINYINT NOT NULL DEFAULT 0 COMMENT '0: waiting, 1: approved, 2: denied'",
		  'created_date' => "DATETIME NOT NULL",
		  'PRIMARY KEY (`id`)',
        ));
	}

	public function down()
	{
		echo "m140211_094550_preregister_course does not support migration down.\n";
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