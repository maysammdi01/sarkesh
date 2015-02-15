<div class="row body_ctr_uploader_<?php echo $name;?> col-xs-12 col-sm-12 col-lg-<?php echo $size;?>">	
	<div class="panel panel-<?php echo $type;?>">
		<div class="panel-heading">
			<div class="panel-tittle">
				<?php echo $label;?> : <span><?php echo $help;?></span>
			</div>
		</div>
		<div class="panel-body">
				<div class="row">
					<div class="col-xs-12">
						<input form ="<?php echo $form;?>" name="<?php echo $name;?>" id="id_ctr_uploader_<?php echo $name;?>" class="ca_<?php echo $form;?>" type="hidden" value="" />
						<form name="form_ctr_upload_<?php echo $name;?>" id="form_ctr_upload_<?php echo $name;?>" enctype="multipart/form-data" >
						    
			    			<input type="file" name ="uploader" form="form_ctr_upload" id ="ctr_uploader_<?php echo $name;?>" onchange="ctr_uploader_onchange(this.files, 'ctr_uploader_<?php echo $name;?>')"  class="file"/>
	    				</form>
	    			</div>
    			</div>
		</div>	
	</div>
</div>
<script type="text/javascript">
	$("#ctr_uploader_<?php echo $name;?>").fileinput({
		<?php if( !$preview ){ ?>'showPreview':false,<?php } ?>
		'uploadUrl':'<?php echo $uploadUrl;?>',
		'previewFileType':'any',
		'browseLabel':'<?php echo $browse_label;?>',
		'removeLabel':'<?php echo $remove_label;?>',
		'uploadLabel':'<?php echo $upload_label;?>',
		'uploadExtraData':{id:'<?php echo $file_system_id;?>'},
		'allowedFileExtensions':[<?php echo $file_extensions;?>],
		'uploadTitle':'<?php echo $label;?>',
		'maxFileSize':'<?php echo $max_size;?>',
		'msgSizeTooLarge':'<?php echo $msgSizeTooLarge;?>',
		'msgInvalidFileExtension':'<?php echo $msgInvalidFileExtension;?>',
		'msgLoading':'<?php echo $msgLoading;?>',
		'msgProgress':'<?php echo $msgProgress;?>',
		'dropZoneEnabled':'<?php echo $can_drag;?>',
		'dropZoneTitle':'<?php echo $dropZoneTitle;?>'
		
		
	});
</script>
