<?php

class m140212_061739_update_script extends CDbMigration
{
	public function up()
	{
		$this->alterColumn('tbl_preregister_course', 'id', "INT( 11 ) NOT NULL AUTO_INCREMENT");
		$this->alterColumn('tbl_notification', 'content', "TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL");
		$this->addColumn('tbl_preregister_course', 'course_id', "INT( 11 ) NULL DEFAULT NULL AFTER `status`");
	}

	public function down()
	{
		echo "m140212_061739_update_script does not support migration down.\n";
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