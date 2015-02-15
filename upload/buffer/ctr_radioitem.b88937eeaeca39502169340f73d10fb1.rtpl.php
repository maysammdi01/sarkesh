<div class="radio <?php if( $class != '' ){ ?><?php echo $class;?><?php } ?>" <?php if( $style != '' ){ ?>style="<?php echo $style;?>"<?php } ?> >
  <label>
    <input type="radio" form="<?php echo $form;?>" name="<?php echo $name;?>" id="<?php echo $id;?>" value="<?php echo $value;?>" class="ca_<?php echo $form;?>" <?php if( $checked ){ ?>checked<?php } ?> <?php if( $disabled ){ ?>disabled<?php } ?>>
    <?php echo $label;?>
  </label>
</div>
