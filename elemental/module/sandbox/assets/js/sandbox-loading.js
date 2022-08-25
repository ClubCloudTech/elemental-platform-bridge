/**
 * Add dynamic tabs to Elemental admin pages
 *
 * @package Elemental Plugin module/sandbox/assets/js/sandbox-loading.js
 */

 function onReady(callback) {
    var intervalID = window.setInterval(checkReady, 1500);

    function checkReady() {
        if (document.getElementsByTagName('body')[0] !== undefined) {
            window.clearInterval(intervalID);
            callback.call(this);
        }
    }
}

function show(id, value) {
    document.getElementById(id).style.display = value ? 'block' : 'none';
}

onReady(function () {
    show('elemental-sandbox-base', true);
    show('loading', false);
});