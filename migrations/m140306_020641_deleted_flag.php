<?php

class m140306_020641_deleted_flag extends CDbMigration
{
	public function up()
	{
		//Add deleted flag to some tables
		$this->addColumn('tbl_user', 'deleted_flag', "TINYINT( 4 ) NULL DEFAULT '0'");
		$this->addColumn('tbl_course', 'deleted_flag', "TINYINT( 4 ) NULL DEFAULT '0'");
		$this->addColumn('tbl_session', 'deleted_flag', "TINYINT( 4 ) NULL DEFAULT '0'");
		$this->addColumn('tbl_preregister_course', 'deleted_flag', "TINYINT( 4 ) NULL DEFAULT '0'");
		$this->addColumn('tbl_notification', 'deleted_flag', "TINYINT( 4 ) NULL DEFAULT '0'");
		$this->addColumn('tbl_message', 'deleted_flag', "TINYINT( 4 ) NULL DEFAULT '0'");
		$this->addColumn('tbl_session', 'teacher_entered_time', "DATETIME NULL DEFAULT NULL");
		
		//Change status & deleted for old data
		$this->update('tbl_user', array('deleted_flag'=>1), "status=-1", array());
		$this->update('tbl_preregister_course', array('deleted_flag'=>1), "status=0", array());
		$this->update('tbl_course', array('deleted_flag'=>1), "status=0", array());
		$this->update('tbl_session', array('deleted_flag'=>1), "status=0", array());
		
		//Update status for tbl_preregister_course
		$this->update('tbl_preregister_course', array('status'=>0), "status=1", array());
		$this->update('tbl_preregister_course', array('status'=>1), "status=2", array());
		$this->update('tbl_preregister_course', array('status'=>2), "status=3", array());
		
		//Update status for tbl_course
		$this->update('tbl_course', array('status'=>0), "status=1", array());
		$this->update('tbl_course', array('status'=>1), "status=2", array());
		
		//Update status for tbl_session
		$this->update('tbl_session', array('status'=>0), "status=1", array());
		$this->update('tbl_session', array('status'=>1), "status=2", array());
		
	}

	public function down()
	{
		echo "m140306_020641_deleted_flag does not support migration down.\n";
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