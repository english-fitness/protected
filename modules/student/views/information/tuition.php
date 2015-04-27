<?php
$url = Yii::app()->baseurl.'/student';
function checkActiveMenu($current,$t){
    if(Yii::app()->request->getParam('type') != $current)
        return $t->createUrl('/student/information/tuition',array('type'=>$current));
    return "#";
}
?>
<div class="page-title">
    <label class="tabPage"><a href="<?php echo checkActiveMenu(CoursePackageOptions::TYPE_STUDENT_NEW,$this); ?>">Học phí cho học viên mới</a> </label>
    <label class="tabPage"><a href="<?php echo checkActiveMenu(CoursePackageOptions::TYPE_STUDENT_OLD,$this); ?>">Học phí cho học viên cũ</a> </label>
</div>
<?php

$stepLabels = array('Tổng học phí giá gốc','Tổng học phí thực đóng','Học phí tính theo buổi');
$type = Yii::app()->request->getParam('type');
$where = null;
$user = Yii::app()->user->getData();
$packages = CoursePackage::model()->findAll(array('order'=>'sessions asc'));
function options($packages,$totalOfStudent){
    $data = array();
    foreach($packages as $package) {
        $option =  $package->getOption(Yii::app()->request->getParam('type'),$totalOfStudent);
        $data[0][$package->id] = isset($option->tuition)?$option->tuition:0;
        $data[1][$package->id] = isset($option->sales)?$option->sales:0;
        $data[2][$package->id] = isset($option->each_)?$option->each_:0;
    }
    return $data;
}
?>
<div style="padding: 15px">

  <h3 style="color: red;font-size:22px">
      <?php if($type == CoursePackageOptions::TYPE_STUDENT_NEW): ?>
           Dưới đây là bảng học phí dành cho các “Học viên mới” của Daykem.Hocmai.vn<br/> 
          “Học viên mới” là học viên chưa tham gia, đóng tiền bất kỳ một khóa học chính thức nào<br/> 
          Bảng học phí này được áp dụng từ ngày 15/09/2014 đến khi có thông báo mới<br/>
     <?php else: ?>
          Dưới đây là bảng học phí dành cho các “Học viên cũ” của Daykem.Hocmai.vn<br/>
           “Học viên cũ” là học viên đã tham gia, đóng tiền ít nhất một khóa học chính thức<br/>
          Bảng học phí này được áp dụng từ ngày 15/09/2014 đến khi có thông báo mới<br/>
      <?php endif; ?>
   </h3>

    <h3 class="text-primary">Học phí lớp học 1-1 (1 giáo viên 1 học sinh)</h3>
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Kiểu học phí/Số buổi</th>
                <?php foreach($packages as $key=>$label): ?>
                <th><?php echo $label->title; ?></th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
        <?php
        if(isset($stepLabels)):
            $priceAll = options($packages,CoursePackageOptions::CLASS_1_1);
            foreach($stepLabels as $key=>$stepLabel):
                $listPrices = isset($priceAll[$key])?$priceAll[$key]:null;
                ?>
                <tr class="even <?php echo ($key ==1)?'bold-text':''; ?>">
                    <td ><?php echo $stepLabel;?></td>
                    <?php if($listPrices): foreach($listPrices as $label):?>
                        <td ><?php echo Yii::app()->format->formatNumber($label); ?>đ</td>
                    <?php endforeach; endif;?>
                </tr>
            <?php endforeach; endif;?>
        </tbody>
    </table>

    <h3 class="text-primary">Học phí lớp 1-2 (1 giáo viên 2 học sinh)</h3>
    <table class="table table-hover">
        <thead>
        <tr>
            <th>Kiểu học phí/Số buổi</th>
            <?php foreach($packages as $key=>$label): ?>
                <th><?php echo $label->title; ?></th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php
        if(isset($stepLabels)):
            $priceAll = options($packages,CoursePackageOptions::CLASS_1_2);
            foreach($stepLabels as $key=>$stepLabel):
                $listPrices = isset($priceAll[$key])?$priceAll[$key]:null;
                ?>
                <tr class="even <?php echo ($key ==1)?'bold-text':''; ?>">
                    <td ><?php echo $stepLabel;?></td>
                    <?php if($listPrices): foreach($listPrices as $label):?>
                        <td ><?php echo Yii::app()->format->formatNumber($label); ?>đ</td>
                    <?php endforeach; endif;?>
                </tr>
            <?php endforeach; endif;?>
        </tbody>
    </table>
    <h3 class="text-primary">Học phí lớp học 3-6 (1 giáo viên 3 đến 6 học sinh)</h3>
    <table class="table table-hover">
        <thead>
        <tr>
            <th>Kiểu học phí/Số buổi</th>
            <?php foreach($packages as $key=>$label): ?>
                <th ><?php echo $label->title; ?></th>
            <?php endforeach; ?>
        </tr>
        </thead>
        <tbody>
        <?php
        if(isset($stepLabels)):
            $priceAll = options($packages,CoursePackageOptions::CLASS_3_6);
            foreach($stepLabels as $key=>$stepLabel):
                $listPrices = isset($priceAll[$key])?$priceAll[$key]:null;
                ?>
                <tr class="even <?php echo ($key ==1)?'bold-text':''; ?>" >
                    <td ><?php echo $stepLabel;?></td>
                    <?php if($listPrices): foreach($listPrices as $label):?>
                        <td  ><?php echo Yii::app()->format->formatNumber($label); ?>đ</td>
                    <?php endforeach; endif;?>
                </tr>
            <?php endforeach; endif;?>
        </tbody>
    </table>

    <?php if($type == CoursePackageOptions::TYPE_STUDENT_NEW): ?>
    <!-- phần ưu đã tự lập nhóm cho học viên mới -->
    <h3 style="color: red;font-size:20px">
        Với những học sinh tự lập nhóm, rủ bạn cùng học cho các lớp 1-2 và lớp 3-6<br/>
        Sẽ được hưởng ưu đãi như sau:<br/>
        (Liên hệ với tư vấn viên để được hưởng ưu đãi, hotline: 096-949-6795)
    </h3>
    <h3 class="text-primary">Ưu đãi cho học sinh tự lập nhóm lớp 1-2</h3>
    <table class="table table-hover">
        <thead>
        <tr>
            <th>Kiểu học phí/Số buổi</th>
            <th>Học thử (4 buổi)</th>
            <th>10 buổi</th>
            <th>20 buổi</th>
            <th>30 buổi</th>
            <th>50 buổi</th>
            <th>100 buổi</th>
        </tr>
        </thead>
        <tbody>
        <tr class="even">
            <td>Tổng học phí thực đóng</td>
            <td>250.000đ</td>
            <td>850.000đ</td>
            <td>1.600.000đ</td>
            <td>2.250.000đ</td>
            <td>3.600.000đ</td>
            <td>7.000.000đ</td>
        <tr class="even">
            <td>Học phí tính theo buổi</td>
            <td>62.500đ</td>
            <td>85.000đ</td>
            <td>80.000đ</td>
            <td>75.000đ</td>
            <td>72.000đ</td>
            <td>70.000đ</td>
        </tr>

        </tbody>
    </table>
    <h3 class="text-primary">Ưu đãi cho học sinh tự lập nhóm lớp 3-6</h3>
    <table class="table table-hover">
        <thead>
        <tr>
            <th>Kiểu học phí/Số buổi</th>
            <th>Học thử (4 buổi)</th>
            <th>10 buổi</th>
            <th>20 buổi</th>
            <th>30 buổi</th>
            <th>50 buổi</th>
            <th>100 buổi</th>
        </tr>
        </thead>
        <tbody>
        <tr class="even">
            <td>Tổng học phí thực đóng</td>
            <td>250.000đ</td>
            <td>700.000đ</td>
            <td>1.300.000đ</td>
            <td>1.800.000đ</td>
            <td>2.850.000đ</td>
            <td>5.500.000đ</td>
        <tr class="even">
            <td>Học phí tính theo buổi</td>
            <td>62.500đ</td>
            <td>70.000đ</td>
            <td>65.000đ</td>
            <td>60.000đ</td>
            <td>57.000đ</td>
            <td>55.000đ</td>
        </tr>

        </tbody>
    </table>

  <?php else: ?>
        <!-- phần ưu đã tự lập nhóm cho học viên cũ -->
        <h3 style="color: red;font-size:20px">
            Với những học sinh tự lập nhóm cho các lớp 1-2 và lớp 3-6<br/>
            Sẽ được hưởng ưu đãi như sau:<br/>
            (Liên hệ với tư vấn viên để được hưởng ưu đãi, hotline: 096-949-6795)
        </h3>
        <h3 class="text-primary">Ưu đãi cho học sinh tự lập nhóm lớp 1-2</h3>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Kiểu học phí/Số buổi</th>
                <th>Học thử (4 buổi)</th>
                <th>10 buổi</th>
                <th>20 buổi</th>
                <th>30 buổi</th>
                <th>50 buổi</th>
                <th>100 buổi</th>
            </tr>
            </thead>
            <tbody>
            <tr class="even">
                <td>Tổng học phí thực đóng</td>
                <td>250.000đ</td>
                <td>850.000đ</td>
                <td>1.600.000đ</td>
                <td>2.250.000đ</td>
                <td>3.600.000đ</td>
                <td>7.000.000đ</td>
            <tr class="even">
                <td>Học phí tính theo buổi</td>
                <td>62.500đ</td>
                <td>85.000đ</td>
                <td>80.000đ</td>
                <td>75.000đ</td>
                <td>72.000đ</td>
                <td>70.000đ</td>
            </tr>

            </tbody>
        </table>
        <h3 class="text-primary">Ưu đãi cho học sinh tự lập nhóm lớp 3-6</h3>
        <table class="table table-hover">
            <thead>
            <tr>
                <th>Kiểu học phí/Số buổi</th>
                <th>Học thử (4 buổi)</th>
                <th>10 buổi</th>
                <th>20 buổi</th>
                <th>30 buổi</th>
                <th>50 buổi</th>
                <th>100 buổi</th>
            </tr>
            </thead>
            <tbody>
            <tr class="even">
                <td>Tổng học phí thực đóng</td>
                <td>250.000đ</td>
                <td>700.000đ</td>
                <td>1.300.000đ</td>
                <td>1.800.000đ</td>
                <td>2.850.000đ</td>
                <td>5.500.000đ</td>
            <tr class="even">
                <td>Học phí tính theo buổi</td>
                <td>62.500đ</td>
                <td>70.000đ</td>
                <td>65.000đ</td>
                <td>60.000đ</td>
                <td>57.000đ</td>
                <td>55.000đ</td>
            </tr>

            </tbody>
        </table>
  <?php endif; ?>

</div>
