<main class="main_perfil">
  <div class="bloque_contenedor clearfix">

      <section class="section_perfil_1">
        <div class="img_perfil">
          <?php
            $miniatura = explode('/', $dataUserLoged->getImagen());
            $nombreMiniatura = $miniatura[2];
            $rutaMiniatura = '/img/perfil/min_'.$nombreMiniatura;
          ?>
          <img src="<?php echo !is_null($dataUserLoged->getImagen()) ? $rutaMiniatura : 'img/perfil/default.png'  ?>" alt="">
        </div>
        <div class="info_section_perfil_1">
          <span><?php echo $dataUserLoged->getNombreCompleto(); ?> (<?php echo $dataUserLoged->getFKProvincia(); ?>)</span>
          <span><?php echo $dataUserLoged->getEmail(); ?></span>
        </div>
        <span><?php echo _translate("Idioma"); ?>: <?php echo $dataUserLoged->getIdioma(); ?></span>
      </section>

      <section class="section_perfil_2">
        <h1><?php echo _translate("Actualizar datos de usuario"); ?></h1>
        <form action="/perfil" method="post" enctype="multipart/form-data">
          <div class="cambiar_img_perfil">
            <span><?php echo _translate("Actualizar im&aacute;gen"); ?></span>
            <input type="file" name="cambiarImgPerfil">
              <?php echo (isset($arrayErroresFormPerfil) && !empty($arrayErroresFormPerfil['imagen'])) ? '<sub>' . $arrayErroresFormPerfil['imagen'] . '</sub>' : '' ?>
          </div>
          <div class="cambiar_contraseña_perfil">
            <span><?php echo _translate("Actualizar contrase&ntilde;a"); ?></span>
            <input type="password" name="cambiarPasswordPerfil1" placeholder="<?php echo _translate("Contrase&ntilde;a"); ?>">
            <input type="password" name="cambiarPasswordPerfil2" placeholder="<?php echo _translate("Repetir contrase&ntilde;a"); ?>">
              <?php echo (isset($arrayErroresFormPerfil) && !empty($arrayErroresFormPerfil['password'])) ? '<sub>' . $arrayErroresFormPerfil['password'] . '</sub>' : '' ?>
          </div>
          <div class="cambiar_idioma_perfil">
            <select name="cambiarIdiomaPerfil">
              <option selected="selected" disabled="disabled" hidden="hidden"><?php echo _translate("Idioma"); ?></option>
              <option value="es_ES">Español</option>
              <option value="en_GB">Inglés</option>
            </select>
            <i class="fa fa-caret-down"></i>
              <?php echo (isset($arrayErroresFormPerfil) && !empty($arrayErroresFormPerfil['idioma'])) ? '<sub>' . $arrayErroresFormPerfil['idioma'] . '</sub>' : '' ?>
          </div>
          <div class="btn_guardar_cambios_perfil">
            <input type="submit" name="" value="<?php echo _translate("Guardar cambios"); ?>">
          </div>
        </form>

          <?php echo (isset($arrayErroresFormPerfil) && !empty($arrayErroresFormPerfil['alerta'])) ? '<sub class="form_perfil_error">' . $arrayErroresFormPerfil['alerta'] . '</sub>' : '' ?>

      </section>

  </div>
</main>
