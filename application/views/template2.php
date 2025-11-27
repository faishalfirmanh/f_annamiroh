<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script type="text/javascript" src="<?php echo base_url() . 'js/jquery-1.4.min.js'; ?>"></script>

<style type="text/css">@import url("<?php echo base_url() . 'css/reset.css'; ?>");</style>
<style type="text/css">@import url("<?php echo base_url() . 'css/style.css'; ?>");</style>

<title><?php echo isset($title) ? $title : ''; ?></title>
</head>

<body id="<?php echo isset($title) ? $title : ''; ?>">
<div id="main_container">
	
	<div id="header">
		<div id="masthead">
			<?php $this->load->view('masthead'); ?>
		</div>
	</div>
	<div id="nav_top">
		<?php $this->load->view('nav_top'); ?>
	</div>
	<div id="main_content">
		<div class="left_content">
		<?php $this->load->view($left_view); ?>
		</div>
		<div class="center_content">
		<h3 class="title_box"><?php echo ! empty($h2_title) ?  $h2_title : ''; ?></h3>
		<?php $this->load->view($main_view); ?>
		</div>
	</div>
	
	<div id="footer">
		<?php $this->load->view('footer'); ?>
	</div>
</div>
</body>
</html>