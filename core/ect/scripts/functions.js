//setting of pace loading bar
//for more information about this options lock at pace documents on github
paceOptions = {
  restartOnRequestAfter: false,
  minTime: 500
}
//This variable is for use in circles
var Counter=0;
//this variable store value that return from events from server
var ReturnValue;

//this function jump page to input address
function sys_jump_page(url){
  if(url){
  	window.location = url;
  }
}

//this function use for show message in modal
function SysShowModal(data , jump_page){
	xmlDoc = $.parseXML( data ),
	$xml = $( xmlDoc ),
	$header = $xml.find( "header" );
	$content = $xml.find( "content" );
	$btnback = $xml.find( "btn-back" );
	$btnok = $xml.find( "btn-ok" );
	$type = $xml.find( "type" );
	BootstrapDialog.show({
		type: $type.text(),
		title: $header.text(),
		message: $content.text(),
		onshow: function(de){
				de.setClosable(false);
		},
		buttons: [{
				label: $btnok.text(),	       
				action: function(dialogItself){
					
					dialogItself.close(); 
					if(jump_page != '0'){
						//jump to page
						window.location.assign(jump_page);
					}
					}		       
		}]
	});  
}
