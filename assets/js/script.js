/*
* My Circle: Performance Management System
* Email: circle@aidan.my
* Version: 1.0
* Author: Akmal Akhpah
* Copyright 2019 Aidan Technologies
* Website: https://github.com/akmalakhpah/circle
*/
$(document).ready(function() {
    $('#slidesidebar').on('click', function() {
        $('#sidebar').toggleClass('active');
        $('#body').toggleClass('active');
    });

    $('#slidesidebar').mouseover( function() {
        $('#sidebar').removeClass('active');
        $('#body').removeClass('active');
    });

    $('input, textarea').focusout(function(event) {
    	var notempty = $(this).val();
    	if(notempty !== '') {
    		$(this).addClass('notempty');
	    }
    });

    $('input, textarea').each(function(index, el) {
        input = $(this).val();
        if(input !== '') {
            $(this).addClass('notempty');
        };
    });
    
    $('.ui.dropdown.selection, .ui.search.dropdown').each(function(index, el) {
        var dropdown = $(this).find('select').val();
        var selected = $(this).find('.menu .item.active.selected').attr('data-value');
        if (dropdown !== '' && selected !== undefined || selected !== '' && selected !== undefined) {
            $(this).addClass('notempty');
        }
    });

    $('.ui.dropdown.selection, .ui.search.dropdown').focusout(function(event) {
        var dd = $(this).find('select').val();
        var selected = $(this).find('.menu .item.active.selected').attr('data-value');
        if(dd !== '' && selected !== undefined || selected !== '' && selected !== undefined) {
            $(this).addClass('notempty');
        }
    });

});

$(window).resize(function(){
    if($(window).width() <= 768){
        $('#sidebar, #body').addClass('active');
    }
});

$('.dropdown').dropdown();
$('.ui.dropdown').dropdown();
$('.ui.checkbox').checkbox();
$('.ui.radio.checkbox').checkbox();
$('.ui.modal').modal();
$('.ui.basic.modal').modal();

$('.ui.add.modal').modal('attach events', '.btn-add', 'toggle');
$('.ui.edit.modal').modal('attach events', '.btn-edit', 'toggle');
$('.ui.modal.import').modal('attach events', '.btn-import', 'toggle');

// $('.airdatepicker').datepicker({ language: 'en' });
// $('.jtimepicker').mdtimepicker();