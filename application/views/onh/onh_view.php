	<?php
	//echo ! empty($h2_title) ? '<h2>' . $h2_title . '</h2>': '';
	echo ! empty($message) ? '<p class="message">' . $message . '</p>': '';
	
	$flashmessage = $this->session->flashdata('message');
	echo ! empty($flashmessage) ? '<p class="message">' . $flashmessage . '</p>': '';

	echo ! empty($pagination) ? '<p id="pagination">' . $pagination . '</p>' : '';
	echo ! empty($open_form) ? $open_form : '';
	echo ! empty($table) ? $table : '';
	echo ! empty($close_form) ? $close_form : '';

	?>