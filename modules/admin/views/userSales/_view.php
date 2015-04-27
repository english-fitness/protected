<?php
/* @var $this UserSalesController */
/* @var $data UserSalesHistory */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('preregister_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->preregister_user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sale_date')); ?>:</b>
	<?php echo CHtml::encode($data->sale_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('next_sale_date')); ?>:</b>
	<?php echo CHtml::encode($data->next_sale_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sale_note')); ?>:</b>
	<?php echo CHtml::encode($data->sale_note); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('sale_status')); ?>:</b>
	<?php echo CHtml::encode($data->sale_status); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('sale_question')); ?>:</b>
	<?php echo CHtml::encode($data->sale_question); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_answer')); ?>:</b>
	<?php echo CHtml::encode($data->user_answer); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->created_user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modified_user_id')); ?>:</b>
	<?php echo CHtml::encode($data->modified_user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('deleted_flag')); ?>:</b>
	<?php echo CHtml::encode($data->deleted_flag); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_date')); ?>:</b>
	<?php echo CHtml::encode($data->created_date); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('modified_date')); ?>:</b>
	<?php echo CHtml::encode($data->modified_date); ?>
	<br />

	*/ ?>

</div>