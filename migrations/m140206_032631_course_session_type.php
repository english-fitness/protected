<?php

class m140206_032631_course_session_type extends CDbMigration
{
	public function up()
	{
		$this->addColumn('tbl_course', 'type', "TINYINT( 4 ) NOT NULL DEFAULT '1' COMMENT '1: normal course, 2: timer course, 3: trial course' AFTER `content`");
		$this->addColumn('tbl_session', 'type', "TINYINT( 4 ) NOT NULL DEFAULT '1' COMMENT '1: normal session, 2: timer session, 3: trial session' AFTER `content`");
		$this->addColumn('tbl_session', 'actual_duration', "INT( 11 ) NULL DEFAULT NULL AFTER `actual_end`");
		$this->alterColumn('tbl_session', 'plan_start', "DATETIME NULL DEFAULT NULL");
	}

	public function down()
	{
		echo "m140206_032631_course_session_type does not support migration down.\n";
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