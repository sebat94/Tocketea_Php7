<main class="main_crear_evento">
  <div class="bloque_contenedor">

    <section class="form_crear_evento">
      <h1><?php echo _translate("Crear evento"); ?></h1>
      <form action="/eventos/enviar" method="post" enctype="multipart/form-data">

          <input type="hidden" id="idEvt" name="idEvt" value="<?php echo $evento['id']; ?>">

          <div class="titulo_crear_evento">
          <div>
            <label for="tituloEvt"><?php echo _translate("Título"); ?>*</label>
            <input type="text" name="tituloEvt" id="tituloEvt" value="<?php echo $evento['titulo']; ?>">
              <?php
                if(isset($_SESSION['erroresFormularioEvento']) && !empty($_SESSION['erroresFormularioEvento']['titulo']))
                {
                    echo '<sub>' . $_SESSION['erroresFormularioEvento']['titulo'] . '</sub>';
                }
              ?>
            <i class="fa fa-heart-o"></i>
          </div>
          <div>
            <label for="categoriaEvt"><?php echo _translate("Categor&iacute;a"); ?>*</label>
            <select name="categoriaEvt" id="categoriaEvt">
              <option selected="selected" disabled="disabled" hidden="hidden"></option>

              <?php foreach ($categorias as $categoria) : ?>
                <option value="<?php echo $categoria['id']; ?>" <?php echo ($categoria['id'] === $evento['FK_categoria'] ? "selected" : ""); ?>><?php echo $categoria['nombre']; ?></option>
              <?php endforeach; ?>

            </select>
              <?php
                if(isset($_SESSION['erroresFormularioEvento']) && !empty($_SESSION['erroresFormularioEvento']['FK_categoria']))
                {
                    echo '<sub>' . $_SESSION['erroresFormularioEvento']['FK_categoria'] . '</sub>';
                }
              ?>
            <i class="fa fa-sort-amount-asc"></i>
            <span class="fa fa-caret-down"></span>
          </div>
        </div>
        <div class="rango_fechas_crear_evento">
          <div>
            <label for="fechaVentaIniEvt"><?php echo _translate("Venta de entradas desde"); ?>*</label>
            <input type="date" name="fechaVentaIniEvt" id="fechaVentaIniEvt" value="<?php echo $evento['venta_fecha_inicio']; ?>">
              <?php
                if(isset($_SESSION['erroresFormularioEvento']) && !empty($_SESSION['erroresFormularioEvento']['venta_fecha_inicio']))
                {
                    echo '<sub>' . $_SESSION['erroresFormularioEvento']['venta_fecha_inicio'] . '</sub>';
                }
              ?>
            <i class="fa fa-calendar"></i>
          </div>
          <div>
            <label for="fechaVentaFinEvt"><?php echo _translate("Hasta"); ?>*</label>
            <input type="date" name="fechaVentaFinEvt" id="fechaVentaFinEvt"  value="<?php echo $evento['venta_fecha_fin']; ?>">
              <?php
                if(isset($_SESSION['erroresFormularioEvento']) && !empty($_SESSION['erroresFormularioEvento']['venta_fecha_fin']))
                {
                    echo '<sub>' . $_SESSION['erroresFormularioEvento']['venta_fecha_fin'] . '</sub>';
                }
              ?>
            <i class="fa fa-calendar"></i>
          </div>
        </div>
        <div class="fecha_hora_crear_evento">
          <div>
            <label for="fechaCelebracionEvt"><?php echo _translate("Fecha celebraci&oacute;n"); ?>*</label>
            <input type="date" name="fechaCelebracionEvt" id="fechaCelebracionEvt" value="<?php echo $evento['fecha_celebracion']; ?>">
              <?php
                if(isset($_SESSION['erroresFormularioEvento']) && !empty($_SESSION['erroresFormularioEvento']['fecha_celebracion']))
                {
                    echo '<sub>' . $_SESSION['erroresFormularioEvento']['fecha_celebracion'] . '</sub>';
                }
              ?>
            <i class="fa fa-calendar"></i>
          </div>
          <div>
            <label for="horaCelebracionEvt"><?php echo _translate("Hora celebraci&oacute;n"); ?>*</label>
            <input type="time" name="horaCelebracionEvt" id="horaCelebracionEvt" value="<?php echo $evento['hora_celebracion']; ?>">
              <?php
                if(isset($_SESSION['erroresFormularioEvento']) && !empty($_SESSION['erroresFormularioEvento']['hora_celebracion']))
                {
                    echo '<sub>' . $_SESSION['erroresFormularioEvento']['hora_celebracion'] . '</sub>';
                }
              ?>
            <i class="fa fa-clock-o"></i>
          </div>
        </div>
        <div class="localizacion_crear_evento">
          <div>
            <label for="localizacionEvt"><?php echo _translate("Provincia"); ?>*</label>
            <select name="localizacionEvt" id="localizacionEvt">
              <option selected="selected" disabled="disabled" hidden="hidden"></option>

              <?php foreach ($provincias as $provincia) : ?>
                <option value="<?php echo $provincia['id']; ?>" <?php echo ($provincia['id'] === $evento['FK_provincia'] ? "selected" : ""); ?>><?php echo $provincia['nombre']; ?></option>
              <?php endforeach; ?>

            </select>
              <?php
              if(isset($_SESSION['erroresFormularioEvento']) && !empty($_SESSION['erroresFormularioEvento']['FK_provincia']))
                {
                    echo '<sub>' . $_SESSION['erroresFormularioEvento']['FK_provincia'] . '</sub>';
                }
              ?>
            <i class="fa fa-location-arrow"></i>
            <span class="fa fa-caret-down"></span>
          </div>
          <div>
            <label for="direccionEvt"><?php echo _translate("Direcci&oacute;n"); ?>*</label>
            <input type="text" name="direccionEvt" id="direccionEvt" value="<?php echo $evento['direccion']; ?>">
              <?php
                if(isset($_SESSION['erroresFormularioEvento']) && !empty($_SESSION['erroresFormularioEvento']['direccion']))
                {
                    echo '<sub>' . $_SESSION['erroresFormularioEvento']['direccion'] . '</sub>';
                }
              ?>
            <i class="fa fa-map-marker"></i>
          </div>
        </div>
        <div class="num_entradas_crear_evento">
          <div>
            <label for="totalEntradasEvt"><?php echo _translate("Total de entradas"); ?>*</label>
            <input type="number" min="0" name="totalEntradasEvt" id="totalEntradasEvt" value="<?php echo $evento['total_entradas']; ?>">
              <?php
                if(isset($_SESSION['erroresFormularioEvento']) && !empty($_SESSION['erroresFormularioEvento']['total_entradas']))
                {
                    echo '<sub>' . $_SESSION['erroresFormularioEvento']['total_entradas'] . '</sub>';
                }
              ?>
            <i class="fa fa-ticket"></i>
          </div>
          <div>
            <label for="precioEntradasEvt"><?php echo _translate("Precio entradas"); ?>*</label>
            <input type="number" min="0" name="precioEntradasEvt" id="precioEntradasEvt" value="<?php echo $evento['precio_entradas']; ?>">
              <?php
                if(isset($_SESSION['erroresFormularioEvento']) && !empty($_SESSION['erroresFormularioEvento']['precio_entradas']))
                {
                    echo '<sub>' . $_SESSION['erroresFormularioEvento']['precio_entradas'] . '</sub>';
                }
              ?>
            <i class="fa fa-money"></i>
          </div>
        </div>
        <div class="imagen_enlace_crear_evento">
          <div>
            <label for="imagenEvt"><?php echo _translate("Imagen"); ?>*</label>
            <input type="file" name="imagenEvt" id="imagenEvt">
              <?php
                if(isset($_SESSION['erroresFormularioEvento']) && !empty($_SESSION['erroresFormularioEvento']['imagen']))
                {
                    echo '<sub>' . $_SESSION['erroresFormularioEvento']['imagen'] . '</sub>';
                }
              ?>
            <i class="fa fa-file-image-o"></i>
          </div>
          <div>
            <label for="enlaceExternoEvt"><?php echo _translate("Enlace externo"); ?></label>
            <input type="text" name="enlaceExternoEvt" id="enlaceExternoEvt" value="<?php echo $evento['enlace_externo']; ?>">
              <?php
                if(isset($_SESSION['erroresFormularioEvento']) && !empty($_SESSION['erroresFormularioEvento']['enlace_externo']))
                {
                    echo '<sub>' . $_SESSION['erroresFormularioEvento']['enlace_externo'] . '</sub>';
                }
              ?>
            <i class="fa fa-link"></i>
          </div>
        </div>
        <div class="descripcion_crear_evento">
          <label for="DescripcionEvt"><?php echo _translate("Descripción evento"); ?>*</label>
          <textarea name="DescripcionEvt" id="DescripcionEvt"><?php echo $evento['descripcion']; ?></textarea>
            <?php
              if(isset($_SESSION['erroresFormularioEvento']) && !empty($_SESSION['erroresFormularioEvento']['descripcion']))
              {
                  echo '<sub>' . $_SESSION['erroresFormularioEvento']['descripcion'] . '</sub>';
              }
            ?>
          <i class="fa fa-align-left"></i>
        </div>
        <div class="btn_crear_evento">
          <input type="submit" value="<?php echo ((isset($evento['id']) && !empty($evento['id'])) ? _translate("Actualizar evento") : _translate("Crear evento")); ?>">
        </div>
      </form>
    </section>

  </div>
</main>
