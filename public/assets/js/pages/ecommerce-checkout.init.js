/*
Template Name: Clivax - Admin & Dashboard Template
Author: Codebucks

File: ecommerce checkout Js File
*/

$(document).ready(function() {
    $('#checkout-nav-pills-wizard').bootstrapWizard({
        'tabClass': 'nav nav-pills nav-justified'
    });
});

// Active tab pane on nav link

var triggerTabList = [].slice.call(document.querySelectorAll('.twitter-bs-wizard-nav .nav-link'))
triggerTabList.forEach(function (triggerEl) {
    var tabTrigger = new bootstrap.Tab(triggerEl)

    triggerEl.addEventListener('click', function (event) {
        event.preventDefault()
        tabTrigger.show()
    })
})