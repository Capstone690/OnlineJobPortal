
            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <div class="input-group custom-search-form">
                                <input type="text" class="form-control" placeholder="Search...">
                                <span class="input-group-btn">
                                    <button class="btn btn-default" type="button">
                                        <i class="fa fa-search"></i>
                                    </button>
                                </span>
                            </div>
                            <!-- /input-group -->
                        </li>
                        
                        <li>
                            <a class="<?php echo ($sideBarActive==1)?'active':''?>" href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>
                        </li>
                        <li>
                            <a class="<?php echo ($sideBarActive==2)?'active':''?>" href="content.php"><i class="fa fa-clipboard fa-fw"></i> Content </a>
                        </li>
                        <li>
                            <a class="<?php echo ($sideBarActive==3)?'active':''?>" href="news.php"><i class="fa fa-file fa-fw"></i> News </a>
                        </li>
                        <li>
                            <a class="<?php echo ($sideBarActive==4)?'active':''?>" href="category.php"><i class="fa fa-tasks fa-fw"></i> Business Stream </a>
                        </li>
                        <li>
                            <a class="<?php echo ($sideBarActive==5)?'active':''?>" href="company.php"><i class="fa fa-building fa-fw"></i> Company </a>
                        </li>
                        <li>
                            <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Users<span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <li>
                                    <a class="<?php echo ($sideBarActive==6)?'active':''?>" href="user.php?type=e">Employer</a>
                                </li>
                                <li>
                                    <a class="<?php echo ($sideBarActive==7)?'active':''?>" href="user.php?type=j">Jobseeker</a>
                                </li>
                                
                            </ul>
                        </li>
                        <!--<li>
                            <a href="index.html"><i class="fa fa-pencil fa-fw"></i> Blog </a>
                        </li>-->
                        

                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>