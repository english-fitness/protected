<?php

class m140402_084310_status_history extends CDbMigration
{
	public function up()
	{
		$this->addColumn('tbl_user', 'status_history', "TEXT NULL DEFAULT NULL AFTER `status`");
	}

	public function down()
	{
		echo "m140402_084310_status_history does not support migration down.\n";
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