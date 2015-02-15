<?php if( $canComment ){ ?>
<div class="well">
    <h4><?php echo $leaveCommentLabel;?></h4>
    <?php echo $form;?>
</div>
<?php } ?>
<?php $counter1=-1; if( isset($comments) && is_array($comments) && sizeof($comments) ) foreach( $comments as $key1 => $value1 ){ $counter1++; ?>
 <!-- Comment -->
 <div class="row">
  <div class="col-xs-12 well">
    <h4 class=""><?php echo $value1["username"];?><small><?php echo $value1["date"];?></small></h4>
    <?php echo $value1["comment"];?>
  </div>
</div>
<!-- Comment -->
<?php } ?>

