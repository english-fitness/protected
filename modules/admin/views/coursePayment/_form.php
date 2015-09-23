<style>
.datepicker[readonly]{
	background-color: white;
}
</style>
<div class="form">
<?php
    
    $coursePackages = CoursePackage::model()->findAll();
    $packagePrices = array();
    foreach($coursePackages as $package){
        $options = $package->options;
        $prices = array();
        foreach ($options as $option){
            $prices[$option->id] = number_format($option->tuition)." đ";
        }
        $packagePrices[$package->id] = $prices;
    }
?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'course_payment_form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<div class="page-header-toolbar-container row">
	    <div class="col col-lg-6">
	        <h2 class="page-title mT10"><?php echo $model->isNewRecord ? 'Thêm học phí' : 'Sửa học phí';?></h2>
	    </div>
	    <div class="col col-lg-6 for-toolbar-buttons">
	        <div class="btn-group">
	        	<button class="btn btn-primary" name="form_action" type="submit"><i class="icon-save"></i>Lưu lại</button>
	        	<button class="btn btn-default cancel" name="form_action" type="button" onclick="cancel();"><i class="icon-undo"></i>Bỏ qua</button>
	        </div>
	    </div>
	</div>
<fieldset>
	<div class="form-element-container row">
		<div class="col col-lg-3">
			<label for="course_package_select">Số buổi học</label>
		</div>
		<div class="col col-lg-9">
			<select id="course_package_select" name="CoursePackage[package_option_id]" style="font-size:14px">
                <?php if (!$model->isNewRecord):?>
                    <option disabled selected style="display:none"><?php echo $model->sessions." buổi"?></option>
                <?php endif;?>
                <?php foreach($coursePackages as $package):?>
                    <option value="<?php echo $package->id?>" data-sessions="<?php echo $package->sessions?>"><?php echo $package->sessions . " buổi"?></option>
                <?php endforeach;?>
            <select>
		</div>
	</div>
    <div class="form-element-container row">
		<div class="col col-lg-3">
			<label for="price_select">Học phí</label>
		</div>
		<div class="col col-lg-9">
			<select id="price_select" name="CoursePayment[package_option_id]">
                <?php if (!$model->isNewRecord):?>
                    <option disabled selected style="display:none"><?php echo number_format($model->tuition)." đ"?></option>
                <?php endif;?>
            <select>
		</div>
	</div>
    <div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'payment_date'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textField($model,'payment_date',array("id"=>"payment_date","class"=>"datepicker", "readonly"=>true)); ?>
			<?php echo $form->error($model,'payment_date'); ?>
            <div>
                <a class="fs12 errorMessage" style="color:grey;display:none" id="clear_payment_date" href="javascript: clearPaymentDate()">Xóa ngày thanh toán</a>
            </div>
		</div>
	</div>
    <div class="form-element-container row">
		<div class="col col-lg-3">
			<?php echo $form->labelEx($model,'note'); ?>
		</div>
		<div class="col col-lg-9">
			<?php echo $form->textArea($model,'note',array('rows'=>6, 'cols'=>50, 'style'=>'height:8em;')); ?>
			<?php echo $form->error($model,'note'); ?>
		</div>
	</div>
</fieldset>
<div class="clearfix h20">&nbsp;</div>
<?php $this->endWidget(); ?>

</div><!-- form -->
<script>
    var packagePrices = <?php echo json_encode($packagePrices)?>;
    
    function cancel(){
        <?php if(Yii::app()->request->urlReferrer != null):?>
            window.location = '<?php echo Yii::app()->request->urlReferrer?>';
        <?php else:?>
            //TODO: handle the other case where there is no urlReferrer
             return false;
        <?php endif;?>
	}
	$(document).ready(function() {
		$(document).on("click",".datepicker",function(){
            $(this).datepicker({
                "dateFormat":"yy-mm-dd",
                "firstDay":1,
            }).datepicker("show");
        });
        var packageSelect = $("#course_package_select")
        packageSelect.change(function(){
            $("#price_select").html("");
            setPriceOptions(this.value);
        });
        <?php if ($model->isNewRecord):?>
            packageSelect.change();
        <?php else:?>
            $("#course_package_select > option").each(function(){
                var option = $(this);
                if (option.data("sessions") == <?php echo $model->sessions?>){
                    console.log(option[0]);
                    setPriceOptions(option.val());
                }
            })
        <?php endif;?>
        
        $("#payment_date").change(function(){
            if (this.value != ""){
                $("#clear_payment_date").show();
            }
        });
        $("#payment_date").change();
	});
    
    function setPriceOptions(packageId){
        var priceOptions = packagePrices[packageId];
        var priceSelect = $("#price_select");
        for(var i in priceOptions){
            priceSelect.append('<option value="'+i+'">'+priceOptions[i]+'</option>');
        }
    }
    
    function clearPaymentDate(){
        $("#payment_date").val("");
        $("#clear_payment_date").hide();
        return false;
    }
</script>