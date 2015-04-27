<div class="form-element-container row">
	<div class="col col-lg-3">
		<label>Uu đãi học phí (nếu có)</label>
	</div>
	<div class="col col-lg-9">
		<?php foreach($stepPriceRules as $step=>$rules):?>
		<div class="col col-lg-12 pL0i pB10 pT10 borderBottomGrey">
			<div class="col col-lg-3 pL0i">
				<b><span class="error"><?php echo ucfirst($step);?>: Từ ngày</span></b>
				<input type="text" name="priceRule[<?php echo $step;?>][from_date]"  <?php echo $readOnlyAttrStr;?> class="datepicker" value="<?php echo $rules['from_date'];?>"/>
			</div>
			<div class="col col-lg-3 pL0i">
				<b><span class="error">Đến ngày</span></b>
				<input type="text" name="priceRule[<?php echo $step;?>][to_date]" <?php echo $readOnlyAttrStr;?> class="datepicker" value="<?php echo $rules['to_date'];?>"/>
			</div>
			<div class="col col-lg-3 pL0i">
				<b>Chuyển khoản</b>
				<input type="text" name="priceRule[<?php echo $step;?>][bank_price]" <?php echo $readOnlyAttrStr;?> value="<?php echo $rules['bank_price'];?>" maxlength="8"/>
			</div>
			<div class="col col-lg-3 pL0i">
				<b>Cào thẻ</b>
				<input type="text" name="priceRule[<?php echo $step;?>][mobicard_price]" <?php echo $readOnlyAttrStr;?> value="<?php echo $rules['mobicard_price'];?>" maxlength="8"/>
			</div>
			<div class="col col-lg-12 clearfix pL0i">
				<b>Ghi chú ưu đãi <?php echo ucfirst($step);?></b>
				<input type="text" name="priceRule[<?php echo $step;?>][description]" <?php echo $readOnlyAttrStr;?> placeholder="VD: Giảm giá mức <?php echo ucfirst($step);?>" value="<?php echo $rules['description'];?>"/>
			</div>
		</div>
		<?php endforeach;?>
	</div>
</div>