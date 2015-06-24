<?php
class PaymentController extends Controller{

    //Select class & subject
    public function actionIndex()
    {
    	$this->redirect('/student/payment/support');
    }

	/**
	 * Complete payment price by Nganluong
	 */
    public function actionSupport()
    {
		$this->subPageTitle = 'Hướng dẫn cách thức nộp học phí';
        $this->render("paymentSupport", array());
    }
    
 	/**
	 * Complete payment price by Nganluong
	 */
    public function actionNganluong($id)
    {
		$this->subPageTitle = 'Thanh toán qua Ngân lượng';
        $uid = Yii::app()->user->id;
        $attributes = array('condition'=>"deleted_flag=0 and student_id=$uid and id = $id");
        $preregisterCourse = PreregisterCourse::model()->find($attributes);
        if(!isset($preregisterCourse->id)) $this->redirect(array("list"));
        $paymentStatus = NULL;//Payment status
        $nganluong = new ClsNganluong();//Nganluong class checkout
        if(isset($_GET) && !$nganluong->setExpressCheckout){//Sandbox mode true, testing
        	$paymentStatus = $preregisterCourse->updatePaymentFromNganluong($_GET);
        }elseif(isset($_GET['token']) && $nganluong->setExpressCheckout){
        	$nlResult = $nganluong->nlCheckoutv3->GetTransactionDetail($_GET['token']);
        	if($nlResult){
        		$nlErrorcode = (string)$nlResult->error_code;
				$nlTransactionStatus = (string)$nlResult->transaction_status;
				if($nlErrorcode == '00' && ($nlTransactionStatus == '00' || $nlTransactionStatus == '04')){
					$preregisterCourse->payment_status = PreregisterCourse::PAYMENT_STATUS_PAID;
					$preregisterCourse->status = PreregisterCourse::STATUS_APPROVED;
					$preregisterCourse->save();//Update status for precourse
					$paymentStatus = $preregisterCourse->payment_status;//Payment status
					//Save nganluong online payment to history
					$preregisterCourse->saveNganluongOnlinePayment($nlResult);
				}
        	}
        }
        $this->render("paymentStatus", array(
        	'preregisterCourse' => $preregisterCourse,
        	'paymentStatus' => $paymentStatus
        ));
    }
    
	/**
	 * Checkout payment by Ngan luong form
	 */
    public function actionCheckout($id)
    {
		$this->subPageTitle = 'Thanh toán tại quầy giao dịch của ngân hàng!';
        $uid = Yii::app()->user->id;
        $user = User::model()->findByPk($uid);
        $attributes = array('condition'=>"deleted_flag=0 and student_id=$uid and id = $id");
        $preregisterCourse = PreregisterCourse::model()->find($attributes);
        if(!isset($preregisterCourse->id)) $this->redirect(array("/student/courseRequest/list"));
        $nganluong = new ClsNganluong();//Nganluong class checkout
    	$paymentMethod = isset($_GET['paymentMethod'])? $_GET['paymentMethod']: "NH_OFFLINE";
    	$bankCode = isset($_GET['bankCode'])? $_GET['bankCode']: "VCB";
       	$nlResult = $nganluong->generateCheckoutv3($user, $preregisterCourse, $paymentMethod, $bankCode);
       	if($nlResult->error_code =='00' && isset($nlResult->checkout_url)){
       		$this->redirect($nlResult->checkout_url.'&lang=en');
       	}else{
       		$this->render("checkout", array(
        		'errorMessage' => $nlResult->error_message,
        	));
       	}
    }
    
    /**
     * History of payment for a course
     */
    public function actionHistory($id)
    {

        $this->subPageTitle = 'Lịch sử thanh toán!';
        $uid = Yii::app()->user->id;
        $user = User::model()->findByPk($uid);
        $attributes = array('condition'=>"deleted_flag=0 and student_id=$uid and id = $id");
        $preregisterCourse = PreregisterCourse::model()->find($attributes);
        if(!isset($preregisterCourse->id)) $this->redirect(array("/student/courseRequest/list"));
        //Check display some Nganluong payment method
        $viewRenderParams = array(
            "preregisterCourse" => $preregisterCourse,
            "errorMessage" => "",//Error message
            "mobicardSuccess" => "",
        );
        //Check display Nganluong payment
        $checkDisplayNganluongPayment = $preregisterCourse->checkDisplayNganluongPayment();
        if($checkDisplayNganluongPayment){
            if($preregisterCourse->course_type==Course::TYPE_COURSE_NORMAL){
                //$preregisterCourse->updateByCurrentPriceTable();//Re-update price
            }elseif($preregisterCourse->course_type==Course::TYPE_COURSE_PRESET){
                $preregisterCourse->updatePresetPriceForPaymentSoon();//Calculate preset course
            }
            $viewRenderParams['checkoutUrl'] = false;
            $nganluong = new ClsNganluong();//Ngan luong checkout
            if(!$nganluong->setExpressCheckout){
                $viewRenderParams['checkoutUrl'] = $nganluong->generateCheckoutUrl($preregisterCourse);
            }else{
                $remainBankAmount = $preregisterCourse->getRemainPaymentAmount();//Remain payment amount
                if($remainBankAmount>0){
                    $nlResult = $nganluong->generateCheckoutv3($user, $preregisterCourse, 'ATM_ONLINE', 'VCB');
                    if(isset($nlResult->error_code) && $nlResult->error_code =='00'){
                        $viewRenderParams['checkoutUrl'] = $nlResult->checkout_url.'&lang=vn';
                    }else{
                        $viewRenderParams['errorMessage'] = $nlResult->error_message;
                    }
                }
            }
        }
        //Checkout & submit mobicard
        if(isset($_POST['mobiCard'])){
            $card = $_POST['mobiCard'];
            if(trim($card['seri'])!="" && trim($card['sopin'])!="" && trim($card['type'])!=""){
                $nlResult = $nganluong->mobiCardCheckout($user, $preregisterCourse, $card['seri'], $card['sopin'], $card['type']);
                if($nlResult->error_code == '00'){
                    $preregisterCourse->status = PreregisterCourse::STATUS_APPROVED;//Approved pre course
                    $preregisterCourse->saveMobicardPayment($nlResult);//Save mobicard payment
                    $mobicardRemainAmount = $preregisterCourse->getMobicardRemainPaymentAmount();
                    if($mobicardRemainAmount<=0){
                        $preregisterCourse->payment_status = PreregisterCourse::PAYMENT_STATUS_PAID;
                    }
                    $preregisterCourse->save();//Update status for precourse
                    $viewRenderParams['mobicardSuccess'] = "Bạn đã nạp thành công ".$nlResult->card_amount." vào trong tài khoản trả tiền học phí.";
                }else{
                    $viewRenderParams['errorMessage'] = $nlResult->error_message;
                }
            }else{
                $viewRenderParams['errorMessage'] = "Vui lòng nhập đầy đủ, chính xác số seri & mã số thẻ cào điện thoại!";
            }
        }

        //submit cart code
        if(isset($_POST['cart_code']) && $_POST['cart_code']){

            $cartCode = str_replace('-','',Yii::app()->request->getPost('cart_code'));
            $user = Yii::app()->user->data;
            $cartLogError = (int) $user->getUserMeta('cart_count_log_error');
            if($cartLogError >=3) {
                 $viewRenderParams['cartCodeError'] = 'Tài khoản của bạn đã bị khóa tính năng nạp thẻ khuyến mãi xin vui lòng liên hệ với quản trị viên để được mở tính năng này!';
            } else {
                $cartCodeObject = Cart::model()->findCartByCode($cartCode);
                if($cartCodeObject) {
                    $preregisterCourse->final_price -= $cartCodeObject->cart_price;
                    $preregisterCourse->mobicard_final_price -= $cartCodeObject->cart_price;
                    $preregisterCourse->save();
                    $cartCodeObject->cart_status = Cart::STATUS_USED;
                    $cartCodeObject->save();
                    $textLog = 'vừa thực hiện nạp thẻ khuyến mãi cho khóa học:('.$preregisterCourse->id.')'.' '.$preregisterCourse->title.', khóa học đã được giảm '.Yii::app()->format->formatNumber($cartCodeObject->cart_price).' VND';
                    CartLog::model()->log($cartCodeObject,$textLog);
                    CartNotice::model()->send($cartCodeObject,$textLog);
                    $viewRenderParams['cartCodeSucess'] = $cartCodeObject->cart_price;
                    $user->updateUserMeta('cart_count_log_error',0);
                } else {
                    $cartLogError = $cartLogError+1;
                    $user->updateUserMeta('cart_count_log_error',$cartLogError);
                    $viewRenderParams['cartCodeError'] = 'Mã khuyến mãi không tồn tại hoặc đã được sử dụng.Tài khoản của bạn sẽ bị khóa tính năng này nếu như nhập sai 3 lần';
                }
            }
        }

        $viewRenderParams['paymentHistory'] = $preregisterCourse->getPaymentHistory();
        $this->render("paymentHistory", $viewRenderParams);
    }
}

?>