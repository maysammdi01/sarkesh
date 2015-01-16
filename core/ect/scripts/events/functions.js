// Global variables
//this variable store id of clicked button
 var ClickedButton = '';

//--------------------------------------------------------------------

//this function handel events
function ctr_system_event(obj,type, j_before, p_events_p, p_event_f, j_after){ 

	//Get all elements and push in array
	var options = SystemGetFormString(obj);
	var form_elements = SystemGetFormArray(obj);
	SystemEventsHandle(type,j_before, p_events_p, p_event_f,j_after,form_elements,options);
}
//this function return form in string
//obj is an element for input
function SystemGetFormString(obj){
	 //run javascript click function
	 var form = $(obj).attr('form');
	  
	 //Get all elements and push in array
	 var options = "";
	 $('.ca_' + form).each(function(){
		 //start control tag
		options += "control";
		options += "<!!>NAME<!>";
		options += this.name;
		//seperate controls
		if(this.type == 'select-one'){
			options += "<!>SELECTED<!>";
			options += this.value;
			options += "<!!>";
		}
		else if(this.type == 'radio'){
			options += "<!>CHECKED<!>";
			if(this.checked){
				options += '1';
			}
			else{
				options += '0';
			}
			options += "<!!>";
		}
		else if(this.type == 'checkbox'){
			options += "<!>CHECKED<!>";
			if(this.checked){
				options += '1';
			}
			else{
				options += '0';
			}
			options += "<!!>";
		}
		else{
			
			options += "<!>VALUE<!>";
			options += this.value;
			options += "<!>LABEL<!>";
			options += $(this).html();	
			options += "<!!>";
		}
	 });
	//create return element
	options += "control";
	options += "<!!>name<!>RV<!>VALUE<!>0<!>URL<!>0<!>MODAL<!>0<!>JUMP_AFTER_MODAL<!>0";
	
	//create clicked elements
	window.ClickedButton = $(obj).val();
	options += "<!!>control";
	options += "<!!>name<!>CLICK<!>VALUE<!>" + window.ClickedButton + "<!>";
	
	options = options.replace(/&/g,'_a_n_d_')
	alert(options);
	return options;
}

//this function return all form to array
function SystemGetFormArray(obj){
	 //run javascript click function
	 var form = $(obj).attr('form');
	 //Get all elements and push in array
	 var form_elements = [];
	 $('[form=' + form + ']').each(function(){
		 //add element for store
		 form_elements.push(this);
	 });
	return form_elements;
}

//this function handle javascript and php events
function SystemEventsHandle(ctr_type,j_before,p_event_p, p_event_f,j_after,form_elements,options){
	
	if(j_before != '0'){
	  window[j_before](form_elements);
	}

	if(p_event_p != '0'){
		url = "?control=1&plugin=" + p_event_p + "&action=" + p_event_f + "&options=" + options;
		$.get(url ,
			function(data){
				//alert(data);
				//data = decodeURI(data);
				data = data.replace(/_a_n_d_/g,"&");
				//find deference and set that
				window['Counter'] = 0;
				$(form_elements).each(function(){
					$(data).find($(this).attr("id")).children().each(function(){
						//check defernece

						if(this.tagName.toLowerCase() == "label"){
							$(form_elements[window['Counter']]).html($(this).html().trim());
						}
						else if(this.tagName.toLowerCase() == "selected"){
							//it's from element like SELECT tag
							
							//It's under development
							
						}
						else if(this.tagName.toLowerCase() == "checked"){
							//it's from element like checkbox tag
							
							//It's under development
							
						}
						else if(this.tagName.toLowerCase() == "value"){
							if(form_elements[window['Counter']][this.tagName.toLowerCase()].toLowerCase().trim() != $(this).html().toLowerCase().trim()){	
									$(form_elements[window['Counter']]).val($(this).html().replace(/amp;/g,""));
		
							}
							
						}
						else{

							if(form_elements[window['Counter']][this.tagName.toLowerCase()].toLowerCase().trim() != $(this).html().toLowerCase().trim()){
									$(form_elements[window['Counter']]).attr('value', $(this).html());
								
									
							}
						}
									
						
					});
					
					window['Counter'] ++;
				});
				//REDRICT PAGE IF IT'S NEED
				
				if($(data).find("RV").children("URL").html() == 'R'){
					window.location.reload(true);
				}
				else if($(data).find("RV").children("URL").html() != '0'){
					var AdrNoneFilter = $(data).find("RV").children("URL").html();
					var UrlJump = AdrNoneFilter.replace(/%/g,'&');
					window.location.assign(UrlJump);
				}
				
				//SHOW MODAL MESSAGES
				if($(data).find("RV").children("MODAL").html() != '0'){
					
					var modal = $(data).find("RV").children("MODAL").html();
					var jump_after_modal = $(data).find("RV").children("JUMP_AFTER_MODAL").html();
					SysShowModal(modal.replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&'),jump_after_modal);
				}
				
				//control is afterclick input value
				//data back from php code is stored with xml
				//get xml and create object of that
				if(j_after != '0'){
					var RV = $(data).find("RV").children("VALUE").html();
					window[j_after](RV.replace(/&lt;/g,'<').replace(/&gt;/g,'>').replace(/&amp;/g,'&'));
				}
				
			}
		); 
	}
}
