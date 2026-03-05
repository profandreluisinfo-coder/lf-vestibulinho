/**
 * Inicializa todos os popovers com o trigger 'click'.
 * @description
 *   Inicializa todos os elementos com a classe 'popover-trigger' como
 *   popovers com o trigger 'click'. Além disso, fecha todos os popovers
 *   abertos quando o usuário clica fora do elemento.
 */

var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl)
})