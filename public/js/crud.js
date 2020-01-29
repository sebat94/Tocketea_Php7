window.addEventListener('load', inicia);


function inicia() {
    let enlacesEliminar = document.getElementsByClassName('borrar_elemento');

    for (let i = 0; i < enlacesEliminar.length; i++) {
        enlacesEliminar[i].addEventListener('click', eliminaElemento);
    }
}



function eliminaElemento(e) {
    e.preventDefault();
    let enlaceEliminar = this;  // El link de borrar que hemos seleccionado

    // Determinamos si el id recibido es un entero o un string, para saber de donde llamamos al Swal y que fila tiene que borrar
    let urlOriginal = enlaceEliminar.href;
    let urlPieces = urlOriginal.split("/");
    let lastPiece = urlPieces[urlPieces.length - 1];

    swal({
        title: '¿Seguro que quieres eliminarlo?',
        text: "Esta operación no podrá deshacerse",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, bórralo!'
    }).then(function(){
        let xhttpRequest = new XMLHttpRequest();
        const url = enlaceEliminar.href;
        xhttpRequest.open('DELETE', url, true);
        xhttpRequest.send();
        xhttpRequest.onreadystatechange = function()
        {
            if (this.status === 200 && this.readyState === 4)   // Si la peticion Ajax se ha resuelto
            {
                // Recogemos la respuesta que viene en formato JSON desde 'EventoController@borrarEvento' o 'UsuarioController@borrarUsuario'
                let respuesta = JSON.parse(xhttpRequest.response);

                if (xhttpRequest.response !== null)
                {
                    let mensaje = '';
                    if (respuesta[0].code == 200)
                    {
                        // Comprobamos si es un evento o un usuario, dependiendo de si el id que llega es un entero o un string
                        if(Number.parseInt(lastPiece))
                            enlaceEliminar.parentNode.parentNode.remove();
                        else
                            enlaceEliminar.parentNode.parentNode.parentNode.remove();

                        swal(
                            'Eliminado!',
                            respuesta[0].message,
                            'success'
                        )
                    }
                    else
                        swal(
                            'No se ha elimiado!',
                            respuesta[0].message,
                            'error'
                        )
                }
            }
        };
    })
}