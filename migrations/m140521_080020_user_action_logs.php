<?php

class m140521_080020_user_action_logs extends CDbMigration
{
	public function up()
	{
		//Course modified user
		$this->addColumn('tbl_course', 'modified_user_id', "INT( 11 ) NULL DEFAULT NULL AFTER `modified_date`");
		//Message modified user
		$this->addColumn('tbl_message', 'modified_date', "DATETIME NULL AFTER `created_date`");
		$this->addColumn('tbl_message', 'modified_user_id', "INT( 11 ) NULL DEFAULT NULL AFTER `created_date`");
		//Notification modified user
		$this->addColumn('tbl_notification', 'created_user_id', "INT( 11 ) NULL DEFAULT NULL AFTER `created_date`");
		$this->addColumn('tbl_notification', 'modified_date', "DATETIME NULL AFTER `created_date`");
		$this->addColumn('tbl_notification', 'modified_user_id', "INT( 11 ) NULL DEFAULT NULL AFTER `created_date`");
		//Preregister course modified user
		$this->addColumn('tbl_preregister_course', 'modified_date', "DATETIME NULL AFTER `created_date`");
		$this->addColumn('tbl_preregister_course', 'modified_user_id', "INT( 11 ) NULL DEFAULT NULL AFTER `created_date`");
		//Preregister payment modified user
		$this->addColumn('tbl_preregister_payment', 'modified_user_id', "INT( 11 ) NULL DEFAULT NULL AFTER `created_date`");
		//Preset course modified user
		$this->addColumn('tbl_preset_course', 'modified_user_id', "INT( 11 ) NULL DEFAULT NULL AFTER `created_date`");
		//Quiz Exam modified user
		$this->addColumn('tbl_quiz_exam', 'created_date', "DATETIME NULL AFTER `status`");
		$this->addColumn('tbl_quiz_exam', 'modified_date', "DATETIME NULL AFTER `status`");
		$this->addColumn('tbl_quiz_exam', 'created_user_id', "INT( 11 ) NULL DEFAULT NULL AFTER `status`");
		$this->addColumn('tbl_quiz_exam', 'modified_user_id', "INT( 11 ) NULL DEFAULT NULL AFTER `status`");
		//Quiz Item modified user
		$this->addColumn('tbl_quiz_item', 'created_date', "DATETIME NULL AFTER `status`");		
		$this->addColumn('tbl_quiz_item', 'modified_date', "DATETIME NULL AFTER `status`");
		$this->addColumn('tbl_quiz_item', 'created_user_id', "INT( 11 ) NULL DEFAULT NULL AFTER `status`");
		$this->addColumn('tbl_quiz_item', 'modified_user_id', "INT( 11 ) NULL DEFAULT NULL AFTER `status`");
		//Quiz topic modified user
		$this->addColumn('tbl_quiz_topic', 'created_date', "DATETIME NULL AFTER `status`");		
		$this->addColumn('tbl_quiz_topic', 'modified_date', "DATETIME NULL AFTER `status`");
		$this->addColumn('tbl_quiz_topic', 'created_user_id', "INT( 11 ) NULL DEFAULT NULL AFTER `status`");
		$this->addColumn('tbl_quiz_topic', 'modified_user_id', "INT( 11 ) NULL DEFAULT NULL AFTER `status`");
		//Session modified user
		$this->addColumn('tbl_session', 'created_user_id', "INT( 11 ) NULL DEFAULT NULL AFTER `modified_date`");
		$this->addColumn('tbl_session', 'modified_user_id', "INT( 11 ) NULL DEFAULT NULL AFTER `modified_date`");
		//User modified user
		$this->addColumn('tbl_user', 'created_user_id', "INT( 11 ) NULL DEFAULT NULL AFTER `modified_date`");
		$this->addColumn('tbl_user', 'modified_user_id', "INT( 11 ) NULL DEFAULT NULL AFTER `modified_date`");
		//Subject Suggestion modified
		$this->addColumn('tbl_subject_suggestion', 'created_user_id', "INT( 11 ) NULL DEFAULT NULL AFTER `modified_date`");
		$this->addColumn('tbl_subject_suggestion', 'modified_user_id', "INT( 11 ) NULL DEFAULT NULL AFTER `modified_date`");
		
		//tbl_user_action_history table
		$this->createTable('tbl_user_action_history', array(
            'id' => 'pk',
			'user_id' => 'INT(11) NOT NULL',
            'table_name' => 'VARCHAR(80) NULL DEFAULT NULL',
			'controller' => 'VARCHAR(80) NOT NULL',
			'action' => 'VARCHAR(80) NOT NULL',
			'primary_key' => 'VARCHAR(80) NOT NULL',
			'description' => 'TEXT NULL DEFAULT NULL',
			'created_date'=> "DATETIME NOT NULL",
        ));
	}

	public function down()
	{
		echo "m140521_080020_user_action_logs does not support migration down.\n";
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