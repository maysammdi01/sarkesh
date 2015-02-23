    $('#input-id').on('fileuploaded', function(event, data, previewId, index) {
	    var form = data.form, files = data.files, extra = data.extra,
	    response = data.response, reader = data.reader;
	    alert('File uploaded triggered');
    });

function ctr_uploader_upload(obj){
   
    var fileSelect = document.getElementById('ctr_uploader_' + obj);
    var files = fileSelect.files;
    
    // Create a new FormData object.
    var formData = new FormData();
    var file = files[0];

  

    // Add the file to the request.
    formData.append('uploads', file, file.name);
    
     $.ajax({
            url: 'index.php?service=1&plugin=files&action=do_upload',
            type: 'POST',
            data: formData,
            cache: false,
            dataType: 'html',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function(data, textStatus, jqXHR){
				
                 if(data != '-1'){
                    //upload was successfull
                    //set number in input element
                    $('#id_ctr_uploader_' + obj).val(data);
                    
                    //disable control
                    $('.body_ctr_uploader_' + obj).prop('disabled',true);
                    
                    
                 }
                 else{
                    //error in upload file
                    //FILE IS BIG 
    					url = "?service=1&plugin=files&action=upload_error" ;
    					url = encodeURI(url);
    					   $.get(url ,
                               function(data){
    									SysShowModal(data);
    					       }
    					);
                 }
            }
        
    });
    
    
    
	
	
}
