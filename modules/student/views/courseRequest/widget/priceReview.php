<!-- Begin Partial Widget: Generate price table values -->
<?php if(count($priceValues)>0):?>
	<div class="form-element-container row">
	    <div class="col col-lg-3 pR5"><label class="fR mT5">Mức thu học phí:</label></div>
	    <div class="col col-lg-9">
	    	<div class="form-element-container row">
		    	<div class="col col-lg-3 pL5">Học phí toàn khóa học:</div>
		    	<div class="col col-lg-9">
		    		<span><b><?php echo number_format($priceValues['base_price']*$priceValues['total_of_session']);?></b></span>
		    		<span>(học phí/1 buổi: <b><?php echo number_format($priceValues['base_price']);?></b>)</span>
		    	</div>
	    	</div>
	    	<div class="form-element-container row">
		    	<div class="col col-lg-12 pL5">Thông tin về mức học phí khuyến mãi và các điều kiện được hưởng (nếu có):</div>
	    	</div>
	    	<?php 
	    		if(isset($priceValues['steps'])):
	    			foreach($priceValues['steps'] as $key=>$value):
	    	?>
	    	<div class="form-element-container row">
		    	<div class="col col-lg-3 pL5">Học phí khuyến mãi:</div>
		    	<div class="col col-lg-9">
		    		<p><span><b><?php echo number_format($value['next_price']*$priceValues['total_of_session']);?></b></span>
		    		<span>(học phí/1 buổi: <b><?php echo number_format($value['next_price']);?></b>)</span>
		    		<br/><span>Ghi chú: <?php echo $value['description'];?></span></p>
		    	</div>
	    	</div>	    	
	    	<?php endforeach; endif;?>
	    </div>
	</div>
<?php endif;?>
<!-- End Partial Widget -->