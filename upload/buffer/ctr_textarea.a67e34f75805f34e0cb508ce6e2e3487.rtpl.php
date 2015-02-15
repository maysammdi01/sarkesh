<div class="row">
	<div class="form-group">
		<div class="col-xs-<?php echo $size;?>">
			<label for="<?php echo $name;?>"> <?php if( $label != '' ){ ?><?php echo $label;?><?php } ?> </label>
			<textarea name="<?php echo $name;?>" id="<?php echo $name;?>" class="form-control <?php echo $id;?> ca_<?php echo $form;?> <?php if( $class != '' ){ ?><?php echo $class;?><?php } ?>" form ="<?php echo $form;?>" <?php if( $style != '' ){ ?> style="<?php echo $style;?>"<?php } ?> rows="<?php echo $rows;?>" ><?php echo $value;?></textarea> 
			<?php if( $help!='' ){ ?><span class="help-block"><?php echo $help;?></span><?php } ?>
		</div>	
	</div>
</div>

<?php if( $editor ){ ?>
	<script type="text/javascript">$(document).ready(function() {	 
		

    bkLib.onDomLoaded(function(){
      var myEditor = new nicEditor({fullPanel : true }).panelInstance('<?php echo $name;?>');
      myEditor.addEvent('blur', function() {
		for(var i=0;i<myEditor.nicInstances.length;i++){myEditor.nicInstances[i].saveContent();}
      });
    });


	});</script>
<?php } ?>
