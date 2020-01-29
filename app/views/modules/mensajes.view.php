<main class="main_mis_mensajes">
  <div class="panel_mis_mensajes">

    <section class="menu_mis_mensajes">
      <h1><?php echo _translate("Mensajes"); ?></h1>
      <span id="nuevo_mensaje"><?php echo _translate("Nuevo mensaje"); ?></span>
    </section>


    <section class="contactos_mis_mensajes">

      <?php if(isset($chats)) : ?>
        <?php foreach ($chats as $chat) : ?>
        <article class="contacto_mis_mensajes">
          <input type="hidden" id="emailDestinatario" value="<?php echo $chat->getRecibidoPor(); ?>">
          <input type="hidden" id="tituloRespuesta" value="<?php echo $chat->getTitulo(); ?>">
          <a href="/mensajes/<?php echo $chat->getFKGrupo(); ?>">
            <?php
              $miniatura = explode('/', $chat->{'imagen'});
              $nombreMiniatura = $miniatura[2];
              $rutaMiniatura = '/img/perfil/min_'.$nombreMiniatura;
            ?>
            <img src="<?php echo $rutaMiniatura; ?>" alt="">
            <div class="nombre_badge"><span><?php echo $chat->{'nombre_completo'}; ?></span></div>
            <span><?php echo $chat->getFechaHora(); ?></span>
            <div class="desc_mensaje">
              <span><?php echo $chat->getTitulo(); ?></span>
            </div>
          </a>
          <div class="acciones_mensaje">
            <button class="btn_responder_mensaje"><i class="fa fa-reply"></i></button>
            <a href="/mensajes/eliminar/<?php echo $chat->getFKGrupo(); ?>"><i class="fa fa-trash-o"></i></a>
          </div>
        </article>
        <?php endforeach; ?>
      <?php endif; ?>

    </section>



    <section class="conversacion_mis_mensajes">

      <!-- Mensaje de error -->
      <?php
        if(isset($_SESSION['errorEnviarMensaje']))
        {
          foreach ($_SESSION['errorEnviarMensaje'] as $error)
          {
              echo '<p class="errorEnviarMensaje">' . $error . '</p>';
          }
        }
      ?>

      <!-- LEER MENSAJE | En caso de tener mensajes anidados se replicará '.info_mensaje' -->
      <?php if(isset($mensajesChat)) : ?>

        <?php foreach ($mensajesChat as $mensajeChat) : ?>
        <article class="info_mensaje">
          <div class="img_info_mensaje">
            <?php
              $miniatura = explode('/', $mensajeChat->{'imagen'});
              $nombreMiniatura = $miniatura[2];
              $rutaMiniatura = '/img/perfil/min_'.$nombreMiniatura;
            ?>
            <img src="<?php echo $rutaMiniatura; ?>" alt="">
          </div>
          <div class="nombre_fecha_mensaje">
            <span><?php echo $mensajeChat->{'nombre_completo'}; ?></span>
            <span><?php echo $mensajeChat->getFechaHora(); ?></span>
          </div>
          <div class="contenido_mensaje">
            <h3><?php echo $mensajeChat->getTitulo(); ?></h3>
            <p><?php echo $mensajeChat->getDescripcion(); ?></p>
          </div>
        </article>
        <?php endforeach; ?>
      <?php endif; ?>

      <!-- CONTESTAR MENSAJE -->
      <article class="responder_mensaje" id="responder_mensaje">
        <form action="/mensajes/responder" method="post">
          <div class="remitente_mensaje">
              <label><?php echo _translate("De"); ?>: </label>
              <input type="email" name="emailRemitente" value="<?php echo $_SESSION['emailUsuario']; ?>">
          </div>
          <div class="destinatario_mensaje">
              <label><?php echo _translate("Para"); ?>: </label>
              <input type="email" name="emailDestinatario" id="responder_para">
          </div>
          <div class="tema_mensaje">
              <label>Re: </label>
              <input type="text" name="temaMensaje" id="responder_titulo">
          </div>
          <div class="mensaje">
              <label for="responder_mensaje"><?php echo _translate("Responder"); ?></label>
              <textarea name="contenidoMensaje" id="responder_mensaje"></textarea>
          </div>
          <input type="submit" value="<?php echo _translate("Responder"); ?>">
        </form>
      </article>

      <!-- CREAR MENSAJE -->
      <article class="crear_mensaje" id="crear_mensaje">
        <form action="/mensajes/enviar" method="post">
          <div class="remitente_mensaje">
            <label for="emailRemitente"><?php echo _translate("De"); ?>: </label>
            <input type="text" name="emailRemitente" id="emailRemitente" value="<?php echo $_SESSION['emailUsuario']; ?>" disabled="disabled">
          </div>
          <div class="destinatario_mensaje">
            <label for="emailDestinatario"><?php echo _translate("Para"); ?>: </label>
            <input type="text" name="emailDestinatario" id="emailDestinatario" value="<?php echo (isset($contactarCon)) ? $contactarCon : ''; ?>">
          </div>
          <div class="tema_mensaje">
            <label for="temaMensaje"><?php echo _translate("Tema"); ?>: </label>
            <input type="text" name="temaMensaje" id="temaMensaje">
          </div>
          <div class="mensaje">
            <label for="contenidoMensaje"><?php echo _translate("Mensaje"); ?></label>
            <textarea name="contenidoMensaje" id="contenidoMensaje"></textarea>
          </div>
          <input type="submit" value="<?php echo _translate("Enviar"); ?>">
        </form>
      </article>

    </section>

  </div>
</main>

<script type="text/javascript" src="/js/mensajes.js"></script>
