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
            <!-- revisi 16 maret 2024 hapus bukti  -->
            <!-- <td  style="text-align: center; vertical-align: text-top;">
                <p>BUKTI PEMBAYARAN<br />No: <?php echo $no_invoice ?></p>
            </td> -->
        </tr>
        <tr>
            <td><br /><br /></td>
        </tr>
        <tr>
            <table width="612" style="font-family: Arial, sans-serif; font-size: 13.333px;">
                <tr>
                    <td style="width:25%">Keberangkatan</td>
                    <td>:</td>
                    <td style="width:25%"><?php echo isset($estimasi_tgl_keberangkatan) == null ? $estimasi_keberangkatan : $estimasi_tgl_keberangkatan?></td>
                    <td style="width:25%">No Invoice</td>
                    <td>:</td>
                    <td style="width:25%"><?php echo $no_invoice ?></td>
                </tr>
                <tr>
                    <td style="width:25%">Paket</td>
                    <td>:</td>
                    <td style="width:25%"><?php echo $estimasi_keberangkatan.' '. $program ?></td>
                    <td style="width:25%">Tgl Invoice</td>
                    <td>:</td>
                    <td style="width:25%"><?php echo date("d M Y"); ?></td>
                </tr>
                <tr>
                    <td style="width:25%">Jumlah Jamaah</td>
                    <td>:</td>
                    <td style="width:25%"><?php echo $jumlah_jamaah ?> pax</td>
                    <td style="width:25%">Kepada Yth</td>
                    <td>:</td>
                    <td style="width:25%"><?php echo $nama_agen != null ? $nama_agen : $nama_jamaah ?></td>
                </tr>
                <!-- revisi 16 maret 2024 hapus bukti  -->
                <!-- <tr>
                    <td style="width:25%">Hotel Makkah - Madinah</td>
                    <td style="width:25%">: <?php echo $hotel_makkah ?> - <?php echo $hotel_madinah ?></td>
                </tr> -->
                <?php if($catatan != null && $catatan != ''){?>
                <tr>
                    <td style="width:25%">Catatan</td>
                    <td>:</td>
                    <td style="width:25%"><?php echo $catatan ?></td>
                </tr>
                <?php } ?>
            </table>
            
        </tr>
        <tr><br /></tr>
        <tr>
            <table width="612" style="font-family: Arial, sans-serif; font-size: 13.333px; border-collapse: collapse;">
                <tr style="font-weight: bold;">
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">Keterangan</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">Qty</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">Harga (IDR)</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">Jumlah (IDR)</td>
                </tr>
                <?php if($jumlah_jamaah != 0) {?>
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo $estimasi_keberangkatan.' '. $program ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo $jumlah_jamaah  ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo ( $harga != 0 || $harga != '' ) ? number_format($harga,2 ,',','.') : 0 ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo ( $harga != 0 || $harga != '' ) ?  number_format( ($jumlah_jamaah * $harga) ,2,',','.') : 0 ?></td>
                </tr>
                <?php } ?>
                <?php if($jumlah_upgrade_kamar_double > 0) {?>
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">
                        Upgrade Kamar Double
                    </td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">
                        <?php echo $jumlah_upgrade_kamar_double  ?>
                    </td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($harga_upgrade_kamar_double, 2 ,',','.') ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($harga_upgrade_kamar_double * $jumlah_upgrade_kamar_double,2,',','.') ?></td>
                </tr>
                <?php } ?>
                <?php if($jumlah_upgrade_kamar_triple > 0) {?>
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">
                        Upgrade Kamar Triple
                    </td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">
                        <?php echo $jumlah_upgrade_kamar_triple  ?>
                    </td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($harga_upgrade_kamar_triple, 2 ,',','.') ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($harga_upgrade_kamar_triple * $jumlah_upgrade_kamar_triple,2,',','.') ?></td>
                </tr>
                <?php } ?>
                <!-- revisi 16 maret 2024 -->
                <!-- <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">
                        Paspor (<?php echo  $nama_jamaah ?>)
                    </td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">1</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($biaya_tambahan_paspor, 2 ,',','.') ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($biaya_tambahan_paspor,2,',','.') ?></td>
                </tr>
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">
                        Vaksin (<?php echo  $nama_jamaah ?>)
                    </td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">1</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($biaya_tambahan_vaksin, 2 ,',','.') ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($biaya_tambahan_vaksin,2,',','.') ?></td>
                </tr> -->
                <?php if($biaya_tambahan_paspor != 0) {?>
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">
                        Biaya Paspor Kolektif
                    </td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">1</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($biaya_tambahan_paspor, 2 ,',','.') ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($biaya_tambahan_paspor, 2 ,',','.') ?></td>
                </tr>
                <?php } ?>
                <?php if($biaya_tambahan_vaksin != 0) {?>
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">
                        Biaya Vaksin Kolektif
                    </td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">1</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($biaya_tambahan_vaksin, 2 ,',','.') ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($biaya_tambahan_vaksin, 2 ,',','.') ?></td>
                </tr>
                <?php } ?>
                <?php if($biaya_perlengkapan_kolektif != 0) {?>
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">
                        Biaya Perlengkapan Kolektif
                    </td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">1</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($biaya_perlengkapan_kolektif, 2 ,',','.') ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($biaya_perlengkapan_kolektif, 2 ,',','.') ?></td>
                </tr>
                <?php } ?>
                <?php if($biaya_lain != 0) {?>
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">
                        <?php echo isset($biaya_lain_alias) ? $biaya_lain_alias : 'Biaya Lain-lain Kolektif'  ?>
                    </td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">1</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($biaya_lain, 2 ,',','.') ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($biaya_lain, 2 ,',','.') ?></td>
                </tr>
                <?php } ?>
                <?php if($biaya_lain_2 != 0) {?>
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">
                        <?php echo isset($biaya_lain_alias_2) ? $biaya_lain_alias_2 : 'Biaya Lain-lain Kolektif 2'  ?>
                    </td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">1</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($biaya_lain_2, 2 ,',','.') ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($biaya_lain_2, 2 ,',','.') ?></td>
                </tr>
                <?php } ?>
                <?php foreach($jamaah_anak as $j) { ?>
                <?php if($j->biaya_tambahan_paspor_anak != 0) {?>
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">
                        Paspor (<?php echo  $j->nama_jamaah_anak ?>)
                    </td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">1</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($j->biaya_tambahan_paspor_anak, 2 ,',','.') ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($j->biaya_tambahan_paspor_anak,2,',','.') ?></td>
                </tr>
                <?php } ?>
                <?php if($j->biaya_tambahan_vaksin_anak != 0) {?>
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">
                        Vaksin (<?php echo  $j->nama_jamaah_anak ?>)
                    </td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">1</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($j->biaya_tambahan_vaksin_anak, 2 ,',','.') ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($j->biaya_tambahan_vaksin_anak,2,',','.') ?></td>
                </tr>
                <?php } ?>
                <?php } ?>
                <!-- revisi 16 maret 2024 -->
                <!-- <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">
                        Biaya Lain-lain (<?php echo $nama_jamaah ?>)
                    </td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">1</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($biaya_lain, 2 ,',','.') ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($biaya_lain,2,',','.') ?></td>
                </tr> -->
                <?php foreach($jamaah_anak as $j) { ?>
                <?php if($j->biaya_lain_anak != 0) {?>
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">
                        Biaya Lain-lain (<?php echo $j->nama_jamaah_anak ?>)
                    </td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">1</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($j->biaya_lain_anak, 2 ,',','.') ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($j->biaya_lain_anak,2,',','.') ?></td>
                </tr>
                <?php } ?>
                <?php } ?>
                <!-- revisi 16 maret 2024, menambahkan biaya perlengkapan -->
                <?php foreach($jamaah_anak as $j) { ?>
                <?php if($j->biaya_perlengkapan != 0) {?>
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">
                        Biaya Perlengkapan (<?php echo $j->nama_jamaah_anak ?>)
                    </td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">1</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($j->biaya_perlengkapan, 2 ,',','.') ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($j->biaya_perlengkapan,2,',','.') ?></td>
                </tr>
                <?php } ?>
                <?php } ?>
                <tr>
                
                    <!-- <td style="width: 30%">Tgl Deposit (minim 50%)</td>
                    <td><?php echo $tanggal_deposit_minimum ?></td> -->
                    <td colspan="3" style="border-collapse: collapse; padding: 5px; text-align: right;">
                        <strong>Sub Total Biaya (IDR)</strong>
                    </td>
                    <td style="border-collapse: collapse; padding: 5px; text-align: right;"><?php echo number_format($subtotal,2,',','.') ?></td>
                </tr>
                <!-- <tr>
                    <td style="width: 30%">Tgl Pelunasan Maksimal</td>
                    <td><?php echo $tanggal_pelunasan_maksimal ?></td>
                    <td style="border-collapse: collapse; padding: 5px; text-align: right;">
                        <strong>Diskon (IDR)</strong>
                    </td>
                    <td style="border-collapse: collapse; padding: 5px; text-align: left;">(<?php echo number_format($diskon,2,',','.') ?>)</td>
                </tr> -->
                <tr>
                    <td colspan="3" style="border-collapse: collapse; padding: 5px; text-align: right;">
                        <strong>Diskon (IDR)</strong>
                    </td>
                    <td style="border-collapse: collapse; padding: 5px; text-align: right;"><?php echo number_format($diskon,2,',','.') ?></td>
                </tr>
                <tr>
                    <td colspan="3" style="border-collapse: collapse; padding: 5px; text-align: right;">
                        <strong>Total Biaya (IDR)</strong>
                    </td>
                    <td style="border-collapse: collapse; padding: 5px; text-align: right;"><?php echo number_format($total_biaya,2,',','.') ?></td>
                </tr>
                <tr>
                    <td colspan="3" style="border-collapse: collapse; padding: 5px; text-align: right;">
                        <strong>Total Deposit (IDR)</strong>
                    </td>
                    <td style="border-collapse: collapse; padding: 5px; text-align: right;"><?php echo number_format($kredit->total_nominal - $debit->total_nominal,2,',','.') ?></td>
               
                <tr>
                    <td colspan="3" style="border-collapse: collapse; padding: 5px; text-align: right;">
                        <strong>Pembiayaan (IDR)</strong>
                    </td>
                    <td style="border-collapse: collapse; padding: 5px; text-align: right;"><?php echo number_format($pembiyaan,2,',','.') ?></td>
                </tr>
                <tr>
                    <td colspan="3" style="border-collapse: collapse; padding: 5px; text-align: right;">
                        <strong>Total Tagihan (IDR)</strong>
                    </td>
                    <td style="border-collapse: collapse; padding: 5px; text-align: right; border-top: 1px solid black;">
                        <strong><?php echo number_format( ($total_biaya - (($kredit->total_nominal - $debit->total_nominal) + $pembiyaan)),2,',','.') ?></strong>
                    </td>
                </tr>
            </table>
        </tr>
        <tr><br /></tr>
        <tr>
            <table width="612"  style="font-family: Arial, sans-serif; font-size: 13.333px;">
                <tr>
                    <td style="width: 50%; text-align:right"><br />Hormat Kami, <br/><br/><br/>Ana Maulida<br/>Divisi Keuangan</td>
                </tr>
            </table>
        </tr>
        <tr>
        <!-- revisi 16 maret 2024 -->
        <br>
        <table width="612" style="font-family: Arial, sans-serif; font-size: 13.333px; border-collapse: collapse;">
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px; font-weight:bold;">Nama Bank</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px; font-weight:bold;">Atas Nama</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px; font-weight:bold;">Nomor Rekening</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">Mandiri</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">PT AN NAMIROH TRAVELINDO</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">142 001 628 348 2</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">Muamalat</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">AN NAMIROH TRAVELINDO PT </td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">704 001 354 1</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">BNI</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">AN NAMIROH TRAVELINDO PT</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">70 888 00 889</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">BSI</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">AN NAMIROH TRAVELINDO PT</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">706 901 888 7</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">BCA</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">PT AN NAMIROH TRAVELINDO </td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">6140777500</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">BRI</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">PT AN NAMIROH TRAVELINDO</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;">0586 010 007 103 08</td>
                </tr>
        </table>
        <br>
        <p><strong>RINCIAN TRANSAKSI DEBIT KREDIT (<?php echo $program ?>)</strong></p>
        <table width="612" style="font-family: Arial, sans-serif; font-size: 13.333px; border-collapse: collapse;">
            <thead>
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px; font-weight:bold;">Catatan</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px; font-weight:bold;">Tanggal Bayar</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px; font-weight:bold;">Kredit (IDR)</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px; font-weight:bold;">Debit (IDR)</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px; font-weight:bold;">Teller</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach($rincian_pembayaran as $r) {?>
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo $r->keterangan ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo tanggal_indo($r->created_at) ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo $r->tanda == '+' ? number_format($r->nominal, 2, ',', '.') : '0' ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo $r->tanda == '-' ? number_format($r->nominal, 2, ',', '.') : '0' ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo $r->nama_admin ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <br>
        <table width="612" style="font-family: Arial, sans-serif; font-size: 13.333px;">
            <tr>
                <td width="150"><strong>Total Kredit (IDR)</strong></td>
                <td>:</td>
                <td style="width: 10px; text-align:right"><?php echo number_format($kredit->total_nominal, 2, ',', '.') ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td width="150"><strong>Total Debit (IDR)</strong></td>
                <td>:</td>
                <td style="width: 10px; text-align:right"><?php echo number_format($debit->total_nominal, 2, ',', '.') ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            <tr>
                <td width="150"><strong>Total Deposit (IDR)</strong></td>
                <td>:</td>
                <td style="width: 10px; text-align:right"><?php echo  number_format(($kredit->total_nominal - $debit->total_nominal), 2, ',', '.') ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        <br>
        <p><strong>RINCIAN PEMBIAYAAN (<?php echo $program ?>)</strong></p>
        <table width="612" style="font-family: Arial, sans-serif; font-size: 13.333px; border-collapse: collapse;">
            <thead>
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px; font-weight:bold;">Catatan</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px; font-weight:bold;">Tanggal Pembiayaan</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px; font-weight:bold;">Nominal (IDR)</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px; font-weight:bold;">No Kontrak</td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px; font-weight:bold;">Nama Kontrak</td>
                </tr>
            </thead>
            <tbody>
                <?php foreach($kontrak as $r) {?>
                <tr>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo $r->catatan ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo tanggal_indo($r->tanggal_pencairan) ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo number_format($r->nominal, 2, ',', '.')?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo $r->nomor_kontrak ?></td>
                    <td style="border: 1px solid #000000;  border-collapse: collapse; padding: 5px;"><?php echo $r->nama_kontrak ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <br>
        <table width="612" style="font-family: Arial, sans-serif; font-size: 13.333px;">
            <tr>
                <td width="150"><strong>Total Pembiayaan (IDR)</strong></td>
                <td>:</td>
                <td style="width: 10px; text-align:right"><?php echo number_format($total_pembiayaan->nominal, 2, ',', '.') ?></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </table>
        </tr>
    </table>
</div>

