<?php

class m140407_021531_payment_tables extends CDbMigration
{
	public function up()
	{
		//Create tbl_preregister_payment table
		$this->createTable('tbl_preregister_payment', array(
            'id' => 'pk',
            'preregister_id' => 'INT(11) NOT NULL',
			'transaction_id'=> 'VARCHAR( 20 ) NULL DEFAULT NULL',
			'paid_amount'=> 'FLOAT NULL',
			'payment_method' => 'VARCHAR( 255 ) NULL DEFAULT NULL',
			'payment_date' => 'DATETIME NULL DEFAULT NULL',
			'status' => 'TINYINT NOT NULL DEFAULT 0',
			'note' => 'TEXT NULL DEFAULT NULL',
			'created_user_id' => 'INT(11) NOT NULL',
			'created_date' => 'DATETIME NOT NULL',
			'modified_date'=> "DATETIME NULL DEFAULT NULL",
        ));
	}

	public function down()
	{
		echo "m140407_021531_payment_tables does not support migration down.\n";
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