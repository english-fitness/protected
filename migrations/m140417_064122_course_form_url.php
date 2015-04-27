<?php

class m140417_064122_course_form_url extends CDbMigration
{
	public function up()
	{
		//Add to course form urls
		$this->addColumn('tbl_course', 'teacher_form_url', "VARCHAR( 256 ) NULL DEFAULT NULL AFTER `payment_type`");
		$this->addColumn('tbl_course', 'student_form_url', "VARCHAR( 256 ) NULL DEFAULT NULL AFTER `payment_type`");
	}

	public function down()
	{
		echo "m140417_064122_course_form_url does not support migration down.\n";
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