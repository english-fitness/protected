<?php

class m140320_044122_transaction_update extends CDbMigration
{
	public function up()
	{
		$this->addColumn('tbl_preregister_course', 'transaction_id', "INT( 11 ) NULL DEFAULT NULL AFTER `final_price`");
	}

	public function down()
	{
		echo "m140320_044122_transaction_update does not support migration down.\n";
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