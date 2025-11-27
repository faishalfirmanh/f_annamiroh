<?php 
	//echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';

	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';
?>
<script type="text/javascript">
      $(document).ready(function(){
        $("#tgl_kredit").datepicker({
                          dateFormat  : "yy-mm-dd",
                          changeMonth : true,
                          changeYear  : true
        });
      });
</script>

<script type="text/javascript">
		$(function() {
			// jQuery formatCurrency plugin: http://plugins.jquery.com/project/formatCurrency
			// Format while typing & warn on decimals entered, no cents
			$('#nominal').blur(function() {
				$('#nominalNotification').html(null);
				$(this).formatCurrency({ colorize: true, negativeFormat: '-%s%n', roundToDecimalPlace: 0 });
			})
			.keyup(function(e) {
				var e = window.event || e;
				var keyUnicode = e.charCode || e.keyCode;
				if (e !== undefined) {
					switch (keyUnicode) {
						case 16: break; // Shift
						case 27: this.value = ''; break; // Esc: clear entry
						case 35: break; // End
						case 36: break; // Home
						case 37: break; // cursor left
						case 38: break; // cursor up
						case 39: break; // cursor right
						case 40: break; // cursor down
						case 78: break; // N (Opera 9.63+ maps the "." from the number key section to the "N" key too!) (See: http://unixpapa.com/js/key.html search for ". Del")
						case 110: break; // . number block (Opera 9.63+ maps the "." from the number block to the "N" key (78) !!!)
						case 190: break; // .
						default: $(this).formatCurrency({ colorize: true, negativeFormat: '-%s%n', roundToDecimalPlace: -1, eventOnDecimalsEntered: true });
					}
				}
			})
			.bind('decimalsEntered', function(e, cents) {
				var errorMsg = 'Please do not enter any cents (0.' + cents + ')';
				$('#nominalNotification').html(errorMsg);
				log('Event on decimals entered: ' + errorMsg);
			});


			// Warn on decimals entered, no cents
			$('#warnOnDecimalsEntered').blur(function() {
				$('#warnOnDecimalsEnteredNotification').html(null);
				$(this).formatCurrency({ roundToDecimalPlace: 0, eventOnDecimalsEntered: true });
			})
			.bind('decimalsEntered', function(e, cents) {
				var errorMsg = 'Please do not enter any cents (0.' + cents + ')';
				$('#warnOnDecimalsEnteredNotification').html(errorMsg);
				log('Event on decimals entered: ' + errorMsg);
			});


			function log(text) {
				$('#divLog').prepend('<div>' + text + '</div>');
			}
			
			$('#clearLog').click(function() {
				$('#divLog').empty();
			});

		});
	</script>
<script type="text/javascript">
      $(document).ready(function() {
          $('#onh_form').ketchup();
      });  
</script>	
<form name="onh_form" id="onh_form" method="post" action="<?=$form_action?>">
<input type="hidden" class="form_field" name="id_pembayaran" size="30" value="<?php echo set_value('id_pembayaran', isset($default['id_pembayaran']) ? $default['id_pembayaran'] : ''); ?>" />
<input type="hidden" class="form_field" name="angsuran_ke" size="30" value="<?php echo set_value('angsuran_ke', isset($default['angsuran_ke']) ? $default['angsuran_ke'] : ''); ?>" />
	<p>
		<label for="tgl_kredit">Tgl Kredit :</label>
		<input type="text" class="validate(date)" id="tgl_kredit" name="tgl_kredit" size="10" maxlength="10" value="<?php echo set_value('tgl_kredit', isset($default['tgl_kredit']) ? $default['tgl_kredit'] : ''); ?>" />
	</p>
	<?php echo form_error('tgl_kredit', '<p class="field_error">', '</p>');?>
	
	<p>
		<label for="nominal">Nominal (Rp.) :</label>
		<input type="text" class="validate(number)" name="nominal" id="nominal" size="20" maxlength="20" value="<?php echo set_value('nominal', isset($default['nominal']) ? $default['nominal'] : ''); ?>" />
		
	</p>
	<?php echo form_error('nominal', '<p class="field_error">', '</p>');?>	

	<p>
		<label for="button">&nbsp;</label>
		<input type="submit" name="submit" id="submit" value=" Simpan " /> <input id="button" type="button" name="batal" onclick="window.location='<?=base_url()?>index.php/pemb/kredit';" value="Batal" />
	</p>
</form>
<?php
	if ( ! empty($link))
	{
		echo '<p id="bottom_link">';
		foreach($link as $links)
		{
			echo $links . ' ';
		}
		echo '</p>';
	}
?>