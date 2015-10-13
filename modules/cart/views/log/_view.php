<?php
/* @var $this LogController */
/* @var $data CartLog */
?>

<div class="row log-row">
    <div class="col-sm-12">
        <strong><i class="glyphicon glyphicon-user"></i><a href="#"> <?php echo $data->user->fullName(); ?></a>: </strong> <?php echo $data->log_value; ?>  &nbsp;<span class="text-date"><?php echo date('H:i, d/m/Y',$data->created_time); ?></span>
        <div class="text-cart">&nbsp;&nbsp; &nbsp; Số lượng: <?php echo $data->amount?></div>
    </div>
</div>