<?php

class m140418_073030_preset_course extends CDbMigration
{
	public function up()
	{
		//Add preset_course_id to precourse table
		$this->addColumn('tbl_preregister_course', 'preset_course_id', "INT( 11 ) NULL DEFAULT NULL AFTER `course_id`");
		//tbl_preset_course table
		$this->createTable('tbl_preset_course', array(
            'id' => 'pk',
			'subject_id' => 'INT NOT NULL',
			'teacher_id' => 'INT NOT NULL',
            'title' => 'VARCHAR(256) NOT NULL',
			'short_description' => 'TEXT NULL',
			'description' => 'TEXT NULL',
			'price_per_student' => 'FLOAT NOT NULL',
			'min_student' => 'TINYINT NOT NULL',
			'max_student' => 'TINYINT NOT NULL',
			'total_of_session' => 'TINYINT NOT NULL',
			'start_date' => 'DATE NOT NULL',
			'session_per_week' => 'TEXT NOT NULL',
			'status' => 'TINYINT NOT NULL DEFAULT 0',
			'course_id' => 'INT NULL',
			'created_date' => 'DATETIME NOT NULL',
			'modified_date' => 'DATETIME NULL',
			'deleted_flag' => 'TINYINT NOT NULL DEFAULT 0',
        ));
	}

	public function down()
	{
		echo "m140418_073030_preset_course does not support migration down.\n";
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