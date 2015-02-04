<?php use \core\cls\browser as browser;?>
<!DOCTYPE html> 
<head>
</#HEADERS#/>
<title></#PAGE_TITTLE#/></title>
</head>
<body> 
<div class="navbar navbar-default">
  <div class="container">
    <a class="navbar-brand" href="/"></#SITE_NAME#/></a>
    <?php browser\page::set_position('main_menu'); ?>
  </div>
</div>

<div class="container">
  <?php browser\page::set_position('slide_show'); ?>
  <div class="row">
    <div class="col-lg-4">
      <?php browser\page::set_position('sidebar1'); ?>
      <h4>Mobile First</h4>
      <p>Responsive by default, and a clean "flat" design.</p>
      
      <h4>Powerful Grid</h4>
      <p>3 responsive grid sizes to control the grid layout on tiny, small and large displays.</p>
    </div>
    
    <div class="col-lg-8">
      <?php browser\page::set_position('content'); ?>
    </div>
  </div>
  
  <hr>
  <div class="col-lg-8">
      <?php browser\page::set_position('footer'); ?>
  </div>
  
  
</div> <!-- /container -->
  </body>
</html>