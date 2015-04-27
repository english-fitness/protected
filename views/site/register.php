<style>
	#popupAll .row-form .label{width:20%;}
	#popupAll .row-form .value{width:75%;}
</style>
<div id="popupAll">
<?php if(isset($connectedSuccess) && $connectedSuccess==1):?>
	<script type="text/javascript">
		setTimeout(function() {window.close();}, 300);
	</script>
	<?php if(isset($facebookConnected) && $facebookConnected):?><p class="text-center alert-success">Kết nối facebook thành công!</p><?php endif;?>
	<?php if(isset($googleConnected) && $googleConnected):?><p class="text-center alert-success">Kết nối google thành công!</p><?php endif;?>
<?php else:?>
	<h3 class="text-center mT8">Đăng ký tài khoản học sinh</h3>
	<div id="accountRegister">
	    <form enctype="multipart/form-data" method="post" action="<?php echo Yii::app()->baseurl;?>/login/register" class="myForm" role="form" id="registerForm">
	        <div class="row-form">
	            <div class="col-sm-3 label">Họ tên đệm: <span class="required">*</span></div>
	            <div class="col-sm-8 value">
	                <input type="text" style="width: 175px;" name="User[lastname]"  placeholder="Họ tên đệm" value="<?php echo isset($userData['lastname'])? $userData['lastname']: '';?>"><b> Tên: </b>
	                <input type="text" name="User[firstname]" placeholder="Tên" style="width:175px;" value="<?php echo isset($userData['firstname'])? $userData['firstname']: '';?>">
	                <?php if(isset($userData) && isset($facebookConnected) && $facebookConnected):?><input type="hidden" name="facebookConnected" value="1"><?php endif;?>
					<?php if(isset($userData) && isset($googleConnected) && $googleConnected):?><input type="hidden" name="googleConnected" value="1"><?php endif;?>
					<div class="errorMessage">
						<?php 
							if(isset($errors['lastname'])) echo $errors['lastname'][0];
							if(isset($errors['firstname'])) echo $errors['firstname'][0];
						?>
					</div>
	            </div>
	        </div>
	        <div class="row-form">
	            <div class="col-sm-3 label">Email đăng nhập:  <span class="required">*</span></div>
	            <div class="col-sm-8 value">
	                <input type="email" name="User[email]" placeholder="Email đăng nhập" value="<?php echo isset($userData['email'])? $userData['email']: '';?>"/>
	                <div class="errorMessage"><?php	echo isset($errors['email'])? $errors['email'][0]:"";?></div>
	            </div>
	        </div>
	        <div class="row-form">
	            <div class="col-sm-3 label">Mật khẩu:  <span class="required">*</span></div>
	            <div class="col-sm-8 value">
	                <input type="password" name="User[password]" placeholder="*******" value="<?php echo isset($userData['password'])? $userData['password']: '';?>"/>
	                <div class="errorMessage"><?php	if(isset($errors['password'])) echo $errors['password'][0];?></div>
	            </div>
	        </div>
	        <div class="row-form">
	            <div class="col-sm-3 label">Xác nhận mật khẩu:  <span class="required">*</span></div>
	            <div class="col-sm-8 value">
	                <input type="password" name="User[repeatPassword]" placeholder="*******" value="<?php echo isset($userData['repeatPassword'])? $userData['repeatPassword']: '';?>"/>
	                <div class="errorMessage"><?php	if(isset($errors['repeatPassword'])) echo $errors['repeatPassword'][0];?></div>
	            </div>
	        </div>
	        <div class="row-form">
	            <div class="col-sm-3 label">Ngày sinh:  </div>
	            <div class="col-sm-8 value">
	               <?php
	               		$birthday = isset($_REQUEST['birthday'])? $_REQUEST['birthday']: array();
	               		$date = isset($birthday['date'])? $birthday['date']: '01';
	               		$month = isset($birthday['month'])? $birthday['month']: '01';
	               		$year = isset($birthday['year'])? $birthday['year']: date('Y')-16;
						echo CHtml::dropDownList('birthday[date]', $date, Common::numberOptions(31, 1), array('style'=>'width:60px;'));
						echo CHtml::dropDownList('birthday[month]', $month, Common::numberOptions(12, 1), array('style'=>'width:60px;'));
						echo CHtml::dropDownList('birthday[year]', $year, Common::numberOptions(date('Y')-10, 1970), array('style'=>'width:75px;'));
					?>
					<b>Giới tính: </b>
					<?php $genderOptions = array('male'=>1, 'female'=>0, 0=>0, 1=>1);?>
					<?php 
						$gender = isset($userData['gender'])? $genderOptions[$userData['gender']]: 1;
					?>
	                <select name="User[gender]" style="width: 100px;">
	                    <option value="1" <?php if($gender==1):?> selected="selected" <?php endif;?>>Nam</option>
	                    <option value="0" <?php if($gender==0):?> selected="selected" <?php endif;?>>Nữ</option>
	                </select>
	            </div>
	        </div>
	        <div class="row-form">
	            <div class="col-sm-3 label">Lớp đang học:  </div>
	            <div class="col-sm-8 value">
	            	<?php $selectedClassId = isset($_REQUEST['Student'])? $_REQUEST['Student']['class_id']:"";?>
	                <select name="Student[class_id]" style="width: 128px;">
	                    <?php if(count($classes)>0):
	                    	 foreach($classes as $class):
	                    ?>
                        <option value="<?php echo $class->id; ?>" <?php if($class->id==$selectedClassId):?> selected="selected" <?php endif;?>>
                        	<?php echo $class->name; ?>
                        </option>
	                    <?php endforeach;
	                    endif;
	                    ?>
	                </select>
	                <b>Điện thoại liên hệ: </b>
	                <input type="text" style="width:175px;" maxlength="20" name="User[phone]" placeholder="Số điện thoại liên hệ" value="<?php echo isset($userData['phone'])? $userData['phone']: '';?>"/>
	            </div>
	        </div>
	        <div class="row-form">
	            <div class="col-sm-3 label">Địa chỉ liên hệ:  </div>
	            <div class="col-sm-8 value">
	                <input type="text" name="User[address]" placeholder="Địa chỉ liên hệ" value="<?php echo isset($userData['address'])? $userData['address']: '';?>"/>
	                <div class="errorMessage"><?php echo isset($errors['errorMessage'])? $errors['errorMessage'][0]:"";?></div>
	            </div>
	        </div>
	        <div class="row-form">
	            <div class="col-sm-3 label">&nbsp; </div>
	            <div class="col-sm-8 value">
	                <button type="submit"  name="save">Đăng ký</button>
	            </div>
	        </div>
	    </form>
	</div>
	<?php endif;?>
</div>