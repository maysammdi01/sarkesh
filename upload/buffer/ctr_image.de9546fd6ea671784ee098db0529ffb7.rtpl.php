
	<div class="text-center">
		<?php if( $href != '' ){ ?><a href="<?php echo $href;?>"><?php } ?>
		<img class="<?php if( $responsive ){ ?>img-responsive<?php } ?> <?php if( $bs_control ){ ?><?php echo $type;?><?php } ?> <?php echo $class;?>" <?php if( $style != '' ){ ?>style="<?php echo $style;?>"<?php } ?> src="<?php echo $src;?>" alt="<?php echo $alt;?>" />
		<?php if( $label != '' ){ ?><p><label><?php echo $label;?></label></p><?php } ?>
		<?php if( $href != '' ){ ?></a><?php } ?>
	</div>


