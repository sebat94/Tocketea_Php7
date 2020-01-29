<!DOCTYPE html>
<html lang="<?php echo (isset($_SESSION['idiomaUsuario']) ? substr($_SESSION['idiomaUsuario'], 0, 2) : 'es'); ?>">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php echo (new tocketea\app\controllers\PagesController)->getTitlePage(); ?></title>
  <link rel="stylesheet" type="text/css" href="/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="/css/fonts.css">
  <link rel="stylesheet" type="text/css" href="/css/global.css">
</head>
<body>

  <header class="cabecera">

    <div class="cabecera_izq unselectable">
      <?php
        if($uri === 'index'){
          echo '<button type="button" id="btn_filtros"><span></span><span></span><span></span></button>';
        }
      ?>
      <h1><a href="/">Tocketea</a></h1>
    </div>

    <?php
      // Menú cabecera derecha ( según tipo de usuario )
      if(!is_null($dataUserLoged)){
        if($dataUserLoged->getRol() === 'ROL_ADMINISTRADOR'){
            include 'modules/navegacion_administrador.view.php';

        }else if($dataUserLoged->getRol() === 'ROL_GESTOR'){
            include 'modules/navegacion_gestor.view.php';

        }else if($dataUserLoged->getRol() === 'ROL_COMPRADOR'){
            include 'modules/navegacion_comprador.view.php';

        }
      }else{
          include 'modules/navegacion_sin_logear.view.php';
      }

    ?>
  </header>


  <?php

    echo $mainContent;  // $mainContent --> contenido de la página a mostrar

  ?>

  <script type="text/javascript" src="/js/global.js"></script>
  <script type="text/javascript" src="/js/index.js"></script>
</body>
</html>
