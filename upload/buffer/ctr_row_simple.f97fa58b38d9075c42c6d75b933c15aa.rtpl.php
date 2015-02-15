<div class="row <?php if( $pad_up ){ ?>row_pad<?php } ?>">
	<div class="col-xs-<?php echo $size;?>">
		<div class="row <?php if( $VERTICAL_ALIGN ){ ?>vertical-align<?php } ?>">
			<?php $counter1=-1; if( isset($e) && is_array($e) && sizeof($e) ) foreach( $e as $key1 => $value1 ){ $counter1++; ?>

				<div class="col-xs-<?php echo $value1["width"];?> col-centered">
					<?php echo $value1["body"];?>

				</div>
			<?php } ?>

		</div>
	</div>
</div>
