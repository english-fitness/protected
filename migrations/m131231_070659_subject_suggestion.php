<?php

class m131231_070659_subject_suggestion extends CDbMigration
{
	public function up()
	{
		//Create tbl_subject_suggestion table
		$this->createTable('tbl_subject_suggestion', array(
			'id' => 'pk',
			'subject_id' => 'INT(11) NOT NULL',
			'title' => 'VARCHAR(256) NOT NULL',
			'description'=> "TEXT DEFAULT NULL",			
			'created_date' => 'DATETIME NOT NULL',
			'modified_date' => 'DATETIME DEFAULT NULL',
		));
	}

	public function down()
	{
		echo "m131231_070659_subject_suggestion does not support migration down.\n";
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