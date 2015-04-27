<div id="accountLogin" style="display: none">
    <form method="post" action="<?php echo Yii::app()->baseurl;?>/site/signin" class="myForm">
    	<?php
    		$loginLabel = "Login";
    		if(Yii::app()->controller->action->id=='register'){
    			$loginLabel = "Registered";
    		}
    	?>
    	<div class="row-form h40">
			<p style="font-style: italic; color: blue">"Education is most powerful weapon which you can use to change the world" &nbsp; --&nbsp;<strong>Nelson Mandela</strong> (1918 - 2013)</p>
		</div>
		<?php if(Yii::app()->controller->action->id=='register'):?>
			<div class="row-form clearfix" style="border-top:1px solid #DDDDDD;">
				<div class="fL" style="width:110px;">
					<img src="<?php echo Yii::app()->baseurl;?>/media/images/contact_icon.png" style="width:100px; height:100px;"/>
				</div>
				<div class="fL pT20" style="width:250px;color:#353535;font-size:12px;">
					<b class="fs18">Fill out the information to be consulted directly <a href="#" onclick="javascript:oauthLoginPopup('<?php echo Yii::app()->baseUrl."/register/contact";?>', 860, 500)" style="color: #275cb3"><br/>(Click here)</a></b>
				</div>
			</div>
			<div class="row-form h5">&nbsp;</div>
		<?php else:?>
			<div class="row-form clearfix">
			</div>
	        <div class="row-form">
	            <div class="label col-sm-4" style="width:80px;">Email: </div>
	            <div class="value col-sm-6" style="width:275px;"><input type="email" name="email" value="" placeholder="Email registered"/></div>
	        </div>
	        <div class="row-form">
	            <div class="label col-sm-4" style="width:80px;">Password: </div>
	            <div class="value col-sm-6" style="width:275px;">
					<input type="password" name="password" value="" placeholder="Password" style="width:180px;"/>
					<button name="save" type="submit" class="fR" style="margin-right:0px;">Login</button>
				</div>
	        </div>
	        <div class="row-form">
	            <div class="label col-sm-4" style="width:80px;">&nbsp;</div>
				<input type="checkbox" name="rememberMe" value="1" style="margin-top:-4px;"> Remember password!
	        </div>
        <?php endif;?>
		<div class="row-form h10">&nbsp;</div>
    </form>
</div>
