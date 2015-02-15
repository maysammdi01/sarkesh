<!-- Fixed navbar -->
    <nav class="navbar navbar-default navbar-fixed-top navbar-inverse navbar_admin_top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="<?php echo $sarkesh_admin_url;?>"><?php echo $sarkesh_admin;?></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-left">
            <li><a href="<?php echo $sarkesh_admin_url;?>"><?php echo $dashboard;?></a></li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="<?php echo $view_site_url;?>" target="_blank"><?php echo $view_site;?></a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><span class="glyphicon glyphicon-cog" aria-hidden="true"></span><span class="caret"></span></a>
              <ul class="dropdown-menu" role="menu">
              <li role="presentation" class="dropdown-header"><?php echo $user_name;?></li>
                <li>
                  <a href="<?php echo $user_profile_url;?>"><i class="fa fa-fw fa-user"></i><?php echo $user_profile;?></a>
                </li>
              <li>
                <a href="<?php echo $user_logout_url;?>"><i class="fa fa-fw fa-power-off"></i><?php echo $user_logout;?></a>
              </li>
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>
    <div class="container-fluid">
        <div class="row content-page-admin">
            <div class="col-xs-12 col-sm-3 col-lg-2" id="Main_side_menu">
              <div class="list-group panel">
                  <?php $counter1=-1; if( isset($menu) && is_array($menu) && sizeof($menu) ) foreach( $menu as $key1 => $value1 ){ $counter1++; ?>
                      <a href="#<?php echo $value1["0"]["1"];?>" class="list-group-item list-group-item-success active" data-toggle="collapse" data-parent="#Main_side_menu"><?php echo $value1["0"]["0"];?>  <?php echo $value1["0"]["1"];?></a>
                      <div class="collapse" id="<?php echo $value1["0"]["1"];?>">
                        <?php $counter2=-1; if( isset($value1["1"]) && is_array($value1["1"]) && sizeof($value1["1"]) ) foreach( $value1["1"] as $key2 => $value2 ){ $counter2++; ?>
                            <a href="<?php echo $value2["0"];?>" class="list-group-item"><?php echo $value2["1"];?></a>
                        <?php } ?>
                      </div>  
                  <?php } ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-9 col-lg-10">
                <h4><?php echo $title;?></h4>
            <div class="col-xs-12"><?php echo $content;?></div> 
            </div>
        </div>
  <nav class="navbar navbar-default navbar-fixed-bottom">
    <div class="container-fluid">
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="https://github.com/morrning/sarkesh"><?php echo $powered_by;?></a></li>
            <li><a ><?php echo $core_version;?></a></li>
            <li><a ><?php echo $build_num;?></a></li>
          </ul>
        </div>
    </div>
  </nav>
                   
    