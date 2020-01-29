<main class="main_mis_entradas">
  <div class="bloque_contenedor clearfix">

    <section class="menu_tabla">
      <div class="filtrar_menu_tabla">

          <div class="num_eventos_tabla">
            <span><?php echo _translate("Mis entradas"); ?></span>
          </div>

      </div>
    </section>

    <?php if(!empty($entradas)) : ?>
    <section class="tabla">
      <div class="tabla_responsive">

        <div class="info_columnas_tabla">
          <div class="columna_tabla_entradas">
            <span><?php echo _translate("T&iacute;tulo"); ?></span>
          </div>
          <div class="columna_tabla_entradas">
            <span><?php echo _translate("Fecha"); ?></span>
          </div>
          <div class="columna_tabla_entradas">
            <span><?php echo _translate("Hora"); ?>Hora</span>
          </div>
          <div class="columna_tabla_entradas">
            <span><?php echo _translate("Entradas"); ?></span>
          </div>
          <div class="columna_tabla_entradas">
            <span><?php echo _translate("Importe"); ?></span>
          </div>
          <div class="columna_tabla_entradas">
            <span><?php echo _translate("Ver"); ?></span>
          </div>
        </div>

        <?php foreach ($entradas as $entrada) : ?>
        <article class="fila_tabla">
          <div class="columna_tabla_entradas">
              <?php
                  $miniatura = explode('/', $entrada['imagen']);
                  $nombreMiniatura = $miniatura[2];
                  $rutaMiniatura = '/img/evento/min_'.$nombreMiniatura;
              ?>
            <img src="<?php echo $rutaMiniatura; ?>" alt="">
            <div><?php echo $entrada['titulo']; ?></div>
          </div>
          <div class="columna_tabla_entradas">
            <span><?php echo $entrada['fecha_celebracion']; ?></span>
          </div>
          <div class="columna_tabla_entradas">
            <span><?php echo $entrada['hora_celebracion']; ?></span>
          </div>
          <div class="columna_tabla_entradas">
            <span><?php echo $entrada['num_entradas']; ?></span>
          </div>
          <div class="columna_tabla_entradas">
            <span><?php echo $entrada['precio_entradas'] * $entrada['num_entradas']; ?>€</span>
          </div>
          <div class="columna_tabla_entradas">
            <a href="eventos/<?php echo $entrada['PK_evento']; ?>"><i class="fa fa-eye"></i></a>
          </div>
        </article>
        <?php endforeach; ?>


      </div>
    </section>
    <?php endif; ?>

  </div>
</main>
