(function(){
  "use strict";

  window.addEventListener('load', e => {

    let form_comprar_entradas = document.getElementById('form_comprar_entradas');
    let input_numero_entradas = document.getElementById('input_numero_entradas');
    let numero_entradas = Number(input_numero_entradas.value);  // cadena vacía --> 0
    let restarEntrada = document.getElementById('restarEntrada');
    let sumarEntrada = document.getElementById('sumarEntrada');

    // Recoge el valor en 'numero_entradas' al introducir un dato en el input
    input_numero_entradas.addEventListener('input', e => {
      numero_entradas = Number(e.target.value);
    });

    // Resta entradas
    restarEntrada.addEventListener('click', e => {
      if(numero_entradas > 0){
        numero_entradas--;
        input_numero_entradas.value = numero_entradas;
      }
    });
    // Suma entradas
    sumarEntrada.addEventListener('click', e => {
      if(numero_entradas >= 0){
        numero_entradas++;
        input_numero_entradas.value = numero_entradas;
      }
    });

    // Se enviará solo si el dato es numérico y es mayor que 0
    form_comprar_entradas.addEventListener('submit', e => {
      if(Number.isNaN(numero_entradas) || typeof numero_entradas !== 'number' || numero_entradas <= 0){
        e.preventDefault();
      }
    });

  });

})();
