<?php $this->load->view("partial/header"); ?>

<?php echo form_open('transer/save',array('id'=>'transfer', 'class'=>'form-horizontal')); ?>
	<div class="form-group">
    	<label for="inputEmail3" class="col-sm-2 control-label"><?php echo $this->lang->line('config_stock_location') ?></label>
    	<div class="col-md-5">
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon3">From</span>
				<select name="from_location" class="form-control">
					<option value="">--</option>
				<?php foreach($locations as $l): ?>
				 	<option value="<?php echo $l->location_id; ?>"><?php echo $l->location_name; ?></option>
				<?php endforeach; ?>
				</select>
			  	<span class="input-group-addon" id="basic-addon3">To</span>
			  	<select name="to_location" class="form-control">
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
				<input disabled type="text" id="item_name" name="item_name" class="form-control">
				<input type="hidden" name="item_id" id="item_id">
			  	<span class="input-group-addon" id="basic-addon3">x</span>
			  	<input type="number" name="item_quantity" style="width: 100px;" readonly class="form-control" title="Quantity" data-toggle="tooltip" data-placement="top">
			  	<input type="hidden" value="" id="qty_hidden">
			</div>
    	</div>
  	</div>
  	<div class="form-group">
    	<label for="inputPassword3" class="col-sm-2 control-label"><?php echo $this->lang->line('common_quantity_to_transer'); ?></label>
    	<div class="col-md-5">
			  <input disabled type="number" style="width: auto;" name="item_quantity_to_transfer" class="form-control" title="Quantity to Transfer">
    	</div>
  	</div>
  	<div class="form-group">
    	<div class="col-sm-offset-2 col-sm-10">
    	 	<button type="submit" class="btn btn-success"><?php echo $this->lang->line('common_submit'); ?></button>
    	</div>
  	</div>

<?php echo form_close(); ?>


<script>
	var from_location = $('select[name=from_location');
	var to_location = $('select[name=to_location]');
	var item_name = $('input[name=item_name]');
	var item_quantity = $('input[name=item_quantity]');
	var item_qtf = $('input[name=item_quantity_to_transfer]');

	$(document).ready(function() {

		$(from_location).change(function(event) {
			if($(this).val() === '' || to_location.val() === ''){
				item_name.attr('disabled',true);
				item_quantity.attr('disabled',true);
				item_qtf.attr('disabled',true);
			}else{
				item_name.removeAttr('disabled');
				item_quantity.removeAttr('disabled');
				item_qtf.removeAttr('disabled');
				get_item_quantity();
			}
		});

		$(to_location).change(function(event) {
			if($(this).val() === '' || from_location.val() === ''){
				item_name.attr('disabled',true);
				item_quantity.attr('disabled',true);
				item_qtf.attr('disabled',true);
			}else{
				item_name.removeAttr('disabled');
				item_quantity.removeAttr('disabled');
				item_qtf.removeAttr('disabled');
			}
		});

	item_qtf.keyup(function(){
		calcQuantity($(this).val());
	});

	function calcQuantity(src) {
        var sum = 0;
        var bal = parseFloat($('#qty_hidden').val());

        if(!isNaN(src) && src.length!=0) {
            //sum += parseFloat(src);
        }

        //item_quantity.val(sum.toFixed(2));
        sum = bal - src;

	    if(src > bal){
	    	sum = 0;
	   	   item_qtf.val(bal.toFixed(0));
	   	   $('input[name=item_quantity_to_transfer]').parents('div.form-group').addClass('has-error');
	   	   $('input[name=item_quantity_to_transfer]').after('<span id="helpBlock2" class="help-block">Max Quantity to be transfered is '+ bal +'</span>');
	    }else{
	    	$('input[name=item_quantity_to_transfer]').parents('div.form-group').removeClass('has-success');
	   	    $('input[name=item_quantity_to_transfer]').after('');
	    }
        item_quantity.val(sum.toFixed(2));
    }

		$("#item_name").autocomplete(
	    {
			source: '<?php echo site_url("items/suggest"); ?>',
	    	minChars:0,
	       	delay:10,
	       	autoFocus: false,
			select:	function (a, ui) {
				$(this).val(ui.item.label);
				$('#item_id').val(ui.item.value);
				get_item_quantity();
				return false;
			}
	    });

	    function get_item_quantity(){
	    	$.ajax({
	    		url: '<?php echo site_url($controller_name."/get_item_id") ?>',
	    		method: 'POST',
	    		dataType: 'json',
	    		data: {item_id: $('#item_id').val(), location_id: from_location.val()},
	    		success: function(data){
	    			$.each(data, function(i, item) {
	    				item_name.val(data[i].name);
	    				item_quantity.val(data[i].quantity);
	    				$('#qty_hidden').val(data[i].quantity);
	    				if(data[i].quantity <= 0)
	    				{
	    					item_qtf.attr('disabled', true);
	    				}else{
	    					item_qtf.removeAttr('disabled');
	    				}
	    			});
	    		}
	    	});
	    	
	    }

	    $('#item_name').focus();

		$('#item_name').keypress(function (e) {
			if (e.which == 13) {
				//$('#add_item_form').submit();
				return false;
			}
		});

		$('#item_name').blur(function()
	    {
	    	$(this).attr('value',"<?php echo $this->lang->line('sales_start_typing_item_name'); ?>");
	    });
	});
</script>

<?php $this->load->view("partial/footer"); ?>