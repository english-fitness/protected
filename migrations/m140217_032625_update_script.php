<?php

class m140217_032625_update_script extends CDbMigration
{
	public function up()
	{
		//Preregister course table update
		$this->addColumn('tbl_preregister_course', 'teacher_id', "INT( 11 ) NULL DEFAULT NULL AFTER `status`");
		$this->addColumn('tbl_preregister_course', 'course_type', "TINYINT( 4 ) NOT NULL DEFAULT '1' COMMENT '0: trial course, 1: normal course' AFTER `course_id`");
		$this->addColumn('tbl_preregister_course', 'payment_type', "TINYINT( 4 ) NOT NULL DEFAULT '0' COMMENT '0: free, 1: not free' AFTER `course_id`");
		$this->addColumn('tbl_preregister_course', 'payment_status', "TINYINT( 4 ) NOT NULL DEFAULT '0' COMMENT '0: not paid yet, 1: paid, 2: refund' AFTER `course_id`");
		$this->addColumn('tbl_preregister_course', 'final_price', "FLOAT ( 20 ) NOT NULL DEFAULT '0' AFTER `course_id`");
		
		//Course table update
		$this->addColumn('tbl_course', 'payment_type', "TINYINT( 4 ) NOT NULL DEFAULT '0' COMMENT '0: free, 1: not free' AFTER `status`");
		$this->addColumn('tbl_course', 'payment_status', "TINYINT( 4 ) NOT NULL DEFAULT '0' COMMENT '0: not paid yet, 1: paid, 2: refund' AFTER  `status`");
		$this->addColumn('tbl_course', 'final_price', "FLOAT ( 20 ) NOT NULL DEFAULT '0' AFTER `status`");
		$this->addColumn('tbl_course', 'total_of_student', "TINYINT( 4 ) NOT NULL DEFAULT '1' AFTER `status`");
		$this->dropColumn('tbl_course', 'shareable');
		
		//Session table update
		$this->addColumn('tbl_session', 'payment_status', "TINYINT( 4 ) NOT NULL DEFAULT '0' COMMENT '0: not paid yet, 1: paid, 2: refund' AFTER `status`");
		$this->addColumn('tbl_session', 'final_price', "FLOAT ( 20 ) NOT NULL DEFAULT '0' AFTER `status`");
		$this->addColumn('tbl_session', 'total_of_student', "TINYINT( 4 ) NOT NULL DEFAULT '1' AFTER `status`");
		
		//Message table update
		$this->addColumn('tbl_message', 'recipient_email', "TEXT DEFAULT NULL");
		$this->dropColumn('tbl_message', 'receiver_status');
		$this->dropForeignKey('fk_message_receiver', 'tbl_message');
		$this->dropColumn('tbl_message', 'receiver_id');
		
		//Create tbl_message_status table
		$this->createTable('tbl_message_status', array(
			'id' => 'pk',
			'message_id' => 'INT(11) NOT NULL',
			'recipient_id' => 'INT(11) NOT NULL',
			'read_flag'=> "TINYINT(4) NOT NULL DEFAULT '0' COMMENT '0: not read, 1: read'",			
			'read_date' => 'DATE NULL DEFAULT NULL',
		));
		//Add index to some column in message status table
		$this->createIndex('idx_message_status_message', 'tbl_message_status', 'message_id');
		$this->createIndex('idx_message_status_recipient', 'tbl_message_status', 'recipient_id');
		$this->createIndex('read_flag', 'tbl_message_status', 'read_flag');
	}

	public function down()
	{
		echo "m140217_032625_update_script does not support migration down.\n";
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