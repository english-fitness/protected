<?php
/* @var $this DailyRecordController */
/* @var $model TeachingDay */
/* @var $form CActiveForm */
?>
<style>
.datepicker[readonly]{
	background-color: white;
}
</style>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'daily_record_form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<div class="page-header-toolbar-container row">
	    <div class="col col-lg-6">
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Thống kê hàng ngày mới' : 'Sửa thống kê hàng ngày';?></h2>
	    </div>
	    <div class="col col-lg-6 for-toolbar-buttons">
	        <div class="btn-group">
	        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
	        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
	        	<?php if(!$model->isNewRecord):?>
	        	<button class="btn btn-default remove" name="form_action" type="button" onclick="removeRecord();"><i class="btn-remove"></i>Xóa</button>
	        	<?php endif;?>
	        </div>
	    </div>
	</div>
	<?php if(isset($error)):?>
	<?php switch($error):
		case 'payment_edit_closed':?>
			<div>
				<span class="fs12 errorMessage pB10">
					Các buổi học trong tháng này đã được tổng hợp xong. Bạn không thể thêm, sửa hoặc xóa các bản thống kê buổi học trong tháng này. 
					Bạn có thể sửa lại ngày của bản thống kê này hoặc <a href="/admin/TeacherPayment/update/id/<?php echo $payment->id?>">click vào đây</a> 
					để xem danh sách các thống kê buổi học trong tháng này.
				</span>
			</div>
		<?php break;?>
		<?php case 'record_existed':?>
			<div>
				<span class="fs12 errorMessage pB10">
					Thống kê buổi học cho ngày này đã tồn tại. Bạn không thể tạo thêm thống kê buổi học cho ngày này. 
					Bạn có thể sửa lại ngày của bản thống kê này hoặc <a href="/admin/TeacherPayment/update/id/<?php echo $payment->id?>">click vào đây</a> 
					để xem danh sách các thống kê buổi học trong tháng này.
				</span>
			</div>
		<?php break;?>
	<?php endswitch;?>
	<?php endif;?>
<fieldset>
	<?php $disabledAttrs = (!$model->isNewRecord && !$model->allowEdit())? array('disabled'=>'disabled'):array();?>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'day'); ?>
		</div>
		<div class="col col-lg-9">
			<?php
				$day = isset($model['day'])? $model['day']: date('Y-m-d');
			?>
			<input type="text" class="datepicker" name="Record[day]" id="day" value="<?php echo $day?>" readonly>
			<?php echo $form->error($model,'day'); ?>
		</div>
	</div>	
	<?php if(!isset($payment) || (isset($error) && $error != 'record_existed')):?>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'teacher_id'); ?>
		</div>
		<div class="col col-lg-9">
			<input type="text" id="teacher_search">
			<input type="hidden" id="hidden_teacher_id" name="Record[teacher_id]">
			<span id="teacher_id_warning" class="fs12 errorMessage pB10" style="display:none">
				Hãy nhập tên giáo viên và chọn từ menu tìm kiếm.
			</span>
		</div>
	</div>
	<?php endif;?>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'platform_session'); ?>
		</div>
		<div class="col col-lg-9">
			<input type="text" name="Record[platform_session]" id="platform_session" value="<?php echo $model->platform_session?>">
			<?php echo $form->error($model,'platform_session'); ?>
		</div>
	</div>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'non_platform_session'); ?>
		</div>
		<div class="col col-lg-9">
			<input type="text" name="Record[non_platform_session]" id="non_platform_session" value="<?php echo $model->non_platform_session?>">
			<?php echo $form->error($model,'non_platform_session'); ?>
		</div>
	</div>
	
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'note'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textArea($model,'note',array('rows'=>6, 'cols'=>50, 'style'=>'height:8em;', 'value'=>$model->note, 'name'=>'Record[note]')); ?>
		</div>
	</div>
</fieldset>
<div class="clearfix h20">&nbsp;</div>
<?php $this->endWidget(); ?>

</div><!-- form -->
<script>
	function cancel(){
		window.location = daykemBaseUrl+'/admin/TeacherPayment<?php echo (isset($payment)) ? '/update/id/' . $payment->id : ''?>';
	}
	$(document).ready(function() {
		$(document).on("click",".datepicker",function(){
            $(this).datepicker({
                "dateFormat":"yy-mm-dd",
				<?php if(isset($payment)):?>
				minDate:'<?php echo date('Y-m-01')?>',
				maxDate:'<?php echo date('Y-m-t')?>',
				<?php endif;?>
            }).datepicker("show");
        });
		bindSearchBoxEvent('teacher_search', searchTeacher);
		<?php if(!isset($payment) || (isset($error) && $error != 'record_existed')):?>
		$('#daily_record_form').submit(function(){
			var teacher_id = $('#hidden_teacher_id');
			if (teacher_id.val() == ""){
				$('#teacher_id_warning').show();
				return false;
			} else {
				$('#teacher_id_warning').hide();
				return true;
			}
		});
		<?php endif;?>
	});
	
	//search box handler
	function bindSearchBoxEvent(searchBoxId, searchFunction){
		$("#"+searchBoxId).keyup(function(){
			var keyword =  $(this).val();
			if(keyword.length<=3 && keyword.length>0) {
				searchFunction.call(undefined, keyword);
			}
		});
	}

	function searchBoxAutocomplete(searchBox, results, selectCallback){
		var searchBox = $('#'+searchBox)
		if (selectCallback){
			searchBox.autocomplete({
				source: formatSearchResult(results),
				height:'50',
				select:function(e, ui){
					selectCallback.call(undefined, ui.item.id);
				},
			});
		} else {
			searchBox.autocomplete({
				source: formatSearchResult(results),
				height:'50',
			});
		}
	}

	function formatSearchResult(result){
		var formattedData = [];
		result.forEach(function(value,key){
			formattedData[formattedData.length] = {
				'value': value.usernameAndFullName,
				'id': value.id,
			}
		});
		return formattedData;
	}
	
	function searchTeacher(keyword){
		$.ajax({
			url:'<?php echo Yii::app()->baseUrl?>/admin/schedule/ajaxSearchTeacher/keyword/' + keyword,
			type:'get',
			success:function(response){
				var data = response.result;
				searchBoxAutocomplete('teacher_search', data, function(id){$('#hidden_teacher_id').val(id);});
			}
		});
	}
</script>