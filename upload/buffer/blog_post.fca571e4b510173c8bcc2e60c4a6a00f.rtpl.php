<?php if( $show_header ){ ?><a href="<?php echo $post_url;?>"><h2><?php echo $header;?></h2></a><?php } ?>
<p><?php if( $settings['show_date'] == '1' ){ ?><span class="glyphicon glyphicon-time"></span> <?php echo $post_date;?><?php } ?> <?php if( $settings['show_author'] == '1' ){ ?><?php echo $by;?> <a href="#"><?php echo $username;?></a><?php } ?></p>
<!-- Post Content -->
<?php $counter1=-1; if( isset($posts) && is_array($posts) && sizeof($posts) ) foreach( $posts as $key1 => $value1 ){ $counter1++; ?>
    <?php echo $value1["value"];?>
<?php } ?>
<hr>