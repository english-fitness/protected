<?php

class m140616_051320_update_payment_field extends CDbMigration
{
	public function up()
	{
		$this->renameColumn('tbl_preregister_payment', 'preregister_id', "precourse_id");
	}

	public function down()
	{
		echo "m140616_051320_update_payment_field does not support migration down.\n";
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