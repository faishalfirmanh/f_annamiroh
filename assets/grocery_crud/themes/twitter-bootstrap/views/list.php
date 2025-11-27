<?php
if(!empty($list)){ ?>
<div class="span12 table-responsive" >
	<table class="table table-bordered tablesorter table-striped">
		<thead>
			<tr>
				<?php if(!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)){?>
				<th class="no-sorter">
						<?php echo $this->l('list_actions'); ?>
				</th>
				<?php }?>
				<?php foreach($columns as $column){?>
				<th>
					<div class="text-left field-sorting <?php if(isset($order_by[0]) &&  $column->field_name == $order_by[0]){?><?php echo $order_by[1]?><?php }?>"
						rel="<?php echo $column->field_name?>">
						<?php echo $column->display_as; ?>
					</div>
				</th>
				<?php }?>
				
			</tr>
		</thead>
		<tbody>
			<?php foreach($list as $num_row => $row){ ?>
			<tr class="<?php echo ($num_row % 2 == 1) ? 'erow' : ''; ?>">
			<?php if(!$unset_delete || !$unset_edit || !$unset_read || !empty($actions)){?>
			<td align="left">
					<div class="tools">
						<div class="btn-group">
							<button class="btn dropdown-toggle" data-toggle="dropdown">
							<?php echo $this->l('list_actions'); ?>
								<span class="caret"></span>
							</button>
							<ul class="dropdown-menu" style="min-width: 0px; position: relative; z-index: 3;">
								<?php
								if(!$unset_read){?>
									<li>
										<a style="padding-right: 10px; padding-left: 10px;"  href="<?php echo $row->read_url?>" title="<?php echo $this->l('list_view')?> <?php echo $subject?>">
											<i class="icon-eye-open"></i>
											<!-- <?php echo $this->l('list_view') . ' ' . $subject; ?> -->
											<?php echo $this->l('list_view'); ?>
										</a>
									</li>
								<?php
								}
								if(!$unset_edit){?>
									<li>
										<a style="padding-right: 10px; padding-left: 10px;"  href="<?php echo $row->edit_url?>" title="<?php echo $this->l('list_edit')?> <?php echo $subject?>">
											<i class="icon-pencil"></i>
											<!-- <?php echo $this->l('list_edit') . ' ' . $subject; ?> -->
											<?php echo $this->l('list_edit'); ?>
										</a>
									</li>
								<?php
								}
								if(!$unset_delete){?>
									<li>
										<a style="padding-right: 10px; padding-left: 10px;" href="javascript:void(0);" data-target-url="<?php echo $row->delete_url?>" title="<?php echo $this->l('list_delete')?> <?php echo $subject?>" class="delete-row" >
											<i class="icon-trash"></i>
											<!-- <?php echo $this->l('list_delete') . ' ' . $subject; ?> -->
											<?php echo $this->l('list_delete') ?>
										</a>
									</li>
								<?php
								}
								if(!empty($row->action_urls)){
									foreach($row->action_urls as $action_unique_id => $action_url){
										$action = $actions[$action_unique_id];
										?>
										<li>
											<a href="<?php echo $action_url; ?>" class="<?php echo $action->css_class; ?> crud-action" title="<?php echo $action->label?>"><?php
											if(!empty($action->image_url)){ ?>
												<img src="<?php echo $action->image_url; ?>" alt="" />
											<?php
											}
											echo ' '.$action->label;
											?>
											</a>
										</li>
									<?php
									}
								}
								?>
								<?php
								// var_dump(in_array(2, $exceptions)); die();
								if($is_invoice){ 
								?>
									<li>
										<a href="<?php echo base_url('transaksi/pembayaran_invoice/' . $row->paket_umroh .'/'. $row->id .'/'. $row->agen) ?>" target="_blank" style="padding-right: 10px; padding-left: 10px; cursor: pointer;" class="crud-action" title="cetak invoice">
											<i class="icon-print"></i> Cetak
										</a>
									</li>
								<?php } ?>
								<?php if($is_collective_transaction){ ?>
									<li>
										<!-- revisi 16 maret 2024, remove jamaah -->
										<a href="<?php echo base_url('transaksi/transaksi_kolektif_anak/' . $row->id  ) ?>" target="_blank" style="padding-right: 10px; padding-left: 10px; cursor: pointer;" class="crud-action" title="Tambah Jamaah">
											<i class="icon-plus"></i> Tambah Jamaah
										</a>
										<a href="<?php echo base_url('transaksi/transaksi_kolektif_kontrak/' . $row->id ) ?>" target="_blank" style="padding-right: 10px; padding-left: 10px; cursor: pointer;" class="crud-action" title="Kontrak Talangan">
											<i class="icon-book"></i> Kontrak Talangan
										</a>
										<a href="<?php echo base_url('transaksi/transaksi_kolektif_invoice/' . $row->id  ) ?>" target="_blank" style="padding-right: 10px; padding-left: 10px; cursor: pointer;" class="crud-action" title="Cetak Invoice">
											<i class="icon-print"></i> Cetak Invoice
										</a>
										<a href="<?php echo base_url('transaksi/transaksi_kolektif_rincian_invoice/' . $row->id  ) ?>" target="_blank" style="padding-right: 10px; padding-left: 10px; cursor: pointer;" class="crud-action" title="Cetak Invoice">
											<i class="icon-print"></i> Cetak Rincian Invoice
										</a>
									</li>
								<?php } 
								if($is_payment_collective_transaction){ ?>
										<a href="<?php echo base_url('transaksi/transaksi_kolektif_pembayaran_invoice/' . $row->id  ) ?>" target="_blank" style="padding-right: 10px; padding-left: 10px; cursor: pointer;" class="crud-action" title="Cetak Invoice">
											<i class="icon-print"></i> Cetak Bukti Bayar
										</a>
								<?php } ?>
								
								</ul>
							</div>
							<div class="clear"></div>
						</div>
					</td>
					<?php }?>
				<?php foreach($columns as $column){?>
					<td class="<?php if(isset($order_by[0]) &&  $column->field_name == $order_by[0]){?>sorted<?php }?>">
						<div class="text-left"><?php echo ($row->{$column->field_name} != '') ? $row->{$column->field_name} : '&nbsp;' ; ?></div>
					</td>
				<?php }?>
			</tr>
				<?php } ?>
			</tbody>
		</table>
		
	</div>
	<div class="span12 table-responsive">
	<?php if(isset($footer)){ ?> 
		<?php if($footer['tag'] == 'laporan_harian' || $footer['tag'] == 'kolektif_laporan_harian') {?>
		<table class="table table-bordered tablesorter table-striped">
			<thead>
				<tr>
					<th>Jumlah Jamaah</td>
					<th>Debit (Rp)</td>
					<th>Kredit (Rp)</td>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $footer['jamaah_count'] ?></td>
					<td><?php echo $footer['debit_sum'] ?></td>
					<td><?php echo $footer['kredit_sum'] ?></td>
				</tr>
			<tbody>
		</table>
		<?php } ?>
		<?php if($footer['tag'] == 'transaksi_kredit') {?>
		<table class="table table-bordered tablesorter table-striped">
			<tbody>
				<tr>
					<td>Total Debit (IDR)</td>
					<td><?php echo $footer['debit'] ?></td>
				</tr>
				<tr>
					<td>Total Kredit (IDR)</td>
					<td><?php echo $footer['kredit'] ?></td>
				</tr>
				<tr>
					<td>Total Saldo (IDR)</td>
					<td><?php echo $footer['saldo'] ?></td>
				</tr>
			<tbody>
		</table>
		<?php } ?>
		<?php } ?>
	</div>
<?php }else{ ?>
	<br/><?php echo $this->l('list_no_items'); ?><br/><br/>
<?php }?>

<!-- Modal -->
<div id="my-exact-modal" style="top: none" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
    <h3 id="myModalLabel">Isi Nama Penyetor Kwintasi</h3>
  </div>
  <div class="modal-body">
		<form>
			<label>Penyetor</label>
			<input id="receiver" type="text" placeholder="Type something…">
			<br>
			<button type="button" id="my-submit" class="btn">Submit</button>
		</form>
  </div>
</div>

<script>
	$(document).ready(function () {
    	$('.my-modal').on('click',  (e) => {
			setTimeout(() => {
				var myModal = $('#my-exact-modal');
				var myId = e.target.dataset.myId
				myModal.data('selected-id', myId).modal('show');
			}, 1000);
			
		});
		$('#my-submit').on('click', () => {
			var id = $('#my-exact-modal').data('selected-id');
			var receiver = $('#receiver').val();
			console.log(id, receiver)
			$.ajax({
					url: '<?php echo site_url('transaksi_op/receiver_debit_update'); ?>/' + id + '/' + decodeURI(receiver),
					type: "POST",
					cache: false
			}).done((e)=> {
				var myModal = $('#my-exact-modal');
				myModal.modal('hide');
				var url = '<?php echo site_url('/kuitansi/debit') ?>/' + id; 
				window.location = url;
				console.log(url)
			}).error((e) => {
				console.log(e)
			})

		});
	});
</script>