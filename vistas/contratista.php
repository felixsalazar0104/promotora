<?php
//Activamos el almacenamiento en el buffer
ob_start();
session_start();

if (!isset($_SESSION["nombre"]))
{
  header("Location: login.html");
}
else
{
require 'header.php';

if ($_SESSION['almacen']==1)
{
?>
<!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">        
        <!-- Main content -->
        <section class="content">
            <div class="row">
              <div class="col-md-12">
                  <div class="box">
                    <div class="box-header with-border">
                          <h1 class="box-title">Contratista <button class="btn btn-success" id="btnagregar" onclick="mostrarform(true)"><i class="fa fa-plus-circle"></i> Agregar</button> <a href="../reportes/rptcontratistas.php" target="_blank"><button class="btn btn-info"><i class="fa fa-clipboard"></i> Reporte</button></a></h1>
                        <div class="box-tools pull-right">
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <!-- centro -->
                    <div class="panel-body table-responsive" id="listadoregistros">
                        <table id="tbllistado" class="table table-striped table-bordered table-condensed table-hover">
                          <thead>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Nit</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                          </thead>
                          <tbody>                            
                          </tbody>
                          <tfoot>
                            <th>Imagen</th>
                            <th>Nombre</th>
                            <th>Nit</th>
                            <th>Estado</th>
                            <th>Opciones</th>
                          </tfoot>
                        </table>
                    </div>
                    <div class="panel-body" style="height: 600px;" id="formularioregistros">
                        <form name="formulario" id="formulario" method="POST">

                        <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Imagen:</label>
                            <input type="file" class="form-control" name="imagen" id="imagen" accept="image/x-png,image/gif,image/jpeg">
                            <input type="hidden" name="imagenactual" id="imagenactual">
                            <img src="" width="150px" height="120px" id="imagenmuestra">
                          </div>

                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Nombre:</label>
                            <input type="hidden" name="idcontratista" id="idcontratista">
                            <input type="text" class="form-control" name="nombre" id="nombre" maxlength="50" placeholder="Nombre" required>
                          </div>
                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Nit:</label>
                            <input type="text" class="form-control" name="nit" id="nit" maxlength="256" placeholder="Nit">
                          </div>

                          <div class="form-group col-lg-6 col-md-6 col-sm-6 col-xs-12">
                            <label>Contracto:</label>
                            <input type="hidden" name="archivo" id="archivo">
                            <a id="archivo" hfre="#">
                            <img src="">
                            <input type="file" class="form-control" name="imagen" id="imagen" accept="image/x-png,image/gif,image/jpeg,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                          
                            </a>
                          </div>
                          <div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <label>Fecha Inicio:</label>
                            <input type="date" class="form-control" name="fecha_aprobado" id="fecha_aprobado" maxlength="50" placeholder="fecha" required>
                          </div>
                          <div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <label>Fecha Fin:</label>
                            <input type="date" class="form-control" name="fecha_aprobado" id="fecha_aprobado" maxlength="50" placeholder="fecha" required>
                          </div>

                          <div id="MyI0" class="form-group col-lg-12 col-md-6 col-sm-6 col-xs-12">
                           <h2 class="text-primary">ZONA</h2>
                            <hr style="height:2px;border-width:0;color:gray;background-color:gray">
                          </div>
                          <div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <label>Regional:</label>
                            <select class="form-control selectpicker" name="regional" id="regional">
                                <option value="CENTRO">CENTRO</option>
                                <option value="OCCIDENTE">OCCIDENTE</option>
                                <option value="COSTA">COSTA</option>
                                <option value="NOROCCIDENTE">NOROCCIDENTE</option>
                                <option value="SUROCCIDENTE">SUROCCIDENTE</option>
                            </select>
                           
                          </div>
                          

                          <div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <label>Departamtento 1:</label>
                            <select name="departanemnto1" id="departanemnto1" class="form-control selectpicker" data-live-search="true"></select>
                          </div>
                          <div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <label>Departamtento 2:</label>
                            <select name="departanemnto2" id="departanemnto2" class="form-control selectpicker" data-live-search="true"></select>
                          </div>
                          <div class="form-group col-lg-3 col-md-6 col-sm-6 col-xs-12">
                            <label>Departamtento 3:</label>
                            <select name="departanemnto3" id="departanemnto3" class="form-control selectpicker" data-live-search="true"></select>
                          </div>
                         
                          <div class="form-group col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <button class="btn btn-primary" type="submit" id="btnGuardar"><i class="fa fa-save"></i> Guardar</button>

                            <button class="btn btn-danger" onclick="cancelarform()" type="button"><i class="fa fa-arrow-circle-left"></i> Cancelar</button>
                          </div>
                        </form>
                    </div>

                    
                    <!--Fin centro -->
                  </div><!-- /.box -->
              </div><!-- /.col -->
          </div><!-- /.row -->
      </section><!-- /.content -->

    </div><!-- /.content-wrapper -->
  <!--Fin-Contenido-->
<?php
}
else
{
  require 'noacceso.php';
}

require 'footer.php';
?>
<script type="text/javascript" src="scripts/contratista.js"></script>
<?php 
}
ob_end_flush();
?>


