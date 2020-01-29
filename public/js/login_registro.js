(function(){
  "use strict";

  window.addEventListener('load', e => {

      // Para saber si tenemos que abrir el login o el registro, que en una parte del proyecto lo necesita
      // recibiremos un parámetro por GET enviado en PHP, que recogeremos con JS para verificar cual debe
      // abrirse, en caso de no ser recibida se abrirá la opción por defecto que es el login.
      // **La variable pasada por url será 'open' y el valor recibido será 'login' ó 'registro'
      // ***No podemos usar 'URLSearchParams()' porque el navegador Edge aún no lo soporta
      function getParameterByName(name, url) {
        if (!url) url = window.location.href;
        name = name.replace(/[\[\]]/g, "\\$&");
        let regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"), results = regex.exec(url);
        if (!results) return null;
        if (!results[2]) return '';
        return decodeURIComponent(results[2].replace(/\+/g, " "));
      }
      let open = getParameterByName('open');
      // Si la url no nos manda parámetros, y devuelve null,
      // entonces comprobamos si hemos mandado la info del form en el que estamos desde algún archivo php
      // Y si no es ninguna de las 2 por defecto abrimos el login

      if(open === null){
          let inputApoyo = document.getElementById('formActual');
          open = inputApoyo.value;
      }


      if( open === null || (open !== "login" && open !== "registro") ){
        open = "login";
      }

      // Información del 'login' y 'registro' en la información lateral
      let infoLogin = document.getElementById("info_login");
      let infoRegistro = document.getElementById("info_registro");
      // Carta blanca del 'registro' y 'login'
      let cartaLoginRegistro = document.getElementById("carta_login_registro");
      // Formulario del 'login' y 'registro'
      let formLogin = document.getElementById("form_login");
      let formRegistro = document.getElementById("form_registro");
      // Botones cambio entre formularios
      let btn_mover_a_registro = document.getElementById("btn_ir_registro");
      let btn_mover_a_login = document.getElementById("btn_ir_login");
      let btn_mover_a_registro2 = document.getElementById("ir_registro2");
      let btn_mover_a_login2 = document.getElementById("ir_login2");
      // Si es la primera vez que carga el window, será 'true' y eso hará que insotruzca el efecto opacidad al formulario
      // y no lo cargue cada vez al abrir la ventana
      let firstTimeLoad = true;

      // Nada más cargar el archivo displayeamos la carta que habíamos ocultado para evitar que se viera la posición
      // inicial antes de ser movida a la nueva posición en caso de abrirse en 'login' o 'registro'
      cartaLoginRegistro.classList.add('display_block');



      // opacidad_1    mover_carta_a_registro / mover_carta_a_login    display_block
      // Carga por primera vez el archivo con su correspondiente sección
      if( open === "login" ){
        irLoginBigScreen();
      }else if(open === "registro"){
        irRegistroBigScreen();
      }



      /*-- ************************* --*/
      /*--          EVENTOS          --*/
      /*-- ************************* --*/
      // Para navegar entre login / registro en PC & Tablet
      btn_mover_a_login.addEventListener('click', e =>{
        irLoginBigScreen();
      });
      btn_mover_a_registro.addEventListener('click', e =>{
        irRegistroBigScreen();
      });
      // Para navegar entre login / reistro en Tablet & MVL
      btn_mover_a_login2.addEventListener('click', e =>{
        irLoginSmallScreen();
      });
      btn_mover_a_registro2.addEventListener('click', e =>{
        irRegistroSmallScreen();
      });




      /*-- ********************************** --*/
      /*-- ABRIMOS LOGIN EN PANTALLAS GRANDES --*/
      /*-- ********************************** --*/
      function irLoginBigScreen(){

        // La transición css que hará efecto 'opacity' cargará una vez todo el documento esté mostrado para evitar
        // que la transición se realice nada más cargar el documento en el formulario ya que tarda medio segundo.
        if(firstTimeLoad === true){
          formLogin.classList.add('transition_opacity');
          formRegistro.classList.add('transition_opacity');
        }

        // Ocultamos la información del login en caso de que esté activado
        infoLogin.classList.remove('opacidad_1');
        // Mostramos la información del registro
        infoRegistro.classList.add('opacidad_1');

        // Hacemos desaparecer el formRegistro en caso de que esté activado
        formRegistro.classList.remove('opacidad_1');
        setTimeout( () => {
          formRegistro.classList.remove('display_block');
        },250);

        // Si la carta está en la posición del registro, cambiamos la posición a la del login
        cartaLoginRegistro.classList.remove('mover_carta_a_registro');
        cartaLoginRegistro.classList.add('mover_carta_a_login');

        // Displayeamos el contenido del login
        formLogin.classList.add('display_block');
        // Mostramos el contenido dándole opacidad
        if(firstTimeLoad === true){
          formLogin.classList.add('opacidad_1');
          firstTimeLoad = false;
        }else{
          setTimeout( () => {
            formLogin.classList.add('opacidad_1');
          },500);
        }

      }


      /*-- ************************************* --*/
      /*-- ABRIMOS REGISTRO EN PANTALLAS GRANDES --*/
      /*-- ************************************* --*/
      function irRegistroBigScreen(){

        if(firstTimeLoad === true){
          formLogin.classList.add('transition_opacity');
          formRegistro.classList.add('transition_opacity');
        }

        infoRegistro.classList.remove('opacidad_1');
        infoLogin.classList.add('opacidad_1');

        formLogin.classList.remove('opacidad_1');
        setTimeout( () => {
          formLogin.classList.remove('display_block');
        },250);

        cartaLoginRegistro.classList.remove('mover_carta_a_login');
        cartaLoginRegistro.classList.add('mover_carta_a_registro');

        formRegistro.classList.add('display_block');
        if(firstTimeLoad === true){
          formRegistro.classList.add('opacidad_1');
          firstTimeLoad = false;
        }else{
          setTimeout( () => {
            formRegistro.classList.add('opacidad_1');
          },500);
        }

      }


      /*-- *********************************** --*/
      /*-- ABRIMOS LOGIN EN PANTALLAS PEQUEÑAS --*/
      /*-- *********************************** --*/
      function irLoginSmallScreen(){

        // Hacemos esto porque si haces resize de pequeño a grande la carta no se habrá desplazado
        // y el texto de información tampoco habrá cambiado.
        cartaLoginRegistro.classList.remove('mover_carta_a_registro');
        cartaLoginRegistro.classList.add('mover_carta_a_login');
        infoLogin.classList.remove('opacidad_1');
        infoRegistro.classList.add('opacidad_1');

        formRegistro.classList.remove("opacidad_1");
        setTimeout( e => {
            formRegistro.classList.remove("display_block");
            formLogin.classList.add("display_block");
        }, 250);
        setTimeout( e => {
            formLogin.classList.add("opacidad_1");
        }, 500);

      }


      /*-- ************************************** --*/
      /*-- ABRIMOS REGISTRO EN PANTALLAS PEQUEÑAS --*/
      /*-- ************************************** --*/
      function irRegistroSmallScreen(){

        cartaLoginRegistro.classList.remove('mover_carta_a_login');
        cartaLoginRegistro.classList.add('mover_carta_a_registro');
        infoRegistro.classList.remove('opacidad_1');
        infoLogin.classList.add('opacidad_1');

        formLogin.classList.remove("opacidad_1");
        setTimeout( e => {
            formLogin.classList.remove("display_block");
            formRegistro.classList.add("display_block");
        }, 250);
        setTimeout( e => {
            formRegistro.classList.add("opacidad_1");
        }, 500);

      }


  });// Load Page

})();
