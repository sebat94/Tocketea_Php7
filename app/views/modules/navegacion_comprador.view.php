<div class="cabecera_der">
  <nav>
    <ul>
      <li>
        <a href="/">Home</a>
        <a href="/"><i class="fa fa-home"></i></a>
      </li>
      <li>
        <a href="/nosotros"><?php echo _translate("Acerca de"); ?></a>
        <a href="/nosotros"><i class="fa fa-diamond"></i></a>
      </li>

      <li>
        <div class="dropdown"><span><i class="fa fa-user-o"></i><span><?php echo $dataUserLoged->getNombreCompleto(); ?></span></span>
          <div class="dropdown_content">
            <a href="/perfil"><?php echo _translate("Mi perfil"); ?></a>
            <a href="/mensajes"><?php echo _translate("Mis mensajes"); ?></a>
            <a href="/entradas"><?php echo _translate("Mis entradas"); ?></a>
            <a href="/salir"><?php echo _translate("Salir"); ?></a>
          </div>
        </div>
      </li>

    </ul>
  </nav>
</div>
