<main class="main_mis_eventos">
  <div class="bloque_contenedor clearfix">

    <section class="menu_tabla">
      <div class="filtrar_menu_tabla">
        <form action="/usuarios" method="post" id="formFiltrarGestionUsr">

          <div class="num_eventos_tabla">
            <span><?php echo _translate("Gestión de usuarios"); ?></span>
          </div>

          <div class="filtrar_eventos_por_categoria">
            <div>
              <span><?php echo _translate("Filtrar por"); ?>: </span>
              <select name="categoriaMisEventos" id="categoriaMisEventos">
                <option><?php echo _translate("Todos los usuarios"); ?></option>
                <option value="ROL_COMPRADOR" <?php if(isset($_POST['categoriaMisEventos']) && $_POST['categoriaMisEventos'] === 'ROL_COMPRADOR') echo "selected='selected'"; ?>><?php echo _translate("Comprador"); ?></option>
                <option value="ROL_GESTOR" <?php if(isset($_POST['categoriaMisEventos']) && $_POST['categoriaMisEventos'] === 'ROL_GESTOR') echo "selected='selected'"; ?>><?php echo _translate("Gestor"); ?></option>
                <option value="ROL_ADMINISTRADOR" <?php if(isset($_POST['categoriaMisEventos']) && $_POST['categoriaMisEventos'] === 'ROL_ADMINISTRADOR') echo "selected='selected'"; ?>><?php echo _translate("Administrador"); ?></option>
              </select>
              <i class="fa fa-caret-down"></i>
            </div>
          </div>

        </form>
      </div>
    </section>

    <?php if(!empty($usuarios)) : ?>
    <section class="tabla">
      <div class="tabla_responsive">

        <div class="info_columnas_tabla">
          <div class="columna_tabla_gestion_usuarios">
            <span>Email</span>
          </div>
          <div class="columna_tabla_gestion_usuarios">
            <span><?php echo _translate("Permisos"); ?></span>
          </div>
          <div class="columna_tabla_gestion_usuarios">
            <span><?php echo _translate("Guardar"); ?></span>
          </div>
          <div class="columna_tabla_gestion_usuarios">
            <span><?php echo _translate("Borrar"); ?></span>
          </div>
        </div>

        <?php foreach ($usuarios as $usuario) : ?>
          <article class="fila_tabla">
            <form action="/usuarios/actualizar" method="post">
              <div class="columna_tabla_gestion_usuarios">
                <?php
                  $miniatura = explode('/', $usuario->getImagen());
                  $nombreMiniatura = $miniatura[2];
                  $rutaMiniatura = '/img/perfil/min_'.$nombreMiniatura;
                ?>
                <img src="<?php echo $rutaMiniatura; ?>" alt="">
                <div><?php echo $usuario->getEmail(); ?></div>
              </div>
              <div class="columna_tabla_gestion_usuarios">
                <select name="rolUsuario">
                  <option value="ROL_COMPRADOR" <?php if($usuario->getRol() === 'ROL_COMPRADOR') echo "selected='selected'"; ?>><?php echo _translate("Comprador"); ?></option>
                  <option value="ROL_GESTOR" <?php if($usuario->getRol() === 'ROL_GESTOR') echo "selected='selected'"; ?>><?php echo _translate("Gestor"); ?></option>
                  <option value="ROL_ADMINISTRADOR" <?php if($usuario->getRol() === 'ROL_ADMINISTRADOR') echo "selected='selected'"; ?>><?php echo _translate("Administrador"); ?></option>
                </select>
                <i class="fa fa-caret-down"></i>
              </div>
              <div class="columna_tabla_gestion_usuarios">
                <div class="btn_submit_user">
                  <i class="fa fa-floppy-o"></i>
                  <input type="submit" value="">
                </div>
              </div>
              <div class="columna_tabla_gestion_usuarios">
                <a href="/usuarios/eliminar/<?php echo $usuario->getEmail(); ?>" class="borrar_elemento"><i class="fa fa-trash-o"></i></a>
              </div>

              <input type="hidden" id="usrEmail" name="usrEmail" value="<?php echo $usuario->getEmail(); ?>">
            </form>
          </article>
        <?php endforeach; ?>


      </div>
    </section>
    <?php endif; ?>

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.5/sweetalert2.all.js"></script>
    <script type="text/javascript" src="/js/crud.js"></script>
    <script type="text/javascript" src="/js/gestion_usuarios.js"></script>

  </div>
</main>
