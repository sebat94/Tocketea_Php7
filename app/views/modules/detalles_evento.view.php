<main class="main_detalles_evento">
  <section class="detalles_evento_izq">
    <div class="img_detalles_evento">
      <img src="<?php echo '/'.$evento->getImagen(); ?>" alt="">
      <div class="enlace_ofocial_detalles_evento2">
        <a href="<?php echo ($evento->getEnlaceExterno() != null) ? $evento->getEnlaceExterno() : '#'; ?>" title="Web oficial">
          <i class=" fa fa-external-link"></i>
        </a>
      </div>
    </div>
    <!-- Información relativa al usuario gestor que ha creado el evento en esta sección -->
    <div class="entradas_detalles_evento">
      <a href="<?php echo (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '/') ?>"><i class="fa fa-angle-left"></i></a> <!-- Nos devolverá al index donde le dimos a mas información -->
      <span><?php echo $evento->getEntradasRestantes(); ?></span><span>/</span><span><?php echo $evento->getTotalEntradas(); ?></span><sub><?php echo _translate("Entradas restantes"); ?></sub>
    </div>

    <div class="fecha_inicio_fin_detalles_evento">
      <span><?php echo _translate("&#191;Cuándo puedes comprar tus entradas&#63;"); ?></span>
      <span><?php echo _translate("Desde el"); ?> <?php echo $evento->getVentaFechaInicio(); ?> <br><?php echo _translate("Hasta el"); ?> <?php echo $evento->getVentaFechaFin(); ?></span>
    </div>

    <div class="comprar_entradas_desde_detalles_evento">
      <a href="/entradas/comprar/<?php echo $evento->getId(); ?>"><?php echo _translate("Comprar entradas"); ?></a>
    </div>

    <div class="info_gestor_detalles_evento">
      <div class="ir_a_eventos_gestor_detalles_evento">
        <a href="#">
          <img src="/<?php echo $evento->{'u_imagen'}; ?>" alt="" title="<?php echo 'Evento de ' . $evento->{'u_nombre_completo'} ?>">
        </a>
      </div>
      <a href="/mensajes/responder/<?php echo $evento->getFKEmail(); ?>">
        <i class="fa fa-comment-o" title="Contactar con <?php echo $evento->{'u_nombre_completo'} ?>"></i>
      </a>
    </div>

    <div class="enlace_ofocial_detalles_evento">
      <a href="<?php echo ($evento->getEnlaceExterno() != null) ? $evento->getEnlaceExterno() : '#'; ?>" title="Web oficial">
        <i class=" fa fa-external-link"></i>
      </a>
    </div>
  </section>



  <section class="detalles_evento_der">
    <!-- Información relativa al evento en esta sección -->
    <article class="info_detalles_evento">
      <div class="contenido_info_detalles_evento">

        <div class="titulo_detalles_evento">
          <h1 title="<?php echo $evento->getTitulo(); ?>"><?php echo $evento->getTitulo(); ?></h1>
        </div>

        <div class="localizacion_detalles_evento">
          <span title="<?php echo $evento->getDireccion(); ?>"><?php echo $evento->getDireccion(); ?></span>
        </div>

        <div class="descripcion_detalle_evento">
          <p><?php echo $evento->getDescripcion(); ?></p>
        </div>

        <div class="fecha_celebracion_detalles_evento">
          <span><?php echo $evento->getFechaCelebracion(); ?> - <?php echo substr($evento->getHoraCelebracion(), 0,5 ); ?>h</span>
        </div>

        <div class="precio_detalles_evento">
          <span><?php echo $evento->getPrecioEntradas(); ?>€</span>
        </div>

      </div>
    </article>
  </section>

</main>
