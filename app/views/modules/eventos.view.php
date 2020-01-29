<main class="main_mis_eventos">
  <div class="bloque_contenedor clearfix">
    <section class="menu_tabla">

      <div class="crear_nuevo_evento">
        <a href="/eventos/formulario-evento"><i class="fa fa-plus-square-o"></i> <?php echo _translate("Crear evento"); ?></a>
      </div>

      <div class="filtrar_menu_tabla">

        <div class="num_eventos_tabla">
          <span><?php echo count($eventos); ?> <?php echo _translate("Eventos creados"); ?></span>
        </div>

        <form action="eventos" method="post" id="filtrosEventos">
          <div class="filtrar_eventos_por_categoria">
            <div>
              <span><?php echo _translate("Filtrar por"); ?>: </span>
              <select id="filtroCategoria" name="categoriaMisEventos">
                <option value="0" <?php if(isset($_POST['categoriaMisEventos']) && $_POST['categoriaMisEventos'] == 0) echo "selected='selected'"; ?>><?php echo _translate("Todas las categor&iacute;as"); ?></option>
                  <?php foreach ($categorias as $categoria) : ?>
                    <option value="<?php echo $categoria['id']; ?>" <?php if(isset($_POST['categoriaMisEventos']) && $_POST['categoriaMisEventos'] == $categoria['id']) echo "selected='selected'"; ?>><?php echo $categoria['nombre']; ?></option>
                  <?php endforeach; ?>
              </select>
              <i class="fa fa-caret-down"></i>
            </div>
          </div>

          <div class="filtrar_eventos_futuros_pasados">
            <div>
              <input type="radio" name="fechaMisEventos" value="futuros" id="fechaMisEventosFuturos" <?php if(isset($_POST['fechaMisEventos']) && $_POST['fechaMisEventos'] === 'futuros') echo "checked='checked'"; ?>>
              <label for="fechaMisEventosFuturos"><?php echo _translate("Eventos futuros"); ?></label>
              <span>/</span>
              <input type="radio" name="fechaMisEventos" value="pasados" id="fechaMisEventosPasados" <?php if(isset($_POST['fechaMisEventos']) && $_POST['fechaMisEventos'] === 'pasados') echo "checked='checked'"; ?>>
              <label for="fechaMisEventosPasados"><?php echo _translate("Eventos Pasados"); ?></label>
            </div>
          </div>
        </form>

        <div class="filtrar_eventos_por_nombre">
          <form action="eventos" method="post">
            <input type="search" name="buscarMisEventos" placeholder="<?php echo _translate("Buscar eventos"); ?>" value="<?php if(isset($_POST['buscarMisEventos']) && $_POST['buscarMisEventos'] !== '') echo $_POST['buscarMisEventos']; ?>">
            <i class="fa fa-rocket"></i>
            <input type="submit" name="" value="">
          </form>
        </div>

      </div>
    </section>

    <?php if(!empty($eventos)) : ?>
    <section class="tabla">
      <div class="tabla_responsive">

        <div class="info_columnas_tabla">
          <div class="columna_tabla_eventos">
            <span><?php echo _translate("T&iacute;tulo"); ?></span>
          </div>
          <div class="columna_tabla_eventos">
            <span><?php echo _translate("Precio"); ?></span>
          </div>
          <div class="columna_tabla_eventos">
            <span><?php echo _translate("Ventas"); ?></span>
          </div>
          <div class="columna_tabla_eventos">
            <span><?php echo _translate("Importe"); ?></span>
          </div>
          <div class="columna_tabla_eventos">
            <span><?php echo _translate("Ver"); ?></span>
          </div>
          <div class="columna_tabla_eventos">
            <span><?php echo _translate("Editar"); ?></span>
          </div>
          <div class="columna_tabla_eventos">
            <span><?php echo _translate("Borrar"); ?></span>
          </div>
        </div>

          <?php foreach ($eventos as $evento) : ?>
            <article class="fila_tabla">
              <div class="columna_tabla_eventos">
                <?php
                    $miniatura = explode('/', $evento->getImagen());
                    $nombreMiniatura = $miniatura[2];
                    $rutaMiniatura = '/img/evento/min_'.$nombreMiniatura;
                ?>
                <img src="<?php echo $rutaMiniatura ?>" alt="">
                <div><?php echo $evento->getTitulo(); ?></div>
              </div>
              <div class="columna_tabla_eventos">
                <span><?php echo (!is_null($evento->getPrecioEntradas()) ? $evento->getPrecioEntradas() : 'consultar') ?>€</span>
              </div>
              <div class="columna_tabla_eventos">
                <span><?php echo ($evento->getTotalEntradas() - $evento->getEntradasRestantes()) ?></span>
              </div>
              <div class="columna_tabla_eventos">
                <span><?php echo ($evento->getTotalEntradas() - $evento->getEntradasRestantes()) * $evento->getPrecioEntradas(); ?></span>
              </div>
              <div class="columna_tabla_eventos">
                <a href="/eventos/<?php echo $evento->getId(); ?>"><i class="fa fa-eye"></i></a>
              </div>
              <div class="columna_tabla_eventos">
                <a href="/eventos/actualizar/<?php echo $evento->getId(); ?>"><i class="fa fa-pencil-square-o"></i></a>
              </div>
              <div class="columna_tabla_eventos">
                <a href="/eventos/eliminar/<?php echo $evento->getId(); ?>" class="borrar_elemento"><i class="fa fa-trash-o"></i></a>
              </div>
            </article>
          <?php endforeach; ?>


      </div>
    </section>
    <?php endif; ?>

  </div>
</main>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/6.11.5/sweetalert2.all.js"></script>
<script type="text/javascript" src="/js/crud.js"></script>
<script type="text/javascript" src="/js/mis_eventos.js"></script>
