<?php

class m140109_014601_last_login_note extends CDbMigration
{
	public function up()
	{
		$this->addColumn('tbl_user', 'last_login_note', "TEXT NULL DEFAULT NULL AFTER `last_login_time`");
		$this->alterColumn('tbl_student', 'class_id', "INT( 11 ) NULL DEFAULT NULL");
		$this->alterColumn('tbl_student', 'short_description', "VARCHAR( 256 ) NULL DEFAULT NULL");
		$this->alterColumn('tbl_teacher', 'short_description', "VARCHAR( 256 ) NULL DEFAULT NULL");
	}

	public function down()
	{
		$this->alterColumn('tbl_student', 'class_id', "INT( 11 ) NULL DEFAULT NULL");
		$this->alterColumn('tbl_student', 'short_description', "VARCHAR( 256 ) NULL DEFAULT NULL");
		$this->alterColumn('tbl_teacher', 'short_description', "VARCHAR( 256 ) NULL DEFAULT NULL");
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