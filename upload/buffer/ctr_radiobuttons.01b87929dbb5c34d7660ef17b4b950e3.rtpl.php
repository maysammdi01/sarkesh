<div class="row">
	<div class="col-xs-<?php echo $size;?>">
		<span class=""><?php echo $label;?></span>
		<?php $counter1=-1; if( isset($e) && is_array($e) && sizeof($e) ) foreach( $e as $key1 => $value1 ){ $counter1++; ?>
			<?php echo $value1["body"];?>
		<?php } ?>
		<?php if( $help != '' ){ ?><span class="help-block"><?php echo $help;?></span><?php } ?>
	</div>
</div>
