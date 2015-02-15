<?php if( $show_header != FALSE ){ ?><p><?php echo $show_header;?></p><?php } ?>
<ul class="<?php if( $horiz == '1' ){ ?>nav navbar-nav<?php } ?>">
<?php $counter1=-1; if( isset($links) && is_array($links) && sizeof($links) ) foreach( $links as $key1 => $value1 ){ $counter1++; ?>
    <li><a href="<?php echo $value1["url"];?>"><?php echo $value1["label"];?></a></li>
<?php } ?>
</ul>