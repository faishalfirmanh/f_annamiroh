<table cellspacing="15">
<tr>
	<td>
<?php
echo $th;
for($i=1;$i<=12;$i++){
	$data = array(
               3  => 'http://example.com/news/article/2006/03/',
               7  => 'http://example.com/news/article/2006/07/',
               13 => 'http://example.com/news/article/2006/13/',
               26 => 'http://example.com/news/article/2006/26/'
             );
	echo $this->calendar->generate($th,$i);
	if($i%4==0)
		echo '</td></tr><tr><td>';
	else
		echo '</td><td>';
}
?>
	</td>
</tr>
</table>