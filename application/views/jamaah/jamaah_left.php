<div class="arrowlistmenu">
<h3 class="menuheader expandable"><?php echo ! empty($nama_menu) ?  $nama_menu : ''; ?></h3>
<ul class="categoryitems">
<li><?php echo anchor('jamaah', '<img src='.base_url() .'images/Modify.png> Data Jamaah');?></li>
<li><?php echo anchor('jamaah/add', '<img src='.base_url() .'images/Add.png>Tambah Jamaah');?></li>
</ul>
</div>