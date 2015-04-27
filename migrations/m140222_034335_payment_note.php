<?php

class m140222_034335_payment_note extends CDbMigration
{
	public function up()
	{
		$this->addColumn('tbl_preregister_course', 'payment_note', "TEXT NULL DEFAULT NULL AFTER `payment_type`");
		$this->alterColumn('tbl_message_status', 'read_date', "DATETIME NULL DEFAULT NULL");
	}

	public function down()
	{
		echo "m140222_034335_payment_note does not support migration down.\n";
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