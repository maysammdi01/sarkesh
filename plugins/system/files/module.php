<?php 
namespace core\plugin\files;
use \core\cls\db as db;
class module extends view{
	
	private $service_adr;
	
	function __construct(){
		
		$this->service_adr = SiteDomain . '/?service=1&plugin=files&action=service&id=';//just add file id to access that
		parent::__construct();	
	}
	
	/*
	 * INPUT:FILE
	 * This function store file and return file number
	 * OUTPUT:INTEGER
	 */
	 protected function module_do_upload(){
	       
            //first get active stronge divices
            $places = db\orm::findOne('file_places','state=1');
            
            //WARRNING : THIS PART WAS DEVELOPED ONLY FOR LOCAL STRONGE AND SOME OTHER LIKE FTP AND CLOUD NOT DEVELOPED.
            //I TRY TO DEVELOP THIS PARD IN BETA VERSION
            
            if($places->class_name == 'files_local'){
                //firs check for that file with this name is exists before
                $exist = file_exists($places->options . $_FILES["uploads"]["name"]);
                while($exist){
                    $number = rand(0,99999999999999);
                    $_FILES["uploads"]["name"] = $number . $_FILES["uploads"]["name"];
                    $exist = file_exists($places->options . $_FILES["uploads"]["name"]);
                }
                
                //file stored in local server 
                //access to file is like local address
                try{
                     move_uploaded_file($_FILES["uploads"]["tmp_name"],$places->options . $_FILES["uploads"]["name"]);
                    
                    $file_info = db\orm::dispense('files');
                    $file_info->name = $_FILES["uploads"]["name"];
                    $file_info->place = $places->id;
                    $file_info->address = SiteDomain . $places->options  . $_FILES["uploads"]["name"];
                    $file_info->date = time();
                    $file_info->user = '0';
                    $file_info->size = $_FILES["uploads"]["size"];
                    
                    //Save and return file id for proccess in javascript function
                    return db\orm::store($file_info);
                        
                }
                catch (Exception $e) {
                    // -1 means upload was not successful
                    return -1;
                
                }
               
            }
	 }
	  /*
	   * This function send back adress of file
	   */
	  protected function module_get_adr($id){
		  
		  if(db\orm::count('files','id=?',[$id]) != 0){
			  $file = db\orm::findOne('files','id=?',[$id]);
			  return $this->service_adr . $file->id;
		  }
		  else{
			  //file not found
			  return false;
		  }
	  } 
	
}
?>
