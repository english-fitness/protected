<?php

class m140123_093314_confirmed_ids extends CDbMigration
{
	public function up()
	{
		$this->addColumn('tbl_notification', 'confirmed_ids', "TEXT NULL DEFAULT NULL AFTER `link`");
	}

	public function down()
	{
		echo "m140123_093314_confirmed_ids does not support migration down.\n";
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