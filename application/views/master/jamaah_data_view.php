<table cellspacing="1" cellspacing="1">
<tr>
<td>No Porsi</td><td>: <?=$default['no_porsi']?></td>
</tr>
<tr>
<td>Nama Jamaah</td><td>: <?=$default['nama_jamaah']?></td>
</tr>
<?php
if (! empty($default['status'])){
?>
<tr>
<td>Status</td><td>: <?=$default['status']?></td>
</tr>
<?php
}

if (! empty($default['total'])){
?>
<tr>
<td><?=$nm_total?></td><td>: Rp. <?=$default['total']?></td>
</tr>
<?php
}
?>
</table>