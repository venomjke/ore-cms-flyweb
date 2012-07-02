<?php
foreach ($news as $item){
	?>
	<a class="anons" href="111">
	 	<h3><?php echo truncateText($item->title,7); ?></h3>
		<p><?php echo truncateText($item->body,10); ?></p>
	</a>
	<?php
}
?>
