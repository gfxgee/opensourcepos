<?php $this->load->view("partial/header"); ?>
<div id="general_error_message_box" class="error_message_box"></div>
<?php echo form_open('transer/save',array('id'=>'transfer_form', 'class'=>'form-horizontal')); ?>
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
			  	<span class="input-group-addon" id="basic-addon3">x</span>
			  	<input type="number" name="item_quantity" readonly class="form-control" title="Quantity" data-toggle="tooltip" data-placement="top">
			  	<span class="input-group-addon" id="basic-addon3">in source</span>
			  	<input type="hidden" value="" id="qty_hidden" name="qty_hidden">
			</div>
    	</div>
  	</div>
  	<div class="form-group">
    	<label for="inputPassword3" class="col-sm-2 control-label"><?php echo $this->lang->line('common_quantity_to_transer'); ?></label>
    	<div class="col-md-5">
    		  <div id="errorni" class="input-group">
				 <input disabled type="number" name="item_quantity_to_transfer" class="form-control" title="Quantity to Transfer">
				<input type="hidden" name="item_id" id="item_id">
			  	<span class="input-group-addon" id="basic-addon3">+</span>
			  	<input type="number" name="item_quantity_2" readonly class="form-control" title="Quantity" data-toggle="tooltip" data-placement="top">
			  	<span class="input-group-addon" id="basic-addon3">in destination</span>
			  	<input type="hidden" value="" name="qty_hidden_2" id="qty_hidden_2">
			</div>
    	</div>
  	</div>
  	<div class="form-group">
    	<div class="col-sm-offset-2 col-sm-10">
    	 	<button type="button" disabled id="submit-btn" class="btn btn-success"><?php echo $this->lang->line('common_submit'); ?></button>
    	</div>
  	</div>

<?php echo form_close(); ?>


<script>
	var from_location = $('select[name=from_location');
	var to_location = $('select[name=to_location]');
	var item_name = $('input[name=item_name]');
	var item_quantity = $('input[name=item_quantity]');
	var item_quantity_2 = $('input[name=item_quantity_2]');
	var item_qtf = $('input[name=item_quantity_to_transfer]');
	var sub_btn = $('#submit-btn');
	var qty_hidden_2 = $('input[name=qty_hidden_2');


	$(document).ready(function() {

		$(from_location).change(function(event) {
			if($(this).val() === '' || to_location.val() === ''){
				item_name.attr('disabled',true);
				item_quantity.attr('disabled',true);
				item_qtf.attr('disabled',true);
				sub_btn.attr('disabled', true);
			}else{
				item_name.removeAttr('disabled');
				item_quantity.removeAttr('disabled');
				//item_qtf.removeAttr('disabled');
				//sub_btn.removeAttr('disabled');
				get_item_quantity();
			}
			validate_select();
		});

		$(to_location).change(function(event) {
			if($(this).val() === '' || from_location.val() === ''){
				item_name.attr('disabled',true);
				item_quantity.attr('disabled',true);
				item_qtf.attr('disabled',true);
				sub_btn.attr('disabled', true);
			}else{
				item_name.removeAttr('disabled');
				item_quantity.removeAttr('disabled');
				//item_qtf.removeAttr('disabled');
				//sub_btn.removeAttr('disabled');
				get_item_quantity_2();
			}
			validate_select();
		});

	item_qtf.keyup(function(){
		if($(this).val() === ''){
			calcQuantity('0');
		}else{
			calcQuantity(parseFloat($(this).val()));
		}
	});

	function validate_select(){
		if(to_location.val() === from_location.val()){
			item_qtf.attr('disabled',true);
			sub_btn.attr('disabled', true);
		}else{
			item_qtf.removeAttr('disabled');
			sub_btn.removeAttr('disabled');
		}
	}

	function calcQuantity(src) {
        var sum = 0;
        var tot = 0;
        var bal = parseFloat($('#qty_hidden').val());
        var add = parseFloat($('#qty_hidden_2').val());

        sum = bal - src;
        tot = (parseFloat(add) + parseFloat(src));

	    if(src > bal){
	    	sum = 0;
	    	tot = bal + add;
	   	  	item_qtf.val(bal.toFixed(0));
	   	  	$('input[name=item_quantity_to_transfer]').parents('div.form-group').addClass('has-error');
	   	 	$('#errorni').after('<span id="helpBlock2" class="help-block">Max Quantity to be transfered is '+ bal +'</span>');
	    }else{
	    	$('input[name=item_quantity_to_transfer]').parents('div.form-group').removeClass('has-error');
	   	    $('.help-block').remove();
	    }
	    item_quantity_2.val(tot);
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
				get_item_quantity_2();
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
	    					sub_btn.attr('disabled', true);
	    					item_qtf.attr('disabled', true);
	    				}else{
	    					item_qtf.removeAttr('disabled');
	    					sub_btn.removeAttr('disabled');
	    					validate_select();
	    				}
	    			});
	    		}
	    	});	
	    }

	    function get_item_quantity_2(){
	    	$.ajax({
	    		url: '<?php echo site_url($controller_name."/get_item_id") ?>',
	    		method: 'POST',
	    		dataType: 'json',
	    		data: {item_id: $('#item_id').val(), location_id: to_location.val()},
	    		success: function(data){
	    			$.each(data, function(i, item) {
	    				item_quantity_2.val(data[i].quantity);
	    				$('#qty_hidden_2').val(data[i].quantity);
	    				if(data[i].quantity <= 0)
	    				{
	    					//sub_btn.attr('disabled', true);
	    					//item_qtf.attr('disabled', true);
	    				}else{
	    					//item_qtf.removeAttr('disabled');
	    					//sub_btn.removeAttr('disabled');
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

	    $('#transfer_form').validate($.extend({
	    	errorLabelContainer: "#general_error_message_box",
			rules:
			{
				item_quantity_to_transfer:
				{
					required:true,
					number:true,
					min: 1
				},
				from_location:
				{
					required:true
				},
				to_location:
				{
					required:true
				},
				item_name:
				{
					required:true
				},
			},
			messages: 
				{
					item_quantity_to_transfer: {
						required:"<p> * Quantity to Transfer is required</p>",
						number:"<p> * Number's only</p>",
						min: "<p> * Negative value's is not acceptable</p>"
					},
					from_location:
					{
						required: "<p> * Source Location is required</p>"
					},
					to_location:
					{
						required: "<p> * Destination Location is required</p>"
					},
					item_name:
					{
						required: "<p> * Item Name is required</p>"
					}
				}
		}));

		sub_btn.click(function(){
			if($('#transfer_form').valid() == true && $('input[name=item_quantity_to_transfer]').val() > 0 ){
				$.ajax({
					url: '<?php echo site_url($controller_name.'/save'); ?>',
					method: 'POST',
					dataType: 'json',
					data: $('#transfer_form').serialize(),
					success: function(data){
						if(data.msg == 'success'){
							$('#transfer_form').find("input[type=text],input[type=number],input[type=hidden] , select ").each(function(){
						        $(this).val('');            
						    });
							$.notify(data.msg, { type: 'success'} );
						}else{
							$.notify('Error, Please reload the page or Contact your IT expert.', { type: 'error'} );
						}
					}
				});
			}
		});

	});
</script>

<?php $this->load->view("partial/footer"); ?>