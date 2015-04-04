<?php
namespace addon\plugin\page;
use \core\cls\core as core;
use \core\cls\browser as browser;


class action extends module{
	use view;
	/*
	 * construct
	 */
	function __construct(){
		parent::__construct();
	}
	
	/*
	 * show page with id
	 * @RETURN html content [title,body]
	 */
	public function show(){
		return $this->moduleShow();
	}
	
	/*
	 * show list of catalogues
	 * @RETURN html content [title,body]
	 */
	public function catalogues(){
		if($this->isLogedin())
			return $this->moduleCatalogues();
		return core\router::jump(['service','users','login','service/administrator/load/page/catalogues']);
	}

    /*
	 * show form for add new catalogue
	 * @RETURN html content [title,body]
	 */
    public function newCat(){
        if($this->isLogedin())
            return $this->moduleNewCat();
        return core\router::jump(['service','users','login','service/administrator/load/page/newCat']);
    }
    
    /*
	 * show form for delete catalogue
	 * @RETURN html content [title,body]
	 */
    public function sureDeleteCat(){
        if($this->isLogedin())
            return $this->moduleSureDeleteCat();
        return core\router::jump(['service','users','login','service/administrator/load/page/catalogues']);
    }
    
    /*
	 * edite catalogue form
	 * @RETURN html content [title,body]
	 */
    public function editeCat(){
        if($this->isLogedin())
            return $this->moduleEditeCat();
        return core\router::jump(['service','users','login','service/administrator/load/page/catalogues']);
    }
    
    /*
     * show settings page 
     * @RETURN html content [title,body]
     */
    public function settings(){
		 if($this->isLogedin())
            return $this->moduleSettings();
        return core\router::jump(['service','users','login','service/administrator/load/page/settings']);
	}
	
	/*
	 * show form for post new page
	 * @RETURN html content [title,body]
	 */
    public function newPage(){
        if($this->isLogedin())
            return $this->moduleNewPage();
        return core\router::jump(['service','users','login','service/administrator/load/page/newPage']);
    }
}
