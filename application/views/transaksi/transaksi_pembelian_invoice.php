<div align="center">
    <table width="612" style="font-family: Arial, sans-serif; font-size: 13.333px;">
        <tr>
            <td>
                <img width=88 height=70 src="<?php echo base_url('images/kwitansi_20865_image002.gif');?>" />
            </td>
            <td>
                <p style="text-align: center;">
                    PT AN NAMIROH TRAVELINDO<br />Jl.Raya Menanggal Timur Polres<br />Mojosari Mojokerto<br />0321-595145
                </p>
            </td>
            <td  style="text-align: center; vertical-align: text-top;">
                <p>BUKTI PEMBAYARAN<br />No: <?php echo $kode ?></p>
            </td>
        </tr>
        <tr>
            <td><br /><br /></td>
        </tr>
        <tr>
            <table width="612" style="font-family: Arial, sans-serif; font-size: 13.333px;">
                <tr>
                    <td style="width:25%">Keberangkatan</td>
                    <td style="width:25%">: <?php echo $estimasi_tgl_keberangkatan == null ? $estimasi_keberangkatan : $estimasi_tgl_keberangkatan?></td>
                    <td style="width:25%">No Invoice</td>
                    <td style="width:25%">: <?php echo $kode ?></td>
                </tr>
                <tr>
                    <td style="width:25%">Paket</td>
                    <td style="width:25%">: <?php echo $estimasi_keberangkatan.' '. $program ?></td>
                    <td style="width:25%">Tgl Invoice</td>
                    <td style="width:25%">: <?php echo date("d M Y");; ?></td>
                </tr>
                <tr>
                    <td style="width:25%">Jumlah Jamaah</td>
                    <td style="width:25%">: <?php echo $jumlah_jamaah ?> pax</td>
                    <td style="width:25%">Kepada Yth</td>
                    <td style="width:25%">: <?php echo $nama_agen != null ? $nama_agen : $nama_jamaah ?></td>
                </tr>
                <tr>
                    <td style="width:25%">Hotel Makkah - Madinah</td>
                    <td style="width:25%">: <?php echo $hotel_makkah ?> - <?php echo $hotel_madinah ?></td>
                </tr>
                <tr>
                    <td style="width:25%">Permintaan Tambahan</td>
                    <td style="width:25%">: <?php echo $permintaan_tambahan ?></td>
                </tr>
            </table>
            
        </tr>
        <tr><br /></tr>
        <tr>
            <table width="612" style="font-family: Arial, sans-serif; font-size: 13.333px; border-collapse: collapse;">
                <tr style="font-weight: bold;">
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">Keterangan</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">Qty</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">Harga (IDR)</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">Jumlah</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo $estimasi_keberangkatan.' '. $program ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo $jumlah_jamaah  ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($harga,2,',','.') ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($total,2,',','.') ?></td>
                </tr>
                <tr>
                    <td colspan="3" style="border-collapse: collapse; padding: 5px; text-align: right;">
                        <strong>Subtotal (IDR)</strong>
                    </td>
                    <td style="border-collapse: collapse; padding: 5px; text-align: left;"><?php echo number_format($total,2,',','.') ?></td>
                </tr>
                <tr>
                    <td colspan="3" style="border-collapse: collapse; padding: 5px; text-align: right;">
                        <strong>DP (IDR)</strong>
                    </td>
                    <td style="border-collapse: collapse; padding: 5px; text-align: left;"><?php echo number_format($dp,2,',','.') ?></td>
                </tr>
                <!-- <tr>
                    <td colspan="3" style="border-collapse: collapse; padding: 5px; text-align: right;">
                        <strong>Pembiayaan (IDR)</strong>
                    </td>
                    <td style="border-collapse: collapse; padding: 5px; text-align: left;">xxxxxxx</td>
                </tr> -->
                <tr>
                    <td colspan="3" style="border-collapse: collapse; padding: 5px; text-align: right;">
                        <strong>TOTAL TAGIHAN (IDR)</strong>
                    </td>
                    <td style="border-collapse: collapse; padding: 5px; text-align: left; border-top: 1px solid black;">
                        <strong><?php echo number_format( ($total - $dp),2,',','.') ?></strong>
                    </td>
                </tr>
            </table>
        </tr>
        <tr><br /></tr>
        <tr>
            <table width="612"  style="font-family: Arial, sans-serif; font-size: 13.333px;">
                <tr>
                    <td style="width: 50%">Tgl Deposit (minim 50%)</td>
                    <td><?php echo $tgl_pelunasan ?></td>
                </tr>
                <tr>
                    <td style="width: 50%">Tgl Pelunasan Maksimal (minim 50%)</td>
                    <td><?php echo $tgl_pelunasan ?></td>
                </tr>
            </table>
        </tr>
        <tr>
            <table width="612"  style="font-family: Arial, sans-serif; font-size: 13.333px;">
                <tr>
                    <td style="width: 50%; text-align:right"><br />Hormat Kami, <br/><br/><br/>Ana Maulida<br/>Divisi Keuangan</td>
                </tr>
            </table>
        </tr>
    </table>
</div>

