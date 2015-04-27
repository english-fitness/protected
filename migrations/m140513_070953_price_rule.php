<?php

class m140513_070953_price_rule extends CDbMigration
{
	public function up()
	{
		$this->addColumn('tbl_preset_course', 'price_rules', "TEXT NULL DEFAULT NULL AFTER `session_per_week`");
		$this->addColumn('tbl_preset_course', 'note', "TEXT NULL DEFAULT NULL AFTER `session_per_week`");
		$this->addColumn('tbl_preregister_course', 'price_rules', "TEXT NULL DEFAULT NULL AFTER `final_price`");
		$this->addColumn('tbl_preregister_course', 'mobicard_final_price', "FLOAT ( 20 ) NULL DEFAULT '0' AFTER `final_price`");
	}

	public function down()
	{
		echo "m140513_070953_price_rule does not support migration down.\n";
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