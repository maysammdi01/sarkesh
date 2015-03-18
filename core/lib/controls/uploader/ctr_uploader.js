function ctr_uploader_onchange(obj,uploadUrl,port){
    var files = obj.files;
    
    // Create a new FormData object.
    var formData = new FormData();
    var file = files[0];
    
    // Add the file to the request.
    formData.append('uploads', file, file.name);
    formData.append('port', port);
     $.ajax({
            url: uploadUrl,
            type: 'POST',
            data: formData,
            cache: false,
            dataType: 'html',
            processData: false, // Don't process the files
            contentType: false, // Set content type to false as jQuery will tell the server its a query string request
            success: function(data, textStatus, jqXHR){
				alert(data);
            }
        
    });

}
