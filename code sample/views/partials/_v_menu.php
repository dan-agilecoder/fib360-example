<nav class="navbar navbar-inverse navbar-fixed-top navbar-standard" role="navigation">
    <div class="container">
        <div class="navbar-header" id="navbarHeader">
            <button type="button" class="navbar-toggle collapsed" id="" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#"><img src="<?= IMG.'LogoFIB360Simple.png'; ?>" alt="Logo FIB360" height="40"></a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
        <?php if (!((isset($first_login) &&  ($first_login))) ) { ?>
            <ul class="nav navbar-nav">
                <li class="dropdown <?php if(($active === 'home') OR ($active === 'feedback360') OR ($active === 'services')) echo "active"; ?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Sobre Fib360 <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li <?php if($active === 'home')        echo 'class="active"'; ?>><a href="<?= base_url(); ?>">Inicio</a></li>
                        <li <?php if($active === 'feedback360') echo 'class="active"'; ?>><a href="<?= base_url(); ?>feedback360">Feedback 360</a></li>
                        <li <?php if($active === 'services')    echo 'class="active"'; ?>><a href="<?= base_url(); ?>servicios">Servicios</a></li>
                    </ul>
                </li>
                <li class="dropdown <?php if(($active === 'my_surveys') OR ($active === 'my_reports')) echo "active"; ?>">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Mi Fib360 <span class="caret"></span></a>
                    <ul class="dropdown-menu" role="menu">
                        <li <?php if($active === 'my_surveys') echo 'class="active"'; ?>><a href="<?= base_url(); ?>c_survey/my_surveys/<?= $this->session->userdata('id'); ?>">Mis encuestas</a></li>
                        <?php if ( $this->session->userdata('has_reports') ) { ?>
                        <li <?php if($active === 'my_reports') echo 'class="active"'; ?>><a href="<?= base_url(); ?>c_report/my_reports">Mis informes</a></li>
                        <?php } ?>
                    </ul>
                </li>
                <?php if ( $this->session->userdata('system_rol') === 'Superadmin' || $this->session->userdata('system_rol') === 'Admin' ) { ?>
                <li <?php if($active === 'project') echo 'class="active"'; ?>><a href="<?= base_url(); ?>c_project">Proyectos</a>
                </li>
                <li <?php if($active === 'survey')  echo 'class="active"'; ?>><a href="<?= base_url(); ?>c_survey">Encuestas</a>
                </li>
                <li <?php if($active === 'user')    echo 'class="active"'; ?>><a href="<?= base_url(); ?>c_user">Usuarios</a>
                </li>
                <li <?php if($active === 'rol')     echo 'class="active"'; ?>><a href="<?= base_url(); ?>c_rol">Roles</a>
                </li>
                <li <?php if($active === 'email')   echo 'class="active"'; ?>><a href="<?= base_url(); ?>c_email">Emails</a>
                </li>
                <?php } ?>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <div id="loggedUser">
                    <li>
                        <a href="<?= base_url(); ?>c_profile">
                            <span class="glyphicon glyphicon-user"></span> <?= $this->session->userdata('name'); ?>
                        </a>
                        <a id="btnLogout" href="<?= base_url(); ?>c_login/logout">(Salir)</a>
                    </li>
                </div>
            </ul>
            <?php } ?>
        </div>
        <!--/.nav-collapse -->
    </div>
</nav>