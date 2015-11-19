    <nav id="topbar" role="navigation" class="navbar navbar-fixed-top navbar-inverse">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php"><span id="logoText" class="logo-text">MOW Admin</span></a>
            </div>
            <div class="collapse navbar-collapse" >
                <div class="topbar-main">
                    <ul class="nav navbar navbar-top-links navbar-right mbn">
                        <li class="dropdown topbar-user">
                            <a><span class=""><?php echo $login_name; ?></span>&nbsp;</a>
                        </li>
                        <li>
                            <a href="logout.php"><i class="fa fa-key"></i>Log Out</a>
                        </li>
                    </ul>
                </div>
            </div><!-- /.navbar-collapse -->
        </div><!-- /.container-fluid -->


        <div id="sidebar" role="navigation" class="navbar-default left-side-collapsed navbar-static-side" >
            <div class="sidebar-collapse menu-scroll">
                <ul id="side-menu" class="nav">

                     <div class="clearfix"></div>
                    <li><a href="#"><i class="fa fa-bars fa-fw">
                        <div class="icon-bg bg-orange"></div>
                    </i><span class="menu-title">MOW Admin</span></a></li>

                    <li <?php if($page === 'Deliveries') echo 'class="active"'?> ><a href="index.php"><i class="fa fa-tachometer fa-fw">
                        <div class="icon-bg bg-orange"></div>
                    </i><span class="menu-title">Deliveries</span></a></li>

                    <li <?php if($page === 'Clients') echo 'class="active"'?> ><a href="clients.php"><i class="fa fa-users fa-fw">
                        <div class="icon-bg bg-pink"></div>
                    </i><span class="menu-title">Clients</span></a>

                    </li>

                    <li <?php if($page === 'Drivers') echo 'class="active"'?> ><a href="drivers.php"><i class="fa fa-car fa-fw">
                        <div class="icon-bg bg-green"></div>
                    </i><span class="menu-title">Drivers</span></a>

                    </li>

                    <li <?php if($page === 'Reports') echo 'class="active"'?> ><a href="reports.php"><i class="fa fa-edit fa-fw">
                        <div class="icon-bg bg-violet"></div>
                    </i><span class="menu-title">Reports</span></a>

                    </li>

                    <li <?php if($page === 'Account') echo 'class="active"'?> ><a href="account.php"><i class="fa fa-th-list fa-fw">
                        <div class="icon-bg bg-blue"></div>
                    </i><span class="menu-title">Account</span></a>

                    </li>

                </ul>
            </div>
        </div>
    </nav>