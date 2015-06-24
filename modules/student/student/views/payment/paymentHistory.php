<?php
$cartCodeSucess = isset($cartCodeSucess)?$cartCodeSucess: null
?>
<?php $this->renderPartial('/courseRequest/preregisterTab',array('preregisterCourse'=>$preregisterCourse)); ?>
<div class="form-element-container row">
	<div class="col col-lg-12 text-center pT5" style="background-color:#CCCCCC">
		<label>LỊCH SỬ THANH TOÁN TIỀN HỌC PHÍ KHÓA HỌC: <?php echo $preregisterCourse->title;?></label>
	</div>
</div>
<div class="details-class">
    <div class="session">
    	<?php if($cartCodeSucess): ?>
    		<div class="alert alert-success" role="alert">Xin chúc mừng bạn đã nạp thành công thẻ khuyến mãi, giá khóa học đã được trừ : <?php echo Yii::app()->format->formatNumber($cartCodeSucess); ?>VND </div>
    	<?php  endif; ?>
        <table class="table table-bordered table-striped data-grid">
            <thead>
                <tr>
                	<th class="w150">Mã thanh toán</th>
                    <th class="w150">Ngày thanh toán</th>
                    <th class="w150">Số tiền thanh toán</th>
                    <th class="w120">Phương thức</th>
                    <th>Ghi chú</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            	if(isset($paymentHistory) && count($paymentHistory)>0):
            		foreach($paymentHistory as $payment):
            ?>
            	<tr class="even">
            		<td><?php echo $payment->transaction_id;?></td>
            		<td><?php echo ($payment->payment_date)? date('d/m/Y, H:i', strtotime($payment->payment_date)):"";?></td>
            		<td class="text-right"><?php echo number_format($payment->paid_amount);?></td>
	           		<td><?php echo $payment->payment_method;?></td>
            		<td><?php echo $payment->note;?></td>
            	</tr>
            <?php endforeach;
             endif;?>
			</tbody>
		</table>
	</div>
</div>
<div class="form-element-container row">
	<?php 
		$totalPaidAmount = $preregisterCourse->getTotalPaidAmount();//Total paid amount
		$remainAmount = $preregisterCourse->getRemainPaymentAmount();//Remain payment amount
		$mobiCardRemainAmount = $preregisterCourse->getMobicardRemainPaymentAmount();//Mobicard remain payment amount
	?>
	<div class="col col-lg-4"><label>Tiền học phí khóa học:&nbsp;</label>
	<b style="color:blue;">
		<?php echo number_format($preregisterCourse->getTotalFinalPrice());?>
	</b></div>
	<div class="col col-lg-4"><label>Tiền học phí đã đóng:&nbsp;</label><b style="color:blue;"><?php echo number_format($totalPaidAmount);?></b></div>
	<div class="col col-lg-4"><label>Tiền học phí còn thiếu:&nbsp;</label><b class="error"><?php echo number_format($mobiCardRemainAmount);?></b></div>
</div>
<?php if(isset($mobicardSuccess) && $mobicardSuccess!=""):?>
	<p class="text-center alert-success mT10" style="font-weight:bold;"><?php echo $mobicardSuccess;?></p>
<?php endif;?>
<form id="frmPaymentMobicard" action="<?php echo  Yii::app()->request->requestUri; ?>" method="post" style="padding:0px;">
<?php if($preregisterCourse->checkDisplayNganluongPayment()):
	if(isset($checkoutUrl)):
	$presetCourse = $preregisterCourse->getPresetCourse();
?>
	<?php if($preregisterCourse->status==PreregisterCourse::STATUS_APPROVED || $presetCourse===false 
		|| ($presetCourse!==false && !$presetCourse->checkFullPaidStudents())):
		echo $this->renderPartial('/payment/paymentMethod',array(
			'preregisterCourse' => $preregisterCourse,
			'checkoutUrl' => $checkoutUrl,
			'errorMessage' => $errorMessage,
			'mobicardSuccess' => $mobicardSuccess,
			'remainAmount' => $remainAmount,
			'mobiCardRemainAmount' => $mobiCardRemainAmount,
			'cartCodeError' => isset($cartCodeError)?$cartCodeError: null,
			'cartCodeSucess' => isset($cartCodeSucess)?$cartCodeSucess: null
		));
	?>
	<?php elseif($presetCourse!==false && $presetCourse->checkFullPaidStudents()):?>
		<p class="text-center error fsBold mT10">Khóa học này đã đủ số lượng học sinh đăng ký & nộp tiền học phí. Bạn vui lòng đăng ký khóa học khác!</p>
	<?php endif;
	endif;
endif;?>
</form>
