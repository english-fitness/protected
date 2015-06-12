<?php
        $userID = Yii::app()->user->id;
        $languages = User::model()->findByPk($userID)->language;
        Yii::app()->language=$languages;
?>

<?php $this->renderPartial('/messages/tab'); ?>
<?php $this->renderPartial('accountTab'); ?>
        <?php
        $form = $this->beginWidget(
            'CActiveForm',
            array(
                'htmlOptions' => array('enctype' => 'multipart/form-data','class'=>'form-horizontal'),
                "action"=>Yii::app()->request->requestUri
            )
        );
        $clsNotification = new ClsNotification();
        $enoughProfile = $clsNotification->enoughProfile($model->id);
        ?>
        <?php if(!$enoughProfile || $model->lastname=="" || $model->firstname==""):?>

        <?php elseif(isset($_POST['User'])):?>
			<?php if($model->status==User::STATUS_ENOUGH_PROFILE):?>
	        <script type="text/javascript">
	        	<?php $returnUrl = Yii::app()->session['returnUrl'];?>
	        	<?php if(isset($returnUrl) && $returnUrl!==false):
	        		unset($_SESSION['returnUrl']);//Unset returnUrl if user enough profile
	        	?>
	        		setTimeout(function(){window.location.href="<?php echo $returnUrl;?>"},2000);
	        	<?php else:?>
	        		setTimeout(function(){window.location.href="/student/testCondition/index"},3000);
	        	<?php endif;?>
	        </script>
	        <?php endif;?>
        <?php endif;?>
        <div class="notice editProfile"></div>

		<!-- USER PROFILE -->
        <div class="row">
            <label class="col-sm-1" style="min-width:120px;"><?php echo Yii::t('lang','Họ, tên đệm');?>: </label>
            <div class=" col-sm-2">
				<p> <?php echo $model->lastname;?></p>
            </div>
            <div class=" col-sm-7">
                <label class="col-sm-2" style="min-width:125px;"><?php echo Yii::t('lang','Tên');?>: </label>
                <div class=" col-sm-4">
					<p> <?php echo $model->firstname;?></p>
                </div>
            </div>
        </div>
		
		<!-- PENDING CHANGE
        <div class="row">
            <label class="col-sm-2 control-label">Ảnh đại diện: <span class="required">(*)</span></label>
            <div class=" col-sm-9 loadImageJavascript">
                <img src="<?php echo Yii::app()->user->getProfilePicture(); ?>" class="w50" alt="avartar"/>
                <input type="file" name="profilePicture"/>
            </div>
        </div>
		-->

        <div class="row">
            <label class="col-sm-1" style="min-width:120px;"><?php echo Yii::t('lang','Giới tính');?>: </label>
            <div class=" col-sm-4">
				<p> 
					<?php 
						if ($model->gender == 1)
							echo Yii::t('lang','Nữ'); 
						else if ($model->gender == 2)
							echo Yii::t('lang','Nam');
						else
							echo "";
					?>
				</p>
            </div>
        </div>
		
        <div class="row">
            <label class="col-sm-1" style="min-width:120px;"><?php echo Yii::t('lang','Ngày sinh');?>: </label>
            <div class="col-sm-2">
				<p> <?php echo $model->birthday;?></p>
			</div>
			<div class="col-sm-7">
				<label  class="col-sm-2"" style="min-width:125px;"><?php echo Yii::t('lang','Số điện thoại');?>:</label>
				<div class=" col-sm-4">
					<p> <?php echo $model->phone;?></p>
				</div>
			</div>
		</div>
        <div class="row">
            <label  class="col-sm-1" style="min-width:120px;"><?php echo Yii::t('lang','Địa chỉ');?>: </label>
            <div class=" col-sm-8">
				<p> <?php echo $model->address;?></p>
            </div>
        </div>
		
		<!-- PENDING REMOVE
        <div class="row">
            <label  class="col-sm-2 control-label">Mô tả ngắn về bản thân: </label>
            <div class=" col-sm-7">
				<p> <?php echo $student->short_description;?></p>
            </div>
        </div>
        <div class="row">
            <label  class="col-sm-2 control-label">Mô tả chi tiết bản thân: </label>
            <div class=" col-sm-7">
				<p> <?php echo $student->description;?></p>
            </div>
        </div>
		-->
        <div class="row">
            <label class=" col-sm-1" style="min-width:120px;">&nbsp;</label>
            <div class=" col-sm-7">
				<br>
                <input type="submit" disabled name="save"  class="btn btn-primary" value="<?php echo Yii::t('lang','Vui lòng liên hệ khi cần thay đổi thông tin cá nhân');?>"/>
            </div>
        </div>
        <?php $this->endWidget(); ?>
