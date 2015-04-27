<?php
/* @var $this CartController */
/* @var $model Cart */
?>

<div class="row log-row">
    <div class="col-sm-12">
        <div class="text-cart">Seri: <a href="<?php echo $this->createUrl('/cart/cart/view',array('id'=>$data->cart_id)) ?>"><?php echo $data->cart_id; ?></a>
           - Code: <?php echo Common::formatCartCode($data->cart_code) ?> - Mệnh giá: <?php echo Yii::app()->format->formatNumber($data->cart_price); ?> vnd</div>
    </div>
</div>