<table class="<?php if( $bs_control ){ ?>table <?php if( $border ){ ?>table-bordered <?php } ?> <?php if( $hover ){ ?>table-hover<?php } ?> <?php if( $striped ){ ?>table-striped<?php } ?><?php } ?> <?php if( $class != '' ){ ?> <?php echo $class;?><?php } ?>" >
    <thead>
		<tr class="active">
			<?php $counter1=-1; if( isset($headers) && is_array($headers) && sizeof($headers) ) foreach( $headers as $key1 => $value1 ){ $counter1++; ?>
				<th class="text-center col-md-<?php echo $headers_width["$key1"];?>">
					<?php echo $value1;?>
				</th>
			<?php } ?>
		</tr>
    </thead>
    <tbody>
			<?php $counter1=-1; if( isset($rows) && is_array($rows) && sizeof($rows) ) foreach( $rows as $key1 => $value1 ){ $counter1++; ?>
				<tr>
					<?php $counter2=-1; if( isset($value1) && is_array($value1) && sizeof($value1) ) foreach( $value1 as $key2 => $value2 ){ $counter2++; ?>
						<td class="<?php if( $align_center["$key2"] == true ){ ?>text-center<?php } ?>"><?php echo $value2;?></td>
					<?php } ?>
				</tr>
			<?php } ?>

    </tbody>
</table>
