<?php $recordCount = $payment->countDays();?>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
        <h2 class="page-title mT10">Tổng hợp tháng <?php echo date('m-Y', strtotime($payment->month))?></h2>
    </div>
	<?php if($payment->report_status == TeacherPayment::STATUS_OPEN):?>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
            <a class="top-bar-button btn btn-primary" href="<?php echo Yii::app()->baseUrl; ?>/admin/DailyRecord/create?payment_id=<?php echo $payment->id?>">
			<i class="icon-plus"></i>Thêm bản ghi hàng ngày
			</a>
        </div>
    </div>
	<?php endif;?>
	<!--detail-->
	<div class="col col-lg-12 pB10">
    	<p>
    		<div class="col col-lg-3 pL0i">
    			<span class="fL"><b>ID:</b>&nbsp;<?php echo $payment->id?></span>
    		</div>
    		<div class="col col-lg-3 pL0i">
    			<span class="fL"><b>Giáo viên:</b>&nbsp;<?php echo $payment->getTeacherLink();?></span>
    		</div>
			<div class="col col-lg-3 pL0i">
    			<span class="fL"><b>Tháng:</b>&nbsp;<?php echo date('m-Y', strtotime($payment->month))?></span>
    		</div>
    	</p>
    </div>
	<div class="col col-lg-12 pB10">
    	<p>
			<div class="col col-lg-3 pL0i">
				<span class="fL"><b>Số buổi học trên platform:</b></span>
			</div>
			<div class="col col-lg-2" style="margin-left:-150px">
				<span class="fL"><?php echo $payment->total_platform_session . " buổi"?></span>
			</div>
    	</p>
    </div>
	<div class="col col-lg-12 pB10">
    	<p>
			<div class="col col-lg-3 pL0i">
				<span class="fL"><b>Số buổi học ngoài platform:</b></span>
			</div>
			<div class="col col-lg-2" style="margin-left:-150px">
				<span class="fL"><?php echo $payment->total_non_platform_session . " buổi"?></span>
			</div>
    	</p>
    </div>
	<div class="col col-lg-12 pB10">
    	<p>
			<div class="col col-lg-3 pL0i">
				<span class="fL"><b>Tổng số buổi học:</b></span>
			</div>
			<div class="col col-lg-2" style="margin-left:-150px">
				<span class="fL"><?php echo ($payment->total_platform_session + $payment->total_non_platform_session) . " buổi"?></span>
			</div>
    	</p>
    </div>
	<div class="col col-lg-12 pB10">
    	<p>
    		<div class="col col-lg-3 pL0i">
    			<span class="fL"><b>Trạng thái:</b>&nbsp;<?php echo $payment->getStatus()?></span>
    		</div>
    		<div class="col col-lg-3 pL0i">
    			<span class="fL"><b>Ngày tổng hợp:</b>&nbsp;<?php echo ($payment->report_date != null) ? date('d-m-Y', strtotime($payment->report_date)) : "";?></span>
    		</div>
			<div class="col col-lg-3 pL0i">
    			<span class="fL"><b>Người tổng hợp:</b>&nbsp;<?php 
					$user = User::model()->findByPk($payment->report_user_id);
					if ($user != null)
						echo User::model()->findByPk($payment->report_user_id)->fullname();
					?>
				</span>
    		</div>
			<div class="col col-lg-3">
				<?php if($payment->report_status == TeacherPayment::STATUS_OPEN && $recordCount > 0):?>
					<button id="report" class="btn btn-primary" style="margin-top:-8px; width:100px">
						Tổng hợp
					</button>
				<?php elseif (Yii::app()->user->isAdmin()):?>
					<div class="fR">
						<a class="fs12 errorMessage" href="javascript: openPaymentEdit();">
							Cho phép chỉnh sửa
						</a>
					</div>
				<?php endif;?>
			</div>
    	</p>
    </div>
	<div class="col col-lg-12 pB10">
    	<p>
    		<div class="col col-lg-3 pL0i">
    			<span class="fL"><b>Trạng thái thanh toán:</b>&nbsp;<?php echo $payment->getPaymentStatus()?></span>
    		</div>
    		<div class="col col-lg-3 pL0i">
    			<span class="fL"><b>Ngày thanh toán:</b>&nbsp;<?php echo ($payment->payment_date != null) ? date("d-m-Y", strtotime($payment->payment_date)) : "";?></span>
    		</div>
			<div class="col col-lg-3">
			</div>
			<div class="col col-lg-3">
				<?php if($payment->report_status == TeacherPayment::STATUS_CLOSED && $payment->payment_status == TeacherPayment::STATUS_UNPAID):?>
					<button id="set_paid" class="btn btn-primary" style="margin-top:-8px; width:100px">
						Thanh toán
					</button>
				<?php elseif ($payment->payment_status == TeacherPayment::STATUS_PAID && Yii::app()->user->isAdmin()):?>
					<div class="fR">
						<a class="fs12 errorMessage" href="javascript: setUnpaid();">
							Đặt lại trạng thái thanh toán
						</a>
					</div>
				<?php endif;?>
			</div>
    	</p>
    </div>
	<?php if($recordCount == 0):?>
	<div class="col col-lg-12 pB10">
		<a class="fs12 errorMessage" href="javascript: deletePayment();">
			Không có thống kê buổi học nào trong tháng này. Xóa tổng hợp buổi học trong tháng.
		</a>
	</div>
	<?php endif;?>
</div>
<!--days-->
<?php 
	if ($payment->report_status == TeacherPayment::STATUS_OPEN || Yii::app()->user->isAdmin()){
		$buttomColumn = array(
			'class'=>'CButtonColumn',
			'template'=>'{view}{update}{delete}',
			'afterDelete'=>'function(link, success, data){if (success) window.location.reload(); else alert(data);}',
			'buttons'=>array (
		        'update'=> array(
					'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-edit mL15' ),
					'url'=>'Yii::app()->createUrl("admin/DailyRecord/update/id/$data->id")',
		        ),
		        'view'=>array(
		            'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-view mL5' ),
					'url'=>'Yii::app()->createUrl("admin/DailyRecord/view/id/$data->id")',
		        ),
		        'delete'=>array(
		            'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-remove mL15' ),
					'url'=>'Yii::app()->createUrl("admin/DailyRecord/delete/id/$data->id")',
		        ),
    		),
			'htmlOptions'=>array('style'=>'width:80px;'),
			'headerHtmlOptions'=>array('style'=>'width:80px;'),
		);
	} else {
		$buttomColumn = array(
			'class'=>'CButtonColumn',
			'template'=>'{view}',
			'buttons'=>array (
		        'view'=>array(
		            'label'=>'', 'imageUrl'=>'',
		            'options'=>array( 'class'=>'btn-view mL5' ),
		        ),
    		),
			'htmlOptions'=>array('style'=>'width:80px;'),
			'headerHtmlOptions'=>array('style'=>'width:80px;'),
		);
	}
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'dataProvider'=>$days->search($payment->id),
	'filter'=>$days,
	'enableHistory'=>true,
	'ajaxVar'=>'',
	'pager' => array('class'=>'CustomLinkPager'),
	'columns'=>array(
		array(
			'name'=>'id',
			'value'=>'$data->id',
			'htmlOptions'=>array('style'=>'width:60px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:60px;'),
		),
		array(
			'name'=>'day',
			'value'=>'date("d-m-Y", strtotime($data->day))',
			'htmlOptions'=>array('style'=>'width:60px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:60px;'),
		),
		array(
			'name'=>'platform_session',
			'value'=>'$data->platform_session . " buổi"',
			'filter'=>'',
			'htmlOptions'=>array('style'=>'width:90px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:90px;'),
		),
		array(
			'name'=>'non_platform_session',
			'value'=>'$data->non_platform_session . " buổi"',
			'filter'=>'',
			'htmlOptions'=>array('style'=>'width:90px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:90px;'),
		),
		array(
			'header'=>'Tổng số buổi học',
			'value'=>'($data->platform_session + $data->non_platform_session) . " buổi"',
			'htmlOptions'=>array('style'=>'width:80px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:80px;'),
		),
		array(
			'name'=>'note',
			'value'=>'$data->note',
			'filter'=>'',
			'htmlOptions'=>array('style'=>'width:300px; text-align:center;'),
			'headerHtmlOptions'=>array('style'=>'width:300px;'),
		),
		$buttomColumn,
	),
)); ?>
<script>
	$('#report').click(function(){
		var now = new Date();
		var monthEnd = new Date('<?php echo date('Y-m-t')?>');
		if (now <= monthEnd){
			if (confirm("Tháng này vẫn chưa kết thúc. Bạn có chắc chắn muốn tổng hợp số buổi học trong tháng này không?")){
				report();
			}
		}
	});
	
	$('#set_paid').click(function(){
		if (confirm("Bạn sẽ không thể thay đổi trạng thái thanh toán sau khi đã thanh toán")){
			setPaid();
		}
	});
	
	function report(){
		if (confirm("Sau khi tổng hợp số buổi học, bạn sẽ không thể thay đổi hoặc thêm bản ghi hàng ngày trong tháng này nữa."+
					"Hãy kiểm tra kĩ thông tin trước khi tiếp tục.")){
			$.ajax({
				url:'<?php echo Yii::app()->baseUrl?>/admin/TeacherPayment/report',
				data:{
					payment_id:<?php echo $payment->id?>,
				},
				type:'post',
				success:function(response){
					if (response.success){
						window.location.reload();
					} else {
						alert("Đã có lỗi xảy ra, vui lòng thử lại sau");
					}
				}
			});
		}
	}
	
	function setPaid(){
		$.ajax({
			url:'<?php echo Yii::app()->baseUrl?>/admin/TeacherPayment/setPaid',
			data:{
				payment_id:<?php echo $payment->id?>,
			},
			type:'post',
			success:function(response){
				if (response.success){
					window.location.reload();
				} else {
					alert("Đã có lỗi xảy ra, vui lòng thử lại sau");
				}
			}
		});
	}
	
	function openPaymentEdit(){
		if (confirm("Bạn có muốn cho phép chỉnh sửa bản tổng hợp này")){
			$.ajax({
				url:'<?php echo Yii::app()->baseUrl?>/admin/TeacherPayment/openPaymentEdit',
				data:{
					payment_id:<?php echo $payment->id?>,
				},
				type:'post',
				success:function(response){
					if (response.success){
						window.location.reload();
					} else {
						alert("Đã có lỗi xảy ra, vui lòng thử lại sau");
					}
				}
			});
		}
	}
	
	function setUnpaid(){
		if (confirm("Bạn có muốn đặt lại trạng thái của bản tổng hợp này là chưa thanh toán?")){
			$.ajax({
				url:'<?php echo Yii::app()->baseUrl?>/admin/TeacherPayment/setUnpaid',
				data:{
					payment_id:<?php echo $payment->id?>,
				},
				type:'post',
				success:function(response){
					if (response.success){
						window.location.reload();
					} else {
						alert("Đã có lỗi xảy ra, vui lòng thử lại sau");
					}
				}
			});
		}
	}
</script>