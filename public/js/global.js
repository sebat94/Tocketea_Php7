(function(){
    "use strict";

    window.addEventListener('load', e => {

        let url = window.location.href;
        let uri = url.split("http://localhost/").pop();
        let elemMenu = document.querySelectorAll('.cabecera_der nav ul li');

        for(let i = 0; i < elemMenu.length; i++){
            elemMenu[i].classList.remove('menu_activo');
        }

        if(uri === ''){
            elemMenu[Object.keys(elemMenu)[0]].classList.add('menu_activo');

        }else if(uri === 'nosotros'){
            elemMenu[Object.keys(elemMenu)[1]].classList.add('menu_activo');

        }else if(uri.includes('acceder')){
            elemMenu[Object.keys(elemMenu)[2]].classList.add('menu_activo');

        }else{
            elemMenu[Object.keys(elemMenu)[2]].classList.add('menu_activo');
        }

    });// Load Page

})();
