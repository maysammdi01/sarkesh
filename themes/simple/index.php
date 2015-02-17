<?php use \core\cls\browser as browser;?>
<!DOCTYPE html> 
<head>
</#HEADERS#/>
<title></#PAGE_TITTLE#/></title>
</head>
<body> 
        <div class="page-container">
  
	<!-- top navbar -->
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
       <div class="container">
    	<div class="navbar-header">
           <button type="button" class="navbar-toggle" data-toggle="offcanvas" data-target=".sidebar-nav">
             <span class="icon-bar"></span>
             <span class="icon-bar"></span>
             <span class="icon-bar"></span>
           </button>
           <a class="navbar-brand" href="/"></#SITE_NAME#/></a>
           <?php browser\page::position('main_menu'); ?>
    	</div>
       </div>
    </div>
      
    <div class="container">
      <div class="row row-offcanvas row-offcanvas-left">
        
        <!-- sidebar -->
        <div class="col-xs-5 col-sm-3 sidebar-offcanvas" id="sidebar" role="navigation">
            <?php browser\page::position('sidebar1'); ?>
        </div>
  	
        <!-- main area -->
        <div class="col-xs-12 col-sm-9">
			<div class="row"><div class="col-xs-12" id="sidebar"><?php browser\page::position('content'); ?></div></div>
          
          
        </div><!-- /.col-xs-12 main -->
    </div><!--/.row-->
  </div><!--/.container-->
</div><!--/.page-container-->
        
    </body>
</html>
