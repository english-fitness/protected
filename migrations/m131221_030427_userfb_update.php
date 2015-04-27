<?php

class m131221_030427_userfb_update extends CDbMigration
{
	public function up()
	{
		//Add to columns to tbl_user
		$this->alterColumn('tbl_user_facebook', 'facebook_access_token', "VARCHAR( 256 ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL");
	}

	public function down()
	{
		echo "m131221_030427_userfb_update does not support migration down.\n";
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