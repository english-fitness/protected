<?php

class m140507_072613_add_created_user extends CDbMigration
{
	public function up()
	{
		$this->addColumn('tbl_preset_course', 'created_user_id', "INT( 11 ) NULL DEFAULT NULL AFTER `course_id`");
		$this->addColumn('tbl_session', 'status_note', "VARCHAR( 256 ) NULL DEFAULT NULL AFTER `status`");
		$this->addColumn('tbl_preregister_course', 'created_user_id', "INT( 11 ) NULL DEFAULT NULL AFTER `course_id`");
	}

	public function down()
	{
		echo "m140507_072613_add_created_user does not support migration down.\n";
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