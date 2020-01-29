(function(){
    "use strict";

    window.addEventListener('load', e => {

        // Botones
        let nuevo_mensaje = document.getElementById('nuevo_mensaje');
        let btn_responder_mensaje = document.querySelectorAll('.btn_responder_mensaje');

        // Secciones
        let info_mensaje = document.querySelectorAll('.info_mensaje');
        let responder_mensaje = document.getElementById('responder_mensaje');
        let crear_mensaje = document.getElementById('crear_mensaje');


        // Si viene de 'detalles evento', y además la página de la que viene no es ninguna del apartado 'mensajes'
        // mostramos el formulario de enviar mensaje, en caso de que estuvieramos en 'mensajes' para evitar que el formulario
        // se ponga abajo de las conversaciones hemos validado también de que página viene
        let contactarCon = document.getElementById('emailDestinatario');
        if(contactarCon.value !== '' && !document.referrer.includes('mensajes')){
            crear_mensaje.style.display = 'block';
        }


        nuevo_mensaje.addEventListener('click', () => {

            if(info_mensaje != null){
                for(let i = 0; i< info_mensaje.length; i++){
                    info_mensaje[i].style.display = 'none';
                }
            }
            responder_mensaje.style.display = 'none';

            crear_mensaje.style.display = 'block';

        });


        for(let i = 0; i < btn_responder_mensaje.length; i++){
            btn_responder_mensaje[i].addEventListener('click', (e) => {

                let articleChat = findAncestor(btn_responder_mensaje[i], 'contacto_mis_mensajes');
                let emailDestinatario = articleChat.querySelector('#emailDestinatario').value;
                let tituloRespuesta = articleChat.querySelector('#tituloRespuesta').value;

                if(info_mensaje != null){
                    for(let i = 0; i< info_mensaje.length; i++){
                        info_mensaje[i].style.display = 'none';
                    }
                }
                crear_mensaje.style.display = 'none';

                responder_mensaje.style.display = 'block';

                responder_mensaje.querySelector('#responder_para').value = emailDestinatario;
                responder_mensaje.querySelector('#responder_titulo').value = tituloRespuesta;

            });
        }


        function findAncestor(el, cls) {
            while ((el = el.parentElement) && !el.classList.contains(cls));
            return el;
        }


    });// Load Page

})();
