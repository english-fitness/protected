<script type="text/javascript">
	//Display payment method
	function displayMethod(){
		var paymentMethod = $("input[name='payment_method']:checked").val();
		$('.PaymentMethod').addClass('dpn');
		$('#NL_'+paymentMethod).removeClass('dpn');
	}	
	$(document).ready(function(){
		<?php if(isset($_REQUEST['payment_method'])):?>
			$("#<?php echo $_REQUEST['payment_method'];?>").prop('checked', true);
		<?php endif;?>	
		displayMethod();//Display payment method
		$('.btnNganluongCheckout').click(function(){
			var paymentMethod = $("input[name='payment_method']:checked").val();
			var bankCode = $("#bankCodeOption").val();
			if(paymentMethod=='ATM_ONLINE'){
				window.location.href = "<?php echo $checkoutUrl;?>";
			}else if(paymentMethod=='NH_OFFLINE'){
				checkoutLink = '/student/payment/checkout/id/<?php echo $preregisterCourse->id?>' + '?paymentMethod=NH_OFFLINE&bankCode='+bankCode;
				window.open(checkoutLink);
			}else if(paymentMethod=='MOBI_CARD'){
				$("#frmPaymentMobicard").submit();
			}
		});
	});
</script>
<div class="form-element-container row">
	<div class="col col-lg-12 text-center pT5"  style="background-color:#CCCCCC"><label>CHỌN PHƯƠNG THỨC THANH TOÁN ĐỂ TIẾP TỤC NỘP TIỀN HỌC PHÍ CÒN THIẾU</label></div>
</div>
<div class="form-element-container row">
	<div class="col col-lg-3">
		<label>THANH TOÁN QUA NGÂN LƯỢNG<br/>(Lựa chọn hình thức thanh toán)</label>
		<br/>
		<div style="padding:10px 0 10px 15px; float:left;">
			<script src="https://www.nganluong.vn/tooltip_nbdb/nldb_tootip.js"></script>
			<script type="text/javascript">
				ngaluongloadframe_new(33074, 47107, 150, 0);
			</script>
		</div>
	</div>
	<div class="col col-lg-9">
		<?php
			$checkPaidMobicardPayment = $preregisterCourse->checkPaidMobicardPayment();
			if(!$checkPaidMobicardPayment && isset($checkoutUrl) && $checkoutUrl!==false):
		?>
		<div class="col col-lg-12 form-element-container">
			<div class="col col-lg-12">
				<label><input type="radio" value="ATM_ONLINE" id="ATM_ONLINE" name="payment_method" checked="checked"
				 onclick="displayMethod();">
					THANH TOÁN ONLINE BẰNG THẺ NGÂN HÀNG NỘI ĐỊA<br/>
					<span class="pL15">Số tiền cần thanh toán: <span class="error"><?php echo number_format($remainAmount);?></span> VNĐ</span>
				</label>
			</div>
			<div id="NL_ATM_ONLINE" class="col col-lg-12 PaymentMethod pB10">
				<i class="fs11"><span class="error">Lưu ý</span>: Bạn cần đăng ký Internet-Banking hoặc dịch vụ thanh toán trực tuyến tại ngân hàng trước khi thực hiện.</i><br/>
				<button class="btnNganluongCheckout mT5" style="border:none;" type="button"> 
					<img border="0" src="https://www.nganluong.vn/data/images/buttons/3.gif" /> 
				</button>
			</div>
		</div>
		<div class="col col-lg-12 form-element-container">
			<div class="col col-lg-12">
				<?php $bankCodeSelected = isset($_REQUEST['bankCode'])? $_REQUEST['bankCode']: "VCB";?>
				<label><input type="radio" value="NH_OFFLINE" id="NH_OFFLINE" name="payment_method" onclick="displayMethod();">
					THANH TOÁN BẰNG CÁCH CHUYỂN TIỀN TẠI QUẦY GIAO DỊCH CỦA NGÂN HÀNG<br/>
					<span class="pL15">Số tiền cần thanh toán: <span class="error"><?php echo number_format($remainAmount);?></span> VNĐ</span><br/>
					<span class="pL15">Chọn ngân hàng có quầy giao dịch gần bạn nhất</span>
				</label>
			</div>
			<div id="NL_NH_OFFLINE" class="col col-lg-12 PaymentMethod pB10">
				<?php echo CHtml::dropDownList('bankCode', $bankCodeSelected, ClsNganluong::bankCodeOptions(), array('id'=>'bankCodeOption', 'class'=>'fs13','style'=>'width:420px;'));?>
				<i class="fs11"><span class="error">Lưu ý</span>: Bạn cần lưu lại hướng dẫn thanh toán mà Ngân lượng cung cấp sau khi bấm nút "Thanh toán".</i><br/>
				<button class="btnNganluongCheckout mT5" style="border:none;" type="button"> 
					<img border="0" src="https://www.nganluong.vn/data/images/buttons/3.gif" /> 
				</button>
			</div>
		</div>
	<?php endif; ?>
        <?php if((isset($preregisterCourse->package) && $preregisterCourse->package->type == CoursePackage::TYPE_TRIAL) || $preregisterCourse->course_type==Course::TYPE_COURSE_PRESET):?>
          <div class="col col-lg-12 form-element-container">
			<div class="col col-lg-12">
				<label><input type="radio" value="MOBI_CARD" id="MOBI_CARD" name="payment_method" onclick="displayMethod();">
					THANH TOÁN BẰNG THẺ CÀO ĐIỆN THOẠI<br/>
                    <span class="pL15">Số tiền cần thanh toán: <span class="error"><?php echo number_format($mobiCardRemainAmount);?></span> VNĐ
                        <?php if(isset($preregisterCourse->mobicard_final_price) && $preregisterCourse->mobicard_final_price>$preregisterCourse->final_price):?>
                            (gồm học phí và phí thanh toán trả cho nhà mạng và cổng thanh toán)
                        <?php endif;?>
                    </span>
			    </label>
			</div>
			<div id="NL_MOBI_CARD" class="col col-lg-12 PaymentMethod pB10">
				<i class="fs11"><span class="error">Lưu ý</span>: Hệ thống sẽ ko hoàn lại tiền nếu nộp thừa học phí!</i><br/>
				<?php if(isset($errorMessage) && $errorMessage!=""):?>
					<b class="error"><?php echo $errorMessage;?></b>
				<?php elseif(isset($mobicardSuccess) && $mobicardSuccess!=""):?>
					<b class="alert-success"><?php echo $mobicardSuccess;?></b>
				<?php endif;?>
				<div class="col col-lg-12">
					<div class="col col-lg-3"><label class="mT15">Chọn loại thẻ để nạp:</label></div>
					<div class="col col-lg-9">
						<?php $mobiCardSelected = isset($_POST['mobiCard']['type'])? $_POST['mobiCard']['type']: "";?>
						<?php echo CHtml::dropDownList('mobiCard[type]', $mobiCardSelected, ClsNganluong::mobiCardOptions(), array('id'=>'mobiCardType', 'class'=>'fL fs13','style'=>'width:250px;'));?>
					</div>
				</div>
				<div class="col col-lg-12">
					<div class="col col-lg-3"><label class="mT15">Số Seri:</label></div>
					<div class="col col-lg-9">
						<?php $soseri = isset($_POST['mobiCard']['seri'])? $_POST['mobiCard']['seri']: ""?>
						<input type="text" id="txtSoSeri" name="mobiCard[seri]" class="w250" value="<?php echo $soseri;?>" placeholder="Số Seri trên thẻ cào"/>
					</div>
				</div>
				<div class="col col-lg-12">
					<div class="col col-lg-3"><label class="mT15">Mã số thẻ:</label></div>
					<div class="col col-lg-9">
						<?php $sopin = isset($_POST['mobiCard']['sopin'])? $_POST['mobiCard']['sopin']: ""?>
						<input type="text" id="txtSoPin" name="mobiCard[sopin]" class="w250" value="<?php echo $sopin;?>" placeholder="Mã số thẻ cào"/>
					</div>
				</div>
				<div class="col col-lg-12">
					<div class="col col-lg-3">&nbsp;</div>
					<div class="col col-lg-9">
						<button class="btnNganluongCheckout mT5" style="border:none;" type="button"> 
							<img border="0" src="<?php echo Yii::app()->baseurl.'/media/images/mobicards/napthe.png'?>" /> 
						</button>
					</div>
				</div>
			</div>
		</div>
	<?php endif;?>
		<div class="row">
		   	<div class="col col-lg-12">
	            <div class="col col-lg-6"><br/>
	            	<label>NHẬP THẺ KHUYẾN MÃI NẾU CÓ </label>
	                <input name="cart_code" type="text" class="form-control" placeholder="Vui lòng nhập mã thẻ khuyến mãi nếu có">
                    <?php if($cartCodeError): ?>
                    	<div class="alert alert-danger"><?php echo $cartCodeError; ?></div>
                    <?php endif; ?>
	            </div><!-- /.col-lg-4 -->
	        </div><!-- /.row -->
	         <div class="col col-lg-12">
	            <div class="col col-lg-12">
	                <button type="submit" class="btn btn-default mL5">Nạp thẻ khuyến mãi</button>
	            </div><!-- /.col-lg-12 -->
	        </div><!-- /.row -->
	    </div>  
	</div>
</div>