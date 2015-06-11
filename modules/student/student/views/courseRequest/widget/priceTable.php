<!-- Begin Partial Widget: Generate price table values -->
<?php
$registration = new ClsRegistration();
$priceTable = $registration->generatePriceTable($totalOfStudent,$hasTrial, $user);
$totalSessionOptions = $registration->totalSessionOptions($hasTrial);
$course = $registration->getSession("course");
$packages = CoursePackage::model()->findAll(array('order'=>'sessions asc'));
?>

<table class="table table-bordered table-striped data-grid mB0">
    <thead>
    <tr>
        <th>Ghi chú mức học phí</th>
        <?php
        $stepLabels = array('Tổng học phí giá gốc',' Tổng học phí thực đóng','Học phí tính theo buổi');
        $priceAll = array();
        foreach($packages as $key=>$label):
            if(!$user->isTraining() && $label->type == CoursePackage::TYPE_TRIAL){
                continue;
            }
            $options = $label->getOption(Yii::app()->user->data->getStatusNewOrOld(),$totalOfStudent);
            $priceAll[0][$label->id] =isset($options->tuition)?$options->tuition:0;
            $priceAll[1][$label->id] =isset($options->sales)?$options->sales:0;
            $priceAll[2][$label->id] =isset($options->each_)?$options->each_:0;
        ?>
            <?php $totalOfSession = isset($course['numberOfSession'])? $course['numberOfSession']: null; ?>
            <?php $checked = ($label->id==$totalOfSession)? 'checked="checked"': "";?>
            <th style="text-align:right;">
                <input type="radio" name="Course[numberOfSession]" id="numberOfSession" value="<?php echo $label->id;?>" <?php echo $checked;?>
                       style="margin-right:0px;">
                <span class="mL0"><?php echo $label->title;?></span>
            </th>
        <?php endforeach;?>
    </tr>
    </thead>
    <tbody>
    <?php
    if(isset($stepLabels)):
        foreach($stepLabels as $key=>$stepLabel):
            $listPrices = isset($priceAll[$key])?$priceAll[$key]:null;
            ?>
            <tr class="even <?php echo ($key ==1)?'bold-text':''; ?>">
                <td ><?php echo $stepLabel;?></td>
                <?php if($listPrices): foreach($listPrices as $label):?>
                    <td style="text-align:right;"><?php echo Yii::app()->format->formatNumber($label); ?>đ</td>
                <?php endforeach; endif;?>
            </tr>
    <?php endforeach; endif;?>
    </tbody>
</table>
<!-- End Partial Widget -->