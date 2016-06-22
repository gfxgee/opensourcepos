<?php $this->load->view("partial/header"); ?>

<?php echo form_open('form',array('id'=>'transfer', 'class'=>'form-horizontal')); ?>
	<div class="form-group">
    	<label for="inputEmail3" class="col-sm-2 control-label"><?php echo $this->lang->line('config_stock_location') ?></label>
    	<div class="col-md-5">
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon3">From</span>
				<select class="form-control">
					<option value="">--</option>
				<?php foreach($locations as $l): ?>
				 	<option value="<?php echo $l->location_id; ?>"><?php echo $l->location_name; ?></option>
				<?php endforeach; ?>
				</select>
			  	<span class="input-group-addon" id="basic-addon3">To</span>
			  	<select class="form-control">
			  		<option value="">--</option>
				 	<?php foreach($locations as $l): ?>
					 	<option value="<?php echo $l->location_id; ?>"><?php echo $l->location_name; ?></option>
					<?php endforeach; ?>
				</select>
			</div>
		</div>
  	</div>
  	<div class="form-group">
    	<label for="inputPassword3" class="col-sm-2 control-label"><?php echo $this->lang->line('items_name'); ?></label>
    	<div class="col-md-5">
      		<div class="input-group">
				<input type="text" class="form-control">
			  	<span class="input-group-addon" id="basic-addon3">x</span>
			  	<input type="number" style="width: 100px;" readonly class="form-control" title="Quantity" data-toggle="tooltip" data-placement="top">
			</div>
    	</div>
  	</div>
  	<div class="form-group">
    	<label for="inputPassword3" class="col-sm-2 control-label">Quantity to Transfer</label>
    	<div class="col-md-5">
			  <input type="number" style="width: auto;" class="form-control" title="Quantity to Transfer">
    	</div>
  	</div>
  	<div class="form-group">
    	<div class="col-sm-offset-2 col-sm-10">
    	 	<button type="submit" class="btn btn-default">Sign in</button>
    	</div>
  	</div>

<?php echo form_close(); ?>

<?php $this->load->view("partial/footer"); ?>