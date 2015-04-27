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
		<!-- REMOVE
        <div class="content pL100 pB10"><i class="icon-warning-sign"></i>
	        <b class="error">Bạn vui lòng điền đầy đủ, chính xác tất cả các thông tin bắt buộc(*):</b>
	        <b>Họ tên, Lớp, Ngày sinh, Số điện thoại, Địa chỉ.</b></div>
		-->
        <?php elseif(isset($_POST['User'])):?>
		<!-- REMOVE
        <div class="content pL100 pB10"><b class="alert-success mL100">Bạn đã cập nhật thông tin cá nhân thành công!</b></div>
		-->
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
            <label class="col-sm-2">Họ, tên đệm: </label>
            <div class=" col-sm-3">
                <!--<?php echo $form->textField($model,'firstname',array('class'=>"form-control","maxlength"=>"128")); ?>-->
				<p> <?php echo $model->firstname;?></p>
            </div>
            <div class=" col-sm-7">
                <label class="col-sm-2">Tên: </label>
                <div class=" col-sm-4">
                    <!--<?php echo $form->textField($model,'firstname',array('class'=>"form-control","maxlength"=>"128")); ?>-->
					<p> <?php echo $model->lastname;?></p>
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
            <label class="col-sm-2">Giới tính: </label>
            <div class=" col-sm-4">
			<!--
            	<?php $genderOptions = array(0=>'Nữ', 1=>'Nam');?>
            	<?php echo CHtml::dropDownList('User[gender]', $model->gender, $genderOptions, array('class'=>'form-control'));?>
				-->
				<p> <?php if ($model->gender === 0) echo "Nữ"; else echo "Nam";?></p>
            </div>
        </div>

		<!--
        <div class="row">
            <label  class="col-sm-2 control-label">Học lớp: <span class="required">(*)</span></label>
            <div class=" col-sm-4">
            	<?php
            		$classes = array(''=>'Chọn lớp...') + $classes;
            		echo CHtml::dropDownList('Student[class_id]', $student->class_id, $classes, array('class'=>'form-control'));
            	?>
            </div>
        </div>
		-->
        <div class="row">
            <label  class="col-sm-2">Ngày sinh: </label>
            <div class=" col-sm-2">
				<p> <?php echo $model->birthday;?></p>

			<!--
                <?php
                $birthday = isset($model->birthday)? explode("-", $model->birthday): array("", "", "");
                if(isset($_POST['birthday'])) $birthday = array($_POST['birthday']['year'], $_POST['birthday']['month'], $_POST['birthday']['date']);
                echo CHtml::dropDownList('birthday[date]', $birthday[2], Common::numberOptions(31, 1), array('class'=>'form-control'));
                ?>
				
            </div>

            <div class=" col-sm-2">
                <?php
                echo CHtml::dropDownList('birthday[month]', $birthday[1], Common::numberOptions(12, 1), array('class'=>'form-control'));
                ?>
            </div>
            <div class=" col-sm-2">
                <?php
                echo CHtml::dropDownList('birthday[year]', $birthday[0], Common::numberOptions(date('Y')-10, 1970), array('class'=>'form-control'));
                ?>
            </div>
			-->

			</div>
        <div class="row">
            <label  class="col-sm-2 control-label">Số điện thoại:</label>
            <div class=" col-sm-4">
				<p> <?php echo $model->phone;?></p>

				<!--
            	<?php $phoneErrorClass = "";//Phone error class?>
            	<?php if(isset($_POST['User'])):
            		 $model->phone = $_POST['User']['phone'];
            		 if(!Common::validatePhoneNumber($model->phone)) $phoneErrorClass = "error";
            	endif;
            	?>
            	<?php echo $form->textField($model,'phone',array('class'=>"form-control","maxlength"=>"11","style"=>"width:200px")); ?>
            	<span class="fs11" style="width:100%; color:#8F8F8F;">(Di động hoặc cố định)</span>
            	<br/><span class="fs11 <?php echo $phoneErrorClass;?>" style="width:100%;">Số điện thoại hợp lệ gồm: 9 số, 10 số hoặc 11 số viết liền!</span>
				-->
            </div>
        </div>
        <div class="row">
            <label  class="col-sm-2 control-label">Địa chỉ: </label>
            <div class=" col-sm-8">
				<p> <?php echo $model->address;?></p>
			<!--
                <?php echo $form->textField($model,'address',array('class'=>"form-control","maxlength"=>"128")); ?>
				-->
            </div>
        </div>
		
		<!--
        <div class="row">
            <label  class="col-sm-2 control-label">Họ tên bố: </label>
            <div class=" col-sm-3">
                <?php echo $form->textField($student,'father_name',array('class'=>"form-control","maxlength"=>"128")); ?>
            </div>
            <div class=" col-sm-7">
                <label  class="col-sm-3 control-label">Số điện thoại: : </label>
                <?php if(isset($_POST['Student'])) $student->father_phone = $_POST['Student']['father_phone'];?>
                <div class=" col-sm-5">
                    <?php echo $form->textField($student,'father_phone',array('class'=>"form-control","maxlength"=>"11")); ?>
                </div>
            </div>
        </div>


        <div class="row">
            <label  class="col-sm-2 control-label">Họ tên mẹ: </label>
            <div class=" col-sm-3">
                <?php echo $form->textField($student,'mother_name',array('class'=>"form-control","maxlength"=>"128")); ?>
            </div>
            <div class=" col-sm-7">
                <label  class="col-sm-3 control-label">Số điện thoại: : </label>
                <div class=" col-sm-5">
                    <?php if(isset($_POST['Student'])) $student->mother_phone = $_POST['Student']['mother_phone'];?>
                
                    <?php echo $form->textField($student,'mother_phone',array('class'=>"form-control","maxlength"=>"11")); ?>
                </div>
            </div>
        </div>
		-->

        <div class="row">
            <label  class="col-sm-2 control-label">Mô tả ngắn về bản thân: </label>
            <div class=" col-sm-7">
				<p> <?php echo $student->short_description;?></p>
				<!--
                <textarea class="form-control" name="Student[short_description]" style="height: 80px;"><?php echo $student->short_description; ?></textarea>
				-->
            </div>
        </div>
        <div class="row">
            <label  class="col-sm-2 control-label">Mô tả chi tiết bản thân: </label>
            <div class=" col-sm-7">
				<p> <?php echo $student->description;?></p>
				<!--
                <textarea class="form-control" name="Student[description]" style="height: 120px;"><?php echo $student->description; ?></textarea>
				-->
            </div>
        </div>
        <div class="row">
            <label class=" col-sm-2 control-label">&nbsp;</label>
            <div class=" col-sm-7">
				<br>
                <input type="submit" disabled name="save"  class="btn btn-primary" value="Vui lòng liên hệ để thay đổi thông tin cá nhân"/>
            </div>
        </div>
        <?php $this->endWidget(); ?>