    <div class="container">

      <h1 class="h1-standard">Proyectos</h1>
      <hr class="underlined">

      <div class="row" id="alertStatus">
        <div class="col-xs-12 alert alert-success" role="alert"></div>
      </div>

	<!-- FORMULARIO PARA NUEVO PROYECTO -->
      <?php
      $attr = array('id' => 'new-project-form', 'class' => 'dropzone');
      echo form_open_multipart('c_project/create', $attr); ?>
      <div class="row">
        <div class="col-sm-4">
        <?php if ( $this->session->userdata('system_rol') === 'Superadmin' ){ ?>
          <button class="btn btn-primary btn-block" id="btnShowNewProject" type="button" data-toggle="collapse" data-target="#panelNewProject" aria-expanded="false" aria-controls="panelNewProject">
            <span class="glyphicon glyphicon-plus"></span> Nuevo Proyecto
          </button>
        <?php
        }
        else if ( $this->session->userdata('system_rol') === 'Admin') { ?>
          <button class="btn btn-primary btn-block project-disabled" id="btnNoNewProject" type="button"  data-container="body" data-toggle="popover" data-placement="bottom" data-content="Contacte con Fib360 para crear nuevos Proyectos.">
            <span class="glyphicon glyphicon-plus"></span> Nuevo Proyecto
          </button>
        <?php
        } ?>
        </div>
      </div>
      <div class="collapse panel panel-default" id="panelNewProject">
        <div class="panel-body">
        <div class="col-sm-5">
          <div class="row">
            <div class="form-group col-xs-12">
              <label for="inputName">Nombre</label>
              <input type="text" class="form-control" id="inputName" placeholder="Introduzca el nombre" name="name">
            </div>
          </div>

          <div class="row">
            <div class="form-group col-xs-12">
              <label for="inputFinishDdate">Organización</label>
              <input type="text" class="form-control" id="inputOrganization" placeholder="Introduzca la organización" name="organization">
            </div>
          </div>

          <div class="row">
            <div class="col-xs-12 input-group-row">
              <label>Fecha de inicio</label>
              <div class="input-group">
                <input type="text" class="form-control date-picker" id="inputStartDate" placeholder="Introduzca la fecha de inicio (dd-mm-aaaa)" name="start_date">
                <label for="inputStartDate" class="input-group-addon btn btn-default"><span class="glyphicon glyphicon-calendar"></span></label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-xs-12 input-group-row">
              <label>Fecha de finalización</label>
              <div class="input-group">
                <input type="text" class="form-control date-picker" id="inputFinishDdate" placeholder="Introduzca la fecha de fin (dd-mm-aaaa)" name="finish_date">
                <label for="inputFinishDdate" class="input-group-addon btn btn-default"><span class="glyphicon glyphicon-calendar"></span></label>
              </div>
            </div>
          </div>
        </div>
        <div class="col-sm-7">
            <label>Logotipo:</label>
            <div class="dropzone-previews" id="projectLogoPreview">
              <div id="preview-template" style="display: none;">
                <div class="dz-preview dz-file-preview">
                  <div class="dz-details row">
                    <div class="col-sm-5">
                      <img class="img-thumbnail img-responsive logo-thumbnail" data-dz-thumbnail />
                    </div>
                    <div class="col-sm-7">
                      <div class="dz-filename"><strong>Nombre: </strong><span data-dz-name></span></div>
                      <div class="dz-size"><strong>Tamaño: </strong><span data-dz-size></span></div>
                      <div class="dz-progress progress" id="total-progress">
                        <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuemin="0" aria-valuemax="100" style="width: 0%;" data-dz-uploadprogress></div>
                      </div>
                    </div>
                  </div>
                  <div class="dz-success-mark" style="display:none"><span>✔</span></div>
                  <div class="dz-error-mark" style="display:none"><span>✘</span></div>
                  <div class="dz-error-message text-danger">
                    <span data-dz-errormessage></span>
                  </div>
                  <div class="col-sm-5">
                    <button class="btn btn-default btn-sm btn-block btn-remove-thumbnail" data-dz-remove><span class="glyphicon glyphicon-trash"></span> Eliminar</button>
                  </div>
                </div>
              </div>
              <div class="dz-message text-center text-muted"><div><span class="glyphicon glyphicon-hand-up"></span> Haz click para examinar o arrastra aquí el logotipo del Proyecto</div></div>
            </div>
        </div>
        <div class="col-sm-12">
          <div class="col-sm-2">
            <button type="submit" class="btn btn-info btn-sm btn-block" id="submitbutton"><span class="glyphicon glyphicon-share-alt"></span> Enviar</button>
          </div>
          <div class="col-sm-10">
            <h5 class="pull-right"><small><span class="glyphicon glyphicon-info-sign"></span> Se recomienda que la imágen no exceda de 420 x 200 pixeles</small></h5>
          </div>
        </div>
      </div>
      </div>

      <?php echo form_close(); ?>

	<!-- TABLA DE PROYECTOS -->
      <hr>
      <caption><h3>Proyectos existentes</h3></caption>
      <div id="no-more-tables">
      <table class="table table-striped table-condensed cf" id="projectsTable">
        <thead class="cf">
          <tr>
            <th>Id</th>
            <th></th>
            <th>Nombre</th>
            <th>Organización</th>
            <th>Logotipo</th>
            <th class="text-center">Completada</th>
            <th class="text-center">Fecha inicio</th>
            <th class="text-center">Fecha fin</th>
            <th class="text-center">Procesos</th>
          </tr>
        </thead>
        <tbody>
        <?php
          foreach($existing_projects as $project){ ?>
          <tr>
            <td data-title="Id"><span class="badge badge-info"><?= $project->id; ?></span></td>
            <td data-title="Acciones">
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-default btn-sm btn-modify-project" data-id="<?= $project->id; ?>" data-toggle="tooltip" data-placement="top" title="Modificar Proyecto">
                  <span class="glyphicon glyphicon-pencil"></span>
                </button>
                <button type="button" class="btn btn-default btn-sm btn-delete-project" data-id="<?= $project->id; ?>" data-name="<?= $project->name; ?>" data-toggle="tooltip" data-placement="top" title="Eliminar Proyecto">
                  <span class="glyphicon glyphicon-trash"></span>
                </button>
              </div>
            </td>
            <td data-title="Nombre"><?= $project->name; ?></td>
            <td data-title="Organización"><?= $project->organization; ?></td>
            <td data-title="Logo">
            <?php
              if ( $project->logo ) { ?>
              <img alt="<?= $project->name; ?> Logo" src="<?= LOGO.$project->id.'/'.$project->logo; ?>" height="48">
            <?php
              }
              else { ?>
                <span class="glyphicon glyphicon-remove text-danger"></span>
            <?php
              } ?>
            </td>
            <td data-title="Progreso">
              <?php
              $num_filled_surveys = $projects_percent[$project->id]['num_filled_surveys'];
              $num_surveys = $projects_percent[$project->id]['num_surveys'];
              if ( ! empty($num_surveys) ) { ?>
              <div class="project_progress" id="project-progress-<?= $project->id; ?>" data-percent="<?= ( ! empty($num_surveys) ) ? round($num_filled_surveys/$num_surveys, 2) : 0; ?>">
                <span class="progress_percent" data-num-surveys="<?= $num_surveys; ?>" data-num-filled-surveys="<?= $num_filled_surveys; ?>">
                  <?= round(($num_filled_surveys/$num_surveys)*100); ?> %
                </span>
              </div>
              <?php }
              else { ?>
              <div class="text-center">
                <span class="glyphicon glyphicon-remove text-danger"></span>
              </div>
              <?php } ?>
            </td>
            <td data-title="Fecha inicio"><?php if ( ! empty($project->start_date) ) { echo '<div class="text-center">'.date('d-m-Y', $project->start_date).'</div>'; } else { echo '<div class="text-center"><span class="glyphicon glyphicon-remove text-danger"></span></div>'; } ?></td>
            <td data-title="Fecha fin"><?php if ( ! empty($project->finish_date) ) { echo '<div class="text-center">'.date('d-m-Y', $project->finish_date).'</div>'; } else { echo '<div class="text-center"><span class="glyphicon glyphicon-remove text-danger"></span></div>'; } ?></td>
            <td class="text-center" data-title="Procesos">
              <div class="btn-group" role="group">
                <button type="button" class="btn btn-default btn-sm btnCreateProcess" data-toggle="modal" data-target="#modalNewProcess" data-toggle="tooltip" data-placement="top" title="Nuevo proceso">
                  <span class="glyphicon glyphicon-plus"></span>
                </button>
                <button type="button" class="btn btn-default btn-sm btnShowProcess" data-id-project="<?= $project->id; ?>" data-project-name="<?= $project->name; ?>" data-toggle="tooltip" data-placement="top" title="Ver proceso">
                  <span class="glyphicon glyphicon-eye-open"></span>
                </button>
              </div>
            </td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      </div>
    </div><!-- container -->


	<!-- INCLUSIÓN DE LIBRERIAS -->

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="<?php echo(JS.'jquery-1.11.1.min.js'); ?>"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?php echo(JS.'bootstrap.min.js'); ?>"></script>
	  <!-- Include the JQuery code -->
    <script src="<?php echo(JS.'jquery-ui.min.js'); ?>"></script>
    <!-- Include Progressbar jQuery Plugin -->
    <script src="<?php echo(JS.'progressbar.js'); ?>"></script>
	  <!-- Include the JS code related to Project -->
    <script src="<?php echo(JS.'project.js'); ?>"></script>
    <!-- Include the JS code to drag and drop images -->
    <script src="<?php echo(JS.'dropzone.js'); ?>"></script>
    <!--Load the AJAX API-->
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script>
        // Load the Visualization API and the corechart and table packages
        google.load('visualization', '1.0', {
            'packages': ['corechart']
        });
    </script>
  </body>
</html>