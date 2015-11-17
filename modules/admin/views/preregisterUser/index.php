<?php
/* @var $this PreregisterUserController */
/* @var $model PreregisterUser */

$this->breadcrumbs=array(
	'Preregister Users'=>array('index'),
	'Manage',
);
?>
<script>
    function showFileInput(show){
        if (show == undefined){
            show = true;
        }
        var inputForm = $('#import_data');
        if (!inputForm.is(":visible") && show){
            inputForm.slideDown();
        } else {
            inputForm.slideUp();
            setTimeout(function(){
                $("#file_upload_error").hide();
                $('#file_upload_indicator').hide();
            }, 300);
        }
        return false;
    }
</script>
<div class="page-header-toolbar-container row">
    <div class="col col-lg-6">
    	<?php 
    		$pageTitle = 'Danh sách đăng ký tư vấn';
    		if(isset($_GET['deleted_flag']) && $_GET['deleted_flag']==1){
    			$pageTitle = 'Đăng ký tư vấn đã bị xóa/hủy';
    		}
    	?>
        <h2 class="page-title mT10"><?php echo $pageTitle;?></h2>
    </div>
    <div class="col col-lg-6 for-toolbar-buttons">
        <div class="btn-group">
            <a class="top-bar-button btn btn-primary" href="/admin/preregisterUser/create">
			<i class="icon-plus"></i>Thêm đăng ký tư vấn
			</a>
        </div>
        <div class="btn-group">
            <a class="top-bar-button btn btn-primary" onclick="showFileInput()">
			<i class="btn-view"></i>&nbsp;Nhập dữ liệu từ file
			</a>
        </div>
    </div>
</div>
<div class="form-element-container row">
<?php if($model->deleted_flag==0):?>
	<div class="col col-lg-3">
      <a href="/admin/preregisterUser?deleted_flag=1"><span class="trash"></span>&nbsp;Danh sách đăng ký tư vấn đã bị xóa/hủy</a>
    </div>
    <div class="col col-lg-3">
      <a href="/admin/preregisterUser/saleStat"><span class="btn-view"></span>&nbsp;Thống kê nguồn đăng ký</a>
    </div>
<?php endif;?>
    <div id="import_data" class="col col-lg-6 dpn">
        <form id="file_input_form" class="fR" enctype="multipart/form-data" method="post" style="display:inline-flex; line-height:1">
            <div>
                <input type="file" id="file_input" name="spreadsheet" style="line-height:10px">
            </div>
            <div style="margin: 0 5px">
                <button type="submit">OK</button>
                <input type="button" value="cancel" id="upload_cancel">
            </div>
        </form>
        <div class="clearfix"></div>
        <p id="file_upload_error" class="dpn fR fs12 errorMessage"></p>
        <div id="file_upload_indicator" class="dpn fR" style="min-width:80px; margin-right:5px; padding-left:25px; height:25px; position:relative">
            <img id="succes_img" class="dpn" src="/media/images/icon/tick.png" style="height:20px; position:absolute; left:0; top:-5px"/>
            <p id="file_upload_message" class="fs12" style="color:blue"></p>
        </div>
    </div>
</div>
<?php $this->renderPartial('saleGridView', array('model'=>$model))?>
<script>
    var request;
    var uploaded = false;
    
    $('#file_input').change(function(){
        uploaded = false;
    });
    
    $('#upload_cancel').click(function(){
        if (request){
            request.abort();
        }
        showFileInput(false);
    });
    
    $('#file_input_form').submit(function(e){
        if (uploaded){
            return false;
        }
        e.preventDefault();
        var errorMessage = $('#file_upload_error');
        var indicator = $('#file_upload_indicator');
        var uploadMessage = $('#file_upload_message');
        if (document.getElementById('file_input').files.length > 0){
            var data = new FormData(this);
            if (errorMessage.is(':visible')){
                errorMessage.slideUp();
            }
            errorMessage.promise().done(function(){
                $('#succes_img').hide();
                uploadMessage.html('Đang tải lên');
                indicator.css('background', "url('/media/images/icon/fb-loader.gif') no-repeat top left").slideDown();
            });
            //wait a little bit for fancy animation :P
            setTimeout(function(){
                request = $.ajax({
                    url:'/admin/preregisterUser/importData',
                    type:'post',
                    data:data,
                    processData:false,
                    contentType:false,
                    success:function(response){
                        if (response.success){
                            indicator.css('background', '');
                            $('#succes_img').show();
                            uploadMessage.html('Hoàn thành');
                            $.fn.yiiGridView.update("gridView");
                            uploaded = true;
                        } else {
                            if (response.error){
                                indicator.slideUp();
                                indicator.promise().done(function(){
                                    switch(response.error){
                                        case 'file_extension_not_allowed':
                                            errorMessage.html("Chỉ chấp nhận file '.xls' và '.xlsx'").slideDown();
                                            break;
                                        case 'error_saving_records':
                                            errorMessage.html("File chứa dữ liệu không hợp lệ, vui lòng kiểm tra lại").slideDown();
                                            break;
                                        default:
                                            errorMessage.html("Đã có lỗi xảy ra, vui lòng thử lại sau").slideDown();
                                            break;
                                    }
                                });
                            }
                        }
                    },
                    error:function(){
                        indicator.slideUp();
                        indicator.promise().done(function(){
                            errorMessage.html("Không có file nào được chọn").slideDown();
                        });
                    }
                });
            },500);
        } else {
            var wait;
            if (indicator.is(':visible')){
                wait = true;
                indicator.slideUp();
            }
            if (errorMessage.is(':visible')){
                wait = true;
                errorMessage.slideUp();
            }
            if (wait){
                setTimeout(function(){
                    errorMessage.html("Không có file nào được chọn").slideDown();
                }, 350);
            } else {
                errorMessage.html("Không có file nào được chọn").slideDown();
            }
        }
        return false;
    });
</script>