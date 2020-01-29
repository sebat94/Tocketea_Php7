<main class="main_index">
  <form action="/" method="post" id="formFilter">

  <section class="filtrar_eventos_index transition_activated unselectable" id="menu_lateral_filtrar">


      <div class="filtros_categoria_ciudad">
        <article class="filtro_categoria">
          <span id="filtrar_por_categoria"><i class="fa fa-object-ungroup"></i><?php echo _translate("Cateor&iacute;as"); ?><i>&#8250;</i></span>
          <ul>
            <li><input type="checkbox" id="c_all" name="c_all" <?php if(isset($_POST['c_all'])) echo "checked='checked'"; ?>><label for="c_all">Todas las categorías</label></li>
            <?php foreach ($data['categorias'] as $categoria) : ?>
              <li><input type="checkbox" id="<?php echo 'c_' . $categoria['id']; ?>" name="<?php echo 'c_' . $categoria['id']; ?>" value="<?php echo $categoria['nombre']; ?>" <?php if(isset($_POST['c_'.$categoria['id']])) echo "checked='checked'"; ?>><label for="<?php echo 'c_' . $categoria['id']; ?>"><?php echo $categoria['nombre']; ?></label></li>
            <?php endforeach; ?>
          </ul>
        </article>
        <article class="filtro_ciudad">
          <span id="filtrar_por_ciudad"><i class="fa fa-map-marker"></i><?php echo _translate("Ciudad"); ?><i>&#8250;</i></span>
          <ul>
            <li><input type="checkbox" id="p_all" name="p_all" <?php if(isset($_POST['p_all'])) echo "checked='checked'"; ?>><label for="p_all">Todas las ciudades</label></li>
            <?php foreach ($data['provincias'] as $provincia) : ?>
              <li><input type="checkbox" id="<?php echo 'p_' . $provincia['id']; ?>" name="<?php echo 'p_' . $provincia['id']; ?>" value="<?php echo $provincia['nombre']; ?>" <?php if(isset($_POST['p_'.$provincia['id']])) echo "checked='checked'"; ?>><label for="<?php echo 'p_' . $provincia['id']; ?>"><?php echo $provincia['nombre']; ?></label></li>
            <?php endforeach; ?>
          </ul>
        </article>
      </div>

      <article class="filtro_fecha">
        <span><i class="fa fa-calendar-check-o"></i><?php echo _translate("Filtrar por fecha"); ?></span>
        <ul>
          <li><input type="radio" id="f_all" name="f_filter" value="f_all" <?php if(isset($_POST['f_filter']) && $_POST['f_filter'] === 'f_all') echo "checked='checked'"; ?>><label for="f_all"><?php echo _translate("Todas las fechas"); ?></label></li>
          <li><input type="radio" id="f_tomorrow" name="f_filter" value="f_tomorrow" <?php if(isset($_POST['f_filter']) && $_POST['f_filter'] === 'f_tomorrow') echo "checked='checked'"; ?>><label for="f_tomorrow"><?php echo _translate("Ma&ntilde;ana"); ?></label></li>
          <li><input type="radio" id="f_this_week" name="f_filter" value="f_this_week" <?php if(isset($_POST['f_filter']) && $_POST['f_filter'] === 'f_this_week') echo "checked='checked'"; ?>><label for="f_this_week"><?php echo _translate("Esta semana"); ?></label></li>
          <li><input type="radio" id="f_this_weekend" name="f_filter" value="f_this_weekend" <?php if(isset($_POST['f_filter']) && $_POST['f_filter'] === 'f_this_weekend') echo "checked='checked'"; ?>><label for="f_this_weekend"><?php echo _translate("Este fin de semana"); ?></label></li>
          <li><input type="radio" id="f_this_month" name="f_filter" value="f_this_month" <?php if(isset($_POST['f_filter']) && $_POST['f_filter'] === 'f_this_month') echo "checked='checked'"; ?>><label for="f_this_month"><?php echo _translate("Este mes"); ?></label></li>
        </ul>
      </article>

      <article class="filtro_buscar">
        <label for="buscador"><i class="fa fa-search"></i><?php echo _translate("Buscar"); ?></label>
        <div class="buscador">
          <input type="search" id="buscador" name="busqueda" placeholder="<?php echo _translate("Buscar eventos"); ?>" value="<?php if (isset($_POST['busqueda'])) echo $_POST['busqueda']; ?>">
          <i class="fa fa-rocket"></i>
          <input type="submit" value="">
        </div>
      </article>
    </section>


    <div class="bloque_contenedor clearfix">

      <section class="titulo_index">
        <h1><?php echo _translate("Explora nuestros eventos"); ?></h1>
      </section>

      <section class="eventos_index">

        <?php foreach ($data['eventos'] as $evento) : ?>
        <article class="evento_index">
          <div class="img_evento_index">
            <?php
              $miniaturaEvt = explode('/', $evento['imagen']);
              $nombreMiniaturaEvt = $miniaturaEvt[2];
              $rutaMiniaturaEvt = '/img/evento/min_'.$nombreMiniaturaEvt;
            ?>
            <img src="<?php echo $rutaMiniaturaEvt; ?>" alt="">
          </div>
          <div class="contenido_evento_index">
            <?php
              $miniaturaUsr = explode('/', $evento['u_imagen']);
              $nombreMiniaturaUsr = $miniaturaUsr[2];
              $rutaMiniaturaUsr = '/img/perfil/min_'.$nombreMiniaturaUsr;
            ?>
            <img src="<?php echo $rutaMiniaturaUsr; ?>" alt="" title="<?php echo $evento['u_nombre_completo']; ?>">
            <div class="info_evento_index">
              <div class="titulo_evento_index">
                <h2 title="<?php echo $evento['titulo']; ?>"><?php echo $evento['titulo']; ?></h2>
              </div>
              <div class="posicionar_info_evento">
                <div class="fecha_evento_index">
                  <span><?php echo $evento['fecha_celebracion']; ?></span>
                </div>
                <div class="localizacion_evento_index">
                  <span title="<?php echo $evento['direccion']; ?>"><i class="fa fa-map-marker"></i><?php echo $evento['direccion']; ?></span>
                </div>
                <div class="mas_informacion_evento">
                  <a href="/eventos/<?php echo $evento['id']; ?>"><?php echo _translate("Más información..."); ?></a>
                </div>
              </div>
              <div class="precio_evento_index">
                <span><?php echo $evento['precio_entradas']; ?>€</span>
              </div>
              <div class="comprar_entrada"><a href="/entradas/comprar/<?php echo $evento['id']; ?>"><?php echo _translate("Comprar entradas"); ?></a></div>
            </div>
            <?php if(isset($_SESSION['emailUsuario']) && $_SESSION['emailUsuario'] === $evento['FK_email']) : ?>
              <div class="editar_evento"><a href="/eventos/actualizar/<?php echo $evento['id']; ?>"><i class="fa fa-edit"></i></a></div>
            <?php endif; ?>
          </div>
        </article>
        <?php endforeach; ?>

      </section>

      <section class="paginacion_index unselectable">
        <ul>
            <?php
              if(isset($_POST['pagina']))
              {
                  // Comprobamos que la página no sea mayor que el número total de páginas, o menor que 1
                  if($_POST['pagina'] < 1 || $_POST['pagina'] > $_SESSION['numeroPaginas'])
                      $_POST['pagina'] = 1;

                  $pagina = $_POST['pagina'];
              }
              else
                  $pagina = 1;

              $numeroPaginas = $_SESSION['numeroPaginas'];
            ?>
          <!-- Establecemos cuando el boton de "ANTERIOR" estará deshabilitado -->
          <?php if($pagina == 1 || $pagina < 1) : ?>
            <li class="disabled_page"><label for="prev_page"><input type="radio" name="pagina" value="<?php echo $pagina - 1; ?>" id="prev_page" disabled>&#10092;</label></li>
          <?php else : ?>
            <li><label for="prev_page"><input type="radio" name="pagina" value="<?php echo $pagina - 1; ?>" id="prev_page">&#10092;</label></li>
          <?php endif; ?>
          <!-- Mostramos las páginas -->
          <?php
            for ($i = 1; $i <= $numeroPaginas; $i++)
            {
              if($pagina == $i)
              {
                  echo "<li class='active_page'><label for='page_$i'><input type='radio' name='pagina' value='$i' id='page_$i' checked>$i</label></li>";
              }
              else
              {
                  echo "<li><label for='page_$i'><input type='radio' name='pagina' value='$i' id='page_$i'>$i</label></li>";
              }
            }
          ?>
          <!-- Establecemos cuando el boton de "SIGUIENTE" estará deshabilitado -->
          <?php if($pagina == $numeroPaginas || $pagina > $_SESSION['numeroPaginas']) : ?>
            <li class="disabled_page"><label for="next_page"><input type="radio" name="pagina" value="<?php echo $pagina + 1; ?>" id="next_page" disabled>&#10093;</label></li>
          <?php else : ?>
            <li><label for="next_page"><input type="radio" name="pagina" value="<?php echo $pagina + 1; ?>" id="next_page">&#10093;</label></li>
          <?php endif; ?>
        </ul>
      </section>

    </div>

  </form>
</main>

<script type="text/javascript" src="/js/crud.js"></script>
