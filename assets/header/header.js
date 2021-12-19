const bootstrap = require('bootstrap');

var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
  return new bootstrap.Popover(popoverTriggerEl, {
    html: true,
    content: document.querySelector('.popover__login--template').cloneNode(true).content
  })
})