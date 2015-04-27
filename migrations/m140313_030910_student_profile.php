<?php

class m140313_030910_student_profile extends CDbMigration
{
	public function up()
	{
		$this->addColumn('tbl_student', 'father_name', "VARCHAR( 128 ) NULL DEFAULT NULL AFTER `description`");
		$this->addColumn('tbl_student', 'mother_name', "VARCHAR( 128 ) NULL DEFAULT NULL AFTER `description`");
		$this->addColumn('tbl_student', 'father_phone', "VARCHAR( 20 ) NULL DEFAULT NULL AFTER `description`");
		$this->addColumn('tbl_student', 'mother_phone', "VARCHAR( 20 ) NULL DEFAULT NULL AFTER `description`");
	}

	public function down()
	{
		echo "m140313_030910_student_profile does not support migration down.\n";
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