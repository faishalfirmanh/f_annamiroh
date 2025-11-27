<!-- revisi 26 maret 2024 -->
<div align="center">
    <table width="612" style="font-family: Arial, sans-serif; font-size: 13.333px;">
        <tr>
            <td>
                <table style="font-family: Arial, sans-serif; font-size: 13.333px;">
                <tr>
                    <td style="width:200px">
                        <img width=88 height=70 src="<?php echo base_url('images/kwitansi_20865_image002.gif');?>" />
                    </td>
                    <td>
                        <p style="text-align: center;">
                            PT AN NAMIROH TRAVELINDO<br />Jl.Raya Menanggal Timur Polres<br />Mojosari Mojokerto<br />0321-595145
                        </p>
                    </td>
                    <!-- revisi 16 maret 2024 hapus bukti  -->
                    <td  style="text-align: right; vertical-align: text-top; width: 200px">
                        <p>BUKTI PEMBAYARAN<br />No: <?php echo $no_invoice ?>-<?php echo $id ?></p>
                    </td>
                </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td><br /><br /></td>
        </tr>
        <tr>
            <td>
            <table width="612" style="font-family: Arial, sans-serif; font-size: 13.333px;">
                <tr>
                   <td>Telah Terima Dari</td>
                   <td>: <strong><?php echo ($tanda == '+' ?  $penerima : 'PT. AN NAMIROH TRAVELINDO') ?></strong></td>
                </tr>
                <tr>
                   <td>Paket</td>
                   <td>: <?php echo $estimasi_keberangkatan.' '. $program ?></td>
                </tr>
                <tr>
                   <td>Banyaknya Uang</td>
                   <td>: Rp <?php echo format_rupiah($nominal) ?></td>
                </tr>
                <tr>
                   <td>Terbilang</td>
                   <td>: <?php echo num_to_words($nominal, '', 0, '') ?> rupiah</td>
                </tr>
                <tr>
                   <td>Untuk Pembayaran</td>
                   <td>: <?php echo $nama_transaksi ?></td>
                </tr>
            </table>
            </td>
        </tr>
        <tr>
            <br><br><br>
            <td>
                <table width="612" style="font-family: Arial, sans-serif; font-size: 13.333px;">
                    <tr>
                        <td style="width:50%;">
                            <br>Teller<br/><br/><br/><br><?php echo $nama; ?>
                        </td>
                        <td style="width:50%; text-align: right;">
                            Mojosari, <?php echo ($tanggal_transfer != '0000-00-00 00:00:00') &&  ($tanggal_transfer != null)  ?  tanggal_indo($tanggal_transfer)  : tanggal_indo($created_at) ?>
                            <br/><?php echo $tanda == '+' ?  'Penyetor' : 'Penerima' ?>
                            <br/><br/><br><?php echo (isset($penerima) ? $penerima : '-') ?>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        </tr>
    </table>
</div>

