(function(){
    "use strict";

    let form = document.getElementById('formFiltrarGestionUsr');
    let filtroPorRol = document.getElementById('categoriaMisEventos');

    filtroPorRol.addEventListener('change', () => {

        form.submit();

    });

})();
