/*
Template Name: Clivax - Admin & Dashboard Template
Author: Codebucks

File: Table responsive Init Js File
*/

$(function() {
    $('.table-responsive').responsiveTable({
        addDisplayAllBtn: 'btn btn-secondary'
    });

    $('.btn-toolbar [data-toggle=dropdown]').attr('data-bs-toggle', "dropdown");
});