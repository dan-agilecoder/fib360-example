<div class="modal fade" id="modalModifyProject">
  <div class="modal-dialog">
    <div class="modal-content">
      <?php
      $attr = array('id' => 'formModifyProject'); 
      echo form_open('c_project/modify', $attr); ?>
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
  		  <input type="hidden" id="inputModifyId" name="id">
        <input type="hidden" id="inputModifyLogo" name="logo">
        <div class="row">
          <div class="form-group col-xs-12">
            <label for="inputModifyName">Nombre</label>
            <input type="text" class="form-control" id="inputModifyName" placeholder="Introduzca el nombre" name="name">
          </div>
        </div>

        <div class="row">
          <div class="form-group col-xs-12">
            <label for="inputModifyOrganization">Organización</label>
            <input type="text" class="form-control" id="inputModifyOrganization" placeholder="Introduzca la organización" name="organization">
          </div>
        </div>

        <div class="row">
          <div class="col-xs-12 input-group-row">
            <label>Fecha de inicio</label>
            <div class="input-group">
              <input type="text" class="form-control date-picker modify-project" id="inputModifyStartDate" placeholder="Introduzca la fecha de inicio (dd-mm-aaaa)" name="start_date">
              <label for="inputModifyStartDate" class="input-group-addon btn btn-default"><span class="glyphicon glyphicon-calendar"></span></label>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 input-group-row">
            <label>Fecha de finalización</label>
            <div class="input-group">
              <input type="text" class="form-control date-picker modify-project" id="inputModifyFinishDdate" placeholder="Introduzca la fecha de fin (dd-mm-aaaa)" name="finish_date">
              <label for="inputModifyFinishDdate" class="input-group-addon btn btn-default"><span class="glyphicon glyphicon-calendar"></span></label>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Cerrar</button>
        <button type="submit" class="btn btn-info">Guardar</button>
      </div>
      <?= form_close(); ?>
    </div>
  </div>
</div>