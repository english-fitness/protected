<?php

class m140318_080308_pre_course_payment extends CDbMigration
{
	public function up()
	{
		$this->addColumn('tbl_preregister_course', 'payment_id', "INT( 11 ) NULL DEFAULT NULL AFTER `final_price`");
		$this->addColumn('tbl_preregister_course', 'payment_method', "VARCHAR( 255 ) NULL DEFAULT NULL AFTER `payment_note`");
		$this->addColumn('tbl_preregister_course', 'order_code', "VARCHAR( 255 ) NULL DEFAULT NULL AFTER `course_id`");
		$this->addColumn('tbl_preregister_course', 'payment_date', "DATETIME NULL DEFAULT NULL AFTER `payment_note`");
	}

	public function down()
	{
		echo "m140318_080308_pre_course_payment does not support migration down.\n";
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