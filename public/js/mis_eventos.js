(function(){
    "use strict";

    window.addEventListener('load', e => {

        let form = document.getElementById('filtrosEventos');
        let filtroCategoria = document.getElementById('filtroCategoria');

        let inputFecha = document.querySelectorAll('.filtrar_eventos_futuros_pasados input');
        let labelFecha = document.querySelectorAll('.filtrar_eventos_futuros_pasados label');

        filtroCategoria.addEventListener('change', () => {
            form.submit();
        });

        for(let i = 0; i < inputFecha.length; i++){
            inputFecha[i].checked ? labelFecha[i].style.textDecoration = 'underline' : labelFecha[i].style.textDecoration = 'none';
            inputFecha[i].addEventListener('change', () => {
                form.submit();
            });
        }

    });// Load Page

})();
