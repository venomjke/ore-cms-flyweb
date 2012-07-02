<div class="gTh">
		<?php if($this->item['thTitleShow']): ?>
			<div class="topImageItem">
				<div id="<?php echo $this->idPrefix.$this->item['thNumberItem'];?>" class="gImgName<?php echo $this->item['text_field'];?>">
					<?php echo $this->item['thNumberItem'];?>
				</div>
				<?php if($this->item['deleteIcon']): ?>
					<div class="deleteIcon" title="<?php echo $this->item['fileName'];?>">
						<?php echo $this->item['deleteIcon'];?>
					</div>
				<?php endif;?>
			</div>
		<?php endif;?>

		<div class="imageItem" style="height: <?php echo $this->galleryConfig['thMaxHeight']; ?>px">
			<a class="gImg" rel="img-gallery<?php //echo $this->item['rel'];?>" title="<?php echo $this->item['title'];?>" href="<?php echo $this->item['urlImg'];?>">
				<img src="<?php echo $this->item['imgSrc'];?>" alt="<?php echo $this->item['title'];?>" width="<?php echo $this->item['thFileWidth']; ?>" height="<?php echo $this->item['thFileHeight']; ?>" />
			</a>
		</div>
</div>
