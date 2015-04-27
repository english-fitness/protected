<?php

class m140411_102051_quiz_tables extends CDbMigration
{
	public function up()
	{
		//Add to columns to tbl_user
		$this->addColumn('tbl_subject', 'allow_to_teach', "TINYINT( 4 ) NOT NULL DEFAULT '0' AFTER `name`");
		//tbl_quiz_topic table
		$this->createTable('tbl_quiz_topic', array(
            'id' => 'pk',
			'subject_id' => 'INT NOT NULL',
            'name' => 'VARCHAR(256) NOT NULL',
            'parent_id' => 'INT NOT NULL DEFAULT 0',
			'parent_path' => 'VARCHAR(256) NULL DEFAULT NULL',
			'content'=> 'MEDIUMTEXT NULL DEFAULT NULL',
			'status'=> "TINYINT( 4 ) NOT NULL DEFAULT '0' COMMENT '0: pending, 1: approved'",
			'deleted_flag' => 'TINYINT NOT NULL DEFAULT 0',
        ));
        //tbl_quiz_item table
		$this->createTable('tbl_quiz_item', array(
            'id' => 'pk',
			'subject_id' => 'INT NOT NULL',
			'parent_id' => "INT NOT NULL DEFAULT '0'",
            'content' => 'MEDIUMTEXT NOT NULL',
            'tags' => 'VARCHAR(256) NULL DEFAULT NULL',
			'suggestion'=> 'TEXT NULL DEFAULT NULL',
			'explaination'=> 'MEDIUMTEXT NULL DEFAULT NULL',
			'answers'=> 'TEXT NULL DEFAULT NULL',
			'correct_answer'=> 'VARCHAR(10) NULL DEFAULT NULL',
			'level'=> "TINYINT( 4 ) NOT NULL DEFAULT '0' COMMENT '0: trung binh, 1: kha, 2: gioi'",
			'status'=> "TINYINT( 4 ) NOT NULL DEFAULT '0' COMMENT '0: pending, 1: approved'",
			'deleted_flag' => 'TINYINT NOT NULL DEFAULT 0',
        ));
        //tbl_quiz_item_topic table
		$this->createTable('tbl_quiz_item_topic', array(
            'quiz_item_id' => 'INT NOT NULL',
            'quiz_topic_id' => 'INT NOT NULL',
            'PRIMARY KEY (`quiz_item_id`, `quiz_topic_id`)',
        ));
        //tbl_quiz_exam table
		$this->createTable('tbl_quiz_exam', array(
            'id' => 'pk',
			'subject_id' => 'INT NOT NULL',
            'name' => 'VARCHAR(256) NOT NULL',
			'type'=> "TINYINT( 4 ) NOT NULL DEFAULT '0' COMMENT '0: on tap, 1: de thi'",
			'duration'=> "INT NULL DEFAULT 90 COMMENT 'in minute'",
			'level'=> "TINYINT( 4 ) NOT NULL DEFAULT '0' COMMENT '0: trung binh, 1: kha, 2: gioi'",
			'status'=> "TINYINT( 4 ) NOT NULL DEFAULT '0' COMMENT '0: pending, 1: approved'",
			'deleted_flag' => 'TINYINT NOT NULL DEFAULT 0',
        ));
        //tbl_quiz_exam_item table
		$this->createTable('tbl_quiz_exam_item', array(
            'quiz_exam_id' => 'INT NOT NULL',
            'quiz_item_id' => 'INT NOT NULL',
			'item_id_order' => 'TINYINT NOT NULL DEFAULT 0',
            'PRIMARY KEY (`quiz_exam_id`, `quiz_item_id`)',
        ));
        //tbl_quiz_exam_topic table
		$this->createTable('tbl_quiz_exam_topic', array(
            'quiz_exam_id' => 'INT NOT NULL',
            'quiz_topic_id' => 'INT NOT NULL',
			'PRIMARY KEY (`quiz_exam_id`, `quiz_topic_id`)',
        ));
        //tbl_quiz_exam_history table
		$this->createTable('tbl_quiz_exam_history', array(
            'student_id' => 'INT NOT NULL',
            'quiz_exam_id' => 'INT NOT NULL',
            'correct_percent' => "FLOAT NOT NULL DEFAULT 0",
			'status'=> "TINYINT( 4 ) NOT NULL DEFAULT '0' COMMENT '0: pending, 1: working, 2:ended'",
			'actual_start' => 'DATETIME NULL DEFAULT NULL',
			'actual_end' => 'DATETIME NULL DEFAULT NULL',
			'created_date'=> "DATETIME NOT NULL",
			'modified_date' => 'DATETIME NULL',
			'PRIMARY KEY (`student_id`, `quiz_exam_id`)',
        ));
        //tbl_quiz_item_history table
		$this->createTable('tbl_quiz_item_history', array(
            'student_id' => 'INT NOT NULL',
            'quiz_item_id' => 'INT NOT NULL',
			'answer' => 'VARCHAR(10) NULL DEFAULT NULL',
            'is_correct' => "TINYINT NOT NULL DEFAULT 0",
			'PRIMARY KEY (`student_id`, `quiz_item_id`)',
        ));
	}

	public function down()
	{
		echo "m140320_095040_quiz_tables does not support migration down.\n";
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