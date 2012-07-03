<?php
foreach ($news as $item){
	?>
	<a class="anons" href="<?php echo $item->getUrl(); ?>">
	 	<h3><?php echo truncateText($item->title,4); ?></h3>
		<p><?php echo truncateText($item->body,10); ?></p>
	</a>
	<?php
}
?>
