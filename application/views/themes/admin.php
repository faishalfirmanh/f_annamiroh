<html lang="en">

<head>
	<title>
		<?php if (!empty($tittle)) echo $tittle;
		else echo "Halaman Administrator"; ?>
	</title>
	<meta name="resource-type" content="document" />
	<meta name="robots" content="all, index, follow" />
	<meta name="googlebot" content="all, index, follow" />

	<?php
	/** -- Copy from here -- */
	if (!empty($meta))
		foreach ($meta as $name => $content) {
			echo "\n\t\t";
	?>
		<meta name="<?php echo $name; ?>" content="<?php echo $content; ?>" /><?php
																			}
																		echo "\n";

																		if (!empty($canonical)) {
																			echo "\n\t\t";
																				?>
		<link rel="canonical" href="<?php echo $canonical ?>" /><?php

																		}
																		echo "\n\t";
																		//print_r($js);
																		foreach ($css as $file) {
																			echo "\n\t\t";
																?>
		<link rel="stylesheet" href="<?php echo $file; ?>" type="text/css" /><?php
																			}
																			echo "\n\t";


																			/** -- to here -- */
																				?>

	<!-- Le styles -->
	<link href="<?php echo base_url(); ?>assets/themes/default/hero_files/bootstrap.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>assets/themes/default/hero_files/bootstrap-responsive.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>assets/themes/default/css/general.css" rel="stylesheet">
	<link href="<?php echo base_url(); ?>assets/themes/default/css/custom.css" rel="stylesheet">

	<!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
	<!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

	<!-- Le fav and touch icons -->
	<link rel="shortcut icon" href="<?php echo base_url(); ?>assets/themes/default/images/favicon.png" type="image/x-icon" />
	<meta property="og:image" content="<?php echo base_url(); ?>assets/themes/default/images/facebook-thumb.png" />
	<link rel="image_src" href="<?php echo base_url(); ?>assets/themes/default/images/facebook-thumb.png" />
	<style type="text/css">
		::selection {
			background-color: #E13300;
			color: white;
		}

		::moz-selection {
			background-color: #E13300;
			color: white;
		}

		::webkit-selection {
			background-color: #E13300;
			color: white;
		}

		body {
			background-color: #fff;
			margin: 40px;
			font: 13px/20px normal Helvetica, Arial, sans-serif;
			color: #4F5155;
		}

		a {
			color: #003399;
			background-color: transparent;
			font-weight: normal;
		}

		h1 {
			color: #444;
			background-color: transparent;
			border-bottom: 1px solid #D0D0D0;
			font-size: 19px;
			font-weight: normal;
			margin: 0 0 14px 0;
			padding: 14px 15px 10px 15px;
		}

		code {
			font-family: Consolas, Monaco, Courier New, Courier, monospace;
			font-size: 12px;
			background-color: #f9f9f9;
			border: 1px solid #D0D0D0;
			color: #002166;
			display: block;
			margin: 14px 0 14px 0;
			padding: 12px 10px 12px 10px;
		}

		#body {
			margin: 0 15px 0 15px;
		}

		p.footer {
			text-align: right;
			font-size: 11px;
			border-top: 1px solid #D0D0D0;
			line-height: 32px;
			padding: 0 10px 0 10px;
			margin: 20px 0 0 0;
		}

		#container {
			margin: 10px;
			border: 1px solid #D0D0D0;
			-webkit-box-shadow: 0 0 8px #D0D0D0;
		}

		/* Mengubah gambar sprite hitam menjadi merah */
		.icon-red {
			filter: invert(15%) sepia(100%) saturate(7433%) hue-rotate(359deg) brightness(118%) contrast(115%);
		}
	</style>

</head>

<body>

	<!--<div class="navbar navbar-fixed-top">-->
	<div class="navbar navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container">
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<img src="<?php echo base_url(); ?>assets/themes/default/images/logo.png" style="float:left;margin-top:5px;z-index:5" alt="logo" />
				<a class="brand" href="<?php echo site_url(); ?>">&nbsp;&nbsp;</a>
				<div style="height: 0px;" class="nav-collapse collapse">
					<ul class="nav">


						<?php

						if (isset($menu[1])) {
							echo '<ul class="nav navbar-nav">
                        <li class="menu-item dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Transaksi<b class="caret"></b></a>
                        <ul class="dropdown-menu">';
							foreach ($menu[1] as $link => $m) {
								echo '<li><a href="' . site_url($m[0]) . '">' . $m[1] . '</a></li>';
							}
							echo '</ul>
			  </li>
			  </ul>';
						}
						?>


						<?php
						if (isset($menu[2])) {
							echo '<ul class="nav navbar-nav">
                        <li class="menu-item dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Data Master<b class="caret"></b></a>
                        <ul class="dropdown-menu">';
							foreach ($menu[2] as $link => $m) {
								echo '<li><a href="' . site_url($m[0]) . '">' . $m[1] . '</a></li>';
							}
							echo ' </ul>
			</li>
			</ul>';
						}
						?>



						<?php

						if (isset($menu[3][0])) {
							echo '<ul class="nav navbar-nav">
                        <li class="menu-item dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Ticketing<b class="caret"></b></a>
                        <ul class="dropdown-menu">';
							foreach ($menu[3] as $link => $m) {
								echo '<li><a href="' . site_url($m[0]) . '">' . $m[1] . '</a></li>';
							}
							echo '</ul>
			</li>
			</ul>';
						}

						?>



						<?php

						if (isset($menu[5][0])) {
							echo '<ul class="nav navbar-nav">
                        <li class="menu-item dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Laporan<b class="caret"></b></a>
                        <ul class="dropdown-menu">';
							foreach ($menu[5] as $link => $m) {
								echo '<li><a href="' . site_url($m[0]) . '">' . $m[1] . '</a></li>';
							}
							echo '</ul>
			</li>
			</ul>';
						}

						?>
						<?php

						if (isset($menu[6][0])) {
							echo '<ul class="nav navbar-nav">
				   <li class="menu-item dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Barang<b class="caret"></b></a>
				   <ul class="dropdown-menu">';
							foreach ($menu[6] as $link => $m) {
								if ($m[2] == 1) continue;
								echo '<li><a href="' . site_url($m[0]) . '">' . $m[1] . '</a></li>';
							}
							echo '</ul>
	   </li>
	   </ul>';
						}

						?>

						<?php

						if (isset($menu[4][0])) {
							echo '<ul class="nav navbar-nav">
                        <li class="menu-item dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">User<b class="caret"></b></a>
                        <ul class="dropdown-menu">';
							foreach ($menu[4] as $link => $m) {
								echo '<li><a href="' . site_url($m[0]) . '">' . $m[1] . '</a></li>';
							}
							echo '</ul>
			</li>
			</ul>';
						}

						?>

						<?php if( $this->session->userdata('level') == 2): ?> 
							<li>
								<a href="#" id="notif_paket" style="display: none;color:red;">
									<i class="icon-envelope icon-red" style="color:red;"></i> 
									 
									<span style="color: red; font-weight: bold; margin-left: 2px;" id="nominal_notif"></span>
								</a>
							</li>
						<?php endif; ?>

						<li class="navbar-text"> <?php echo anchor('user/profile', 'Logged in as ' . $menu['nama']); ?></li>

				</div>
				<!--/.nav-collapse -->
			</div>
		</div>
	</div>

	<div class="container">
		<ul class="breadcrumb" style="margin-top:60px;">

			<?php
			if (isset($menu['shortcut'])) {
				foreach ($menu['shortcut'] as $link => $m) {
					echo "
    <li>
    <a href=\"" . base_url($m[0]) . "\">" . $m[1] . "</a>".  (count($menu['shortcut']) > 1 ? "<span class=\"divider\">|</span>" : ""). 
  "</li>";
				}
			}
			?>
			<!--
  <li class="active"><span class="divider">/</span>Umroh</li>-->
		</ul> <?php if ($this->load->get_section('text_header') != '') { ?> <h1><?php echo $this->load->get_section('text_header'); ?></h1>
	<?php } ?>
	<div class="row">
		<?php echo $this->load->get_section('sidebar'); ?>
		<?php echo $output; ?>

	</div>
	<hr />

	<div id="modalPaketUrgent" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="labelModalPaket" aria-hidden="true" style="width: 800px; margin-left: -400px;">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="labelModalPaket">
				<i class="icon-warning-sign"></i> Paket Segera Berangkat (< 25 Hari)
			</h3>
		</div>
		<div class="modal-body">
			<table class="table table-striped table-bordered table-condensed">
				<thead>
					<tr>
						<th>Program</th>
						<!-- <th>Travel</th> -->
						<th>Tgl Berangkat</th>
						<th style="text-align:center">Kuota</th>
						<th style="text-align:center">Terisi</th>
						<th style="text-align:center">Sisa</th>
						<th style="text-align:center">Status</th>
						<th style="text-align:center">Action</th>
					</tr>
				</thead>
				<tbody id="isi_tabel_paket">
				</tbody>
			</table>
		</div>
		<div class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Tutup</button>
		</div>
	</div>

	<footer>
		<div class="row" align="center">
			<div class="span6 b10">
				Copyright &copy; <a target="_blank" href="namiroh.com">PT An Namiroh Tavelindo</a> | <a target="_blank" href="https://alhidayah.id">Pesantren Al Hidayah</a>
			</div>
		</div>
	</footer>

	</div> <!-- /container -->
</body>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

<script>
    var dataPaketTersimpan = [];
    
    // Panggil fungsi saat halaman dimuat
    //checkNotifPaket();

    // function checkNotifPaket() {
    //     // 1. Cek apakah ada cache di browser dan umurnya belum 5 menit (300000 ms)
    //     let cachedData = sessionStorage.getItem('notif_paket_data');
    //     let cacheTime  = sessionStorage.getItem('notif_paket_time');
    //     let now        = new Date().getTime();

	// 	//300000 = 5menit.
    //     if (cachedData && cacheTime && (now - cacheTime < 300000)) {
    //         console.log("Load Notif dari Cache Browser");
    //         renderNotif(JSON.parse(cachedData));
    //         return; 
    //     }

    //     // 2. Jika tidak ada cache / sudah kadaluarsa, baru request ke server
    //     console.log("Load Notif dari Server (AJAX)");
    //     $.ajax({
    //         url: "<?= base_url('AjaxController/packageSeatCalculation') ?>",
    //         type: "GET",
    //         dataType: "json",
    //         success: function(response) {
    //             // Simpan hasil ke cache browser
    //             sessionStorage.setItem('notif_paket_data', JSON.stringify(response));
    //             sessionStorage.setItem('notif_paket_time', now);
                
    //             // Tampilkan notifikasi
    //             renderNotif(response);
    //         },
    //         error: function(xhr, status, error) {
    //             console.error("Gagal load notif:", error);
    //         }
    //     });
    // }

    // Fungsi terpisah untuk menampilkan UI Notif
    function renderNotif(response) {
        if (response.status && response.total > 0) {
            dataPaketTersimpan = response.data;
            $('#nominal_notif').text(response.total);
            $('#notif_paket').show(); 
        } else {
            $('#notif_paket').hide();
        }
    }

    // Event Klik Lonceng Notif
    $('#notif_paket').click(function(e) {
        e.preventDefault(); 
        var html = '';
        
        $.each(dataPaketTersimpan, function(index, item) {
			console.log('list',item)
            var qty = parseInt(item.qty);
            var terisi = parseInt(item.totalPendaftarReal);
            var sisa = qty - terisi;

            var statusLabel = '';
            var rowClass = ''; 

            // if(sisa <= 0) {
            //     statusLabel = '<span class="label label-important">Full</span>';
            //     rowClass = 'error'; 
            // } else if(sisa <= 5) {
            //     statusLabel = '<span class="label label-warning">Kritis</span>';
            //     rowClass = 'warning';
            // } else {
                statusLabel = '<span class="label label-success">Available</span>';
            //}

            html += '<tr class="'+ rowClass +'">';
            html += '<td>' + item.Program + '</td>';
            // html += '<td>' + item.travel + '</td>'; 
            html += '<td>' + item.estimasi_keberangkatan + '</td>';
            html += '<td style="text-align:center; font-weight:bold">' + qty + '</td>';
            html += '<td style="text-align:center">' + terisi + '</td>';
            html += '<td style="text-align:center; font-weight:bold; font-size:14px;">' + sisa + '</td>';
            html += '<td style="text-align:center">' + statusLabel + '</td>';
            html += '<td style="text-align:center">';
            html += '<a href="<?= base_url("transaksi_op/pembayaran/") ?>' + item.id + '" class="btn btn-mini btn-primary">Tambah</a>';
            html += '</td>';
            html += '</tr>';
        });

        $('#isi_tabel_paket').html(html);
        $('#modalPaketUrgent').modal('show');
    });
</script>

</html>