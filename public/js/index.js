(function(){
    "use strict";

    window.addEventListener('load', e => {

        // Si estamos en el index, ejecutará el código
        if(document.getElementsByClassName('main_index')[0] !== undefined){
            // VARIABLES MOSTRAR MENU LATERAL FILTROS
            let btn_filtros  = document.querySelector('.cabecera_izq button');
            let menu_filtros = document.getElementById('menu_lateral_filtrar');


            // MOSTRAR / OCULTAR MENU LATERAL FILTROS
            let estamosEnIndex = document.getElementsByClassName('main_index');
            if(estamosEnIndex.length > 0){
                btn_filtros.addEventListener('click', e => {
                    e.target.classList.toggle('hamburguesa_activa');
                    // Al clicar en la hamburguesa le añadimos la clase con la transición
                    menu_filtros.classList.add('transition_activated');
                    menu_filtros.classList.toggle('mostrar_filtros');
                });
            }


            // ESTO ELIMINARÁ LOS ESTILOS ACTIVOS DEL APARTADO 'c_all', 'p_all' y 'f_all' EN CASO DE QUE HAGAMOS UN FILTRO QUE LE AFECTE
            let categorias = document.querySelectorAll('#formFilter input[id^="c_"]');
            let provincias = document.querySelectorAll('#formFilter input[id^="p_"]');
            let fechas = document.querySelectorAll('#formFilter input[id^="f_"]');
            let c_all_label = document.querySelector('.filtro_categoria ul li:first-of-type label');
            let p_all_label = document.querySelector('.filtro_ciudad ul li:first-of-type label');
            let f_all_label = document.querySelector('.filtro_fecha ul li:first-of-type label');
            // Variable para saber si tenemos que mantener los filtros abiertos o no
            let filterMenuClose = false;
            // Si es distinto de 'c_all' y está checkeado, eliminamos los estilos de 'c_all'
            let deleteStyleCALL = false;
            for (let c = 0; c < categorias.length; c++){
                if(categorias[c].id !== 'c_all' && categorias[c].checked){
                    deleteStyleCALL = true;
                    filterMenuClose = true;
                }else if(categorias[c].id === 'c_all' && categorias[c].checked){
                    filterMenuClose = true;
                }
            }
            if(deleteStyleCALL){
                c_all_label.style.backgroundColor = 'transparent';
                c_all_label.style.textIndent = '0';
            }
            // Si es distinto de 'p_all' y está checkeado, eliminamos los estilos de 'p_all'
            let deleteStylePALL = false;
            for (let p = 0; p < provincias.length; p++){
                if(provincias[p].id !== 'p_all' && provincias[p].checked){
                    deleteStylePALL = true;
                    filterMenuClose = true;
                }else if(provincias[p].id === 'p_all' && provincias[p].checked){
                    filterMenuClose = true;
                }
            }
            if(deleteStylePALL){
                p_all_label.style.backgroundColor = 'transparent';
                p_all_label.style.textIndent = '0';
            }
            // Si es distinto de 'f_all' y está checkeado, eliminamos los estilos de 'f_all'
            let deleteStyleFALL = false;
            for (let p = 0; p < fechas.length; p++){
                if(fechas[p].id !== 'f_all' && fechas[p].checked){
                    deleteStyleFALL = true;
                    filterMenuClose = true;
                }else if(fechas[p].id === 'f_all' && fechas[p].checked){
                    filterMenuClose = true;
                }
            }
            if(deleteStyleFALL){
                f_all_label.style.backgroundColor = 'transparent';
                f_all_label.style.textIndent = '0';
            }
            // EN CASO DE HABER PULSADO ALGÚN FILTRO, MANTENEMOS EL MENÚ LATERAL ABIERTO POR SI QUIERE SEGUIR APLICANDO FILTROS
            if(filterMenuClose){
                // Mientras estemos filtrando eliminaremos la clase de la transición para que no aparezca cada vez que recarga la página
                menu_filtros.classList.remove('transition_activated');
                menu_filtros.classList.add('mostrar_filtros');
            }
            // El buscador también respetará que el menú
            let inputBuscador = document.getElementById('buscador');
            if(inputBuscador.value !== ''){
                menu_filtros.classList.remove('transition_activated');
                menu_filtros.classList.add('mostrar_filtros');
            }



            // Elimino el contenido del search al clicar el resto de filtros, para que el php evalúe bien la condición y no entre al search
            let resetBuscador = document.querySelectorAll('#formFilter input[id^="c_"], #formFilter input[id^="p_"], #formFilter input[id^="f_"]');
            for(let d = 0; d < resetBuscador.length; d++){
                resetBuscador[d].addEventListener('click', () => {
                    document.getElementById('buscador').value = "";
                });
            }




            // Refrescamos la página al aplicar un nuevo filtro y le ponemos el nuevo estilo al elemento hermano label
            let eachInputCheckbox = document.querySelectorAll('#formFilter input:not(#buscador)');
            for(let i = 0; i < eachInputCheckbox.length; i++){
                eachInputCheckbox[i].addEventListener('click', () => {
                    let idInput = eachInputCheckbox[i].id;

                    // Categorías
                    if(idInput === 'c_all'){
                        let inputsADesmarcar = document.querySelectorAll('#formFilter input[id^="c_"]');
                        for(let j = 0; j < inputsADesmarcar.length; j++){
                            inputsADesmarcar[j].checked = false;    // Desmarcamos el resto de opciones
                            if (inputsADesmarcar[j].id === 'c_all'){ inputsADesmarcar[j].checked = true; } // Dejamos marcada solo la de 'c_all'
                            inputsADesmarcar[j].style.backgroundColor = 'transparent';
                            inputsADesmarcar[j].style.textIndent = "0";
                        }
                    }else if(idInput !== 'c_all' && idInput.includes('c_')){
                        // Clicar en cualquiera que no sea 'c_all' le quitaremos los estilos activos a 'c_all' y lo desmarcamos
                        let c_all = document.getElementById('c_all');
                        c_all.checked = false;
                        c_all.nextSibling.style.backgroundColor = 'transparent';
                        c_all.nextSibling.style.textIndent = '0';
                    }

                    // Provincias
                    if(idInput === 'p_all'){
                        let inputsADesmarcar = document.querySelectorAll('#formFilter input[id^="p_"]');
                        for(let z = 0; z < inputsADesmarcar.length; z++){
                            inputsADesmarcar[z].checked = false;
                            if (inputsADesmarcar[z].id === 'p_all'){ inputsADesmarcar[z].checked = true; }
                            inputsADesmarcar[z].style.backgroundColor = 'transparent';
                            inputsADesmarcar[z].style.textIndent = '0';
                        }
                    }else if(idInput !== 'p_all' && idInput.includes('p_')){
                        let p_all = document.getElementById('p_all');
                        p_all.checked = false;
                        p_all.nextSibling.style.backgroundColor = 'transparent';
                        p_all.nextSibling.style.textIndent = '0';
                    }


                    // Si realizamos una búsqueda con el buscador, eliminamos el resto de filtros
                    if(document.getElementById('buscador').value !== ''){
                        let resetFiltros = document.querySelectorAll('#formFilter input[id^="c_"], #formFilter input[id^="p_"], #formFilter input[id^="f_"]');
                        for(let d = 0; d < resetBuscador.length; d++){
                            resetBuscador[d].checked = false;
                        }
                    }


                    // Submit
                    document.getElementById('formFilter').submit();
                });
            }
        }



    });// Load Page

})();
