<section class="login_registro">

    <div class="img_login_registro">
      <img src="img/web/1.jpg" alt="">
    </div>

    <!-- INPUT SOPORTE JAVSACRIPT -->
    <input type="hidden" id="formActual" value="<?php if(isset($formActual)) echo $formActual; ?>">
    <!-- END SOPORTE -->

    <div class="contenedor_login_registro">
      <div class="bloque_contenedor">

          <article class="info_login_registro">


            <section class="ir_registro" id="info_registro">
              <h2><?php echo _translate("&#191;No tienes una cuenta&#63;"); ?></h2>
              <p><?php echo _translate("Date del alta ahora y disfruta de todas las ventajas que te ofrecemos en Tocketea."); ?></p>
              <div class="btn_ir_registro" id="btn_ir_registro"><?php echo _translate("Reg&iacute;strate"); ?></div>
            </section>


            <section class="ir_login" id="info_login">
              <h2><?php echo _translate("&#191;Tienes una cuenta&#63;"); ?></h2>
              <p><?php echo _translate("A que esperas para entrar y seguir disfrutando de tus eventos favoritos en Tocketea."); ?></p>
              <div class="btn_ir_login" id="btn_ir_login">Login</div>
            </section> <!-- Se mostrará cuando estemos en el registro -->


            <section class="form_login_registro" id="carta_login_registro">

              <!-- FORMULARIO LOGIN -->
              <article class="contenido_login" id="form_login">
                <h1><?php echo _translate("Login"); ?></h1>
                <form action="acceder" method="post">

                  <!-- MENSAJE ERROR LOGIN -->
                  <?php
                    if(isset($erroresFormLog)){
                      echo '<div class="mensaje_error_login_registro">
                              <span>'. $erroresFormLog .'</span>
                              <i class="fa fa-caret-down"></i>
                            </div>';
                    }
                  ?>

                  <div class="login_email">
                    <input type="email" placeholder="Email" name="emailLogin" id="emailLogin" tabindex="1" required>
                    <i class="fa fa-envelope"></i>
                  </div>
                  <div class="login_password">
                    <input type="password" placeholder="<?php echo _translate("Contrase&ntilde;a"); ?>" name="passwordLogin" id="passwordLogin" tabindex="2" required>
                    <i class="fa fa-lock"></i>
                  </div>

                  <div class="login_enviar">
                    <a href="#"><?php echo _translate("Recuperar contrase&ntilde;a"); ?></a>
                    <input type="submit" tabindex="3" value="LOGIN">
                  </div>

                  <div class="ir_registro2" id="ir_registro2"><span><?php echo _translate("Reg&iacute;strate"); ?></span></div>

                </form>
              </article>
              <!-- FIN FORMULARIO LOGIN -->

              <!-- FORMULARIO REGISTRO -->
                    <!-- Apoyo desde validar registro para mantener el form registro abierto -->
              <?php if(isset($erroresFormReg) && !empty($erroresFormReg)) : ?>
                <input type="hidden" id="hayErroresReg" value="hayErrores">
              <?php endif; ?>

              <article class="contenido_registro" id="form_registro">
                <h1><?php echo _translate("Registro"); ?></h1>
                <form action="acceder" method="post">

                  <!-- MENSAJE ERROR REGISTRO -->
                  <?php
                    if(isset($erroresFormReg))
                    {
                      echo '<div class="mensaje_error_login_registro">
                              <span>Introduce los datos <u>corréctamente</u>
                              <div>
                                <ul>';
                                  $i = 1;
                                  foreach($erroresFormReg as $error){
                                      echo '<li>'. $i.'. ' . $error .'</li>';
                                    $i++;
                                  }
                        echo   '</ul>
                              </div>
                              </span>
                              <i class="fa fa-caret-down"></i>
                            </div>';
                    }
                  ?>

                  <div class="registro_nombre">
                    <input type="text" placeholder="<?php echo _translate("Nombre completo"); ?>" name="nombreRegistro" id="nombreRegistro" value="<?php echo (isset($_POST['nombreRegistro']) ? $_POST['nombreRegistro'] : ''); ?>">
                    <i class="fa fa-user-circle-o"></i>
                  </div>

                  <div class="registro_provincia">
                    <select name="provinciaRegistro">
                      <option selected="selected" value="" disabled="disabled" hidden="hidden"><?php echo _translate("Provincia"); ?></option>
                      <?php foreach ($provincias as $provincia) : ?>
                        <option value="<?php echo $provincia['id']; ?>" <?php echo (isset($_POST['provinciaRegistro']) && $_POST['provinciaRegistro'] === $provincia['id'] ? "selected" : ""); ?>><?php echo $provincia['nombre']; ?></option>
                      <?php endforeach; ?>
                    </select>
                    <i class="fa fa-caret-down"></i>
                  </div>
                  <div class="registro_email">
                    <input type="email" placeholder="Email" name="emailRegistro" id="emailRegistro" value="<?php echo (isset($_POST['emailRegistro']) ? $_POST['emailRegistro'] : ''); ?>">
                    <i class="fa fa-envelope"></i>
                  </div>

                  <div class="registro_password">
                    <input type="password" placeholder="<?php echo _translate("Contrase&ntilde;a"); ?>" name="passwordRegistro" id="passwordRegistro">
                    <i class="fa fa-lock"></i>
                  </div>
                  <div class="registro_password">
                    <input type="password" placeholder="<?php echo _translate("Repetir contrase&ntilde;a"); ?>" name="repeatPasswordRegistro" id="repeatPasswordRegistro">
                    <i class="fa fa-lock"></i>
                  </div>


                  <div class="captcha">
                    <img src="img/web/captcha.php">
                    <input type="text" name="captcha_code" placeholder="Captcha">
                  </div>


                  <div class="registro_enviar">
                    <input type="submit" tabindex="6" value="<?php echo _translate("REGISTRARME"); ?>">
                  </div>

                  <div class="ir_login2" id="ir_login2"><span><?php echo _translate("LOGIN"); ?></span></div>

                </form>
              </article>
              <!-- FIN FORMULARIO REGISTRO -->

            </section><!-- form_login_registro -->
          </article><!-- info_login_registro -->

        </div><!-- bloque_contenedor -->
    </div><!-- contenedor_login_registro -->
  </section><!-- login_registro -->
  <script type="text/javascript" src="/js/login_registro.js"></script>
