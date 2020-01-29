<main class="main_comprar_entradas">
  <div class="bloque_contenedor clearfix">

    <section class="section_comprar_entradas">

      <?php
        if(isset($mensaje_error))
          echo '<p class="comprar_entradas_error_message">' . $mensaje_error . '</p>';

        if(isset($mensaje_success))
            echo '<p class="comprar_entradas_success_message">' . $mensaje_success . '</p>';
      ?>

      <article class="article_comprar_entradas clearfix">
        <form action="/entradas/guardar" method="post" id="form_comprar_entradas">

          <input type="hidden" name="idEvento" value="<?php if(isset($idEvento)) echo $idEvento; ?>">

          <div class="sumar_restar_entradas">
            <label for="numero_entradas"><?php echo _translate("Uds. Tickets"); ?></label>
            <input type="text" value="0" name="numero_entradas" id="input_numero_entradas">
            <button type="button" id="restarEntrada"><a href="#">-</a></button>
            <button type="button" id="sumarEntrada"><a href="#">+</a></button>
          </div>

          <div class="btn_comprar_entradas">
            <input type="submit" value="<?php echo _translate("Comprar entradas"); ?>">
          </div>

        </form>
      </article>
    </section>

  </div>
</main>

<!-- Modal -->
<?php if(isset($openModal) && $openModal === true) : ?>
<div class="modal_preguntar_log">
  <div class="info_preguntar_log clearfix">
    <span>Necesitas estar registrado para comprar tus entradas</span>
    <div class="btn_ir_login_desde_comprar">
      <a href="/acceder">Login</a>
    </div>
    <div class="btn_ir_registro_desde_comprar">
      <a href="/acceder?open=registro">Registro</a>
    </div>
  </div>
</div>
<?php endif; ?>

<script type="text/javascript" src="/js/comprar_entradas.js"></script>
