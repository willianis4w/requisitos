var App = function () {

	'use strict';

	/*jslint browser: true*/
	/*global $, jQuery*/
	/*jslint todo: false, white: true */

	var self = this,
		path = window.location.hostname + '/exit';

	/*Atualizar Flex*/
	this.submitFlex = function (data) {
		$.ajax({
			url: 'flex/update',
			type: 'POST',
			data: {
				'data': data
			},
			complete: function () {
				$('.atualizacao').fadeIn("slow").delay(1000).fadeOut("slow");
			}
		});
	};


	// Atualizar Contável
	this.atualizarContavel = function (id, contavel) {
		$.ajax({
			url: 'flex/contavel',
			type: 'POST',
			data: {
				'id': id,
				'contavel': contavel
			},
			complete: function () {
				$('.atualizacao').fadeIn("slow").delay(1000).fadeOut("slow");
			}
		});
	};

};

$(document).ready(function ($) {

	'use strict';
	/*jslint browser: true*/
	/*global $, jQuery*/

	$('.spin').fadeOut();
	$('.fadeIn').fadeIn();

	var application = new App();

  	/*
	* Events 
  	*/

  	// Atualizar Flex
	$('#flex-form').submit(function () {
		var values = $(this).serializeArray();
		application.submitFlex(values);
		return false;
	});

	// Atualizar Contável
	$('.flex-contavel').click(function (e) {
		e.preventDefault();
		var id 			= $(this).data('id'),
			contavel 	= $(this).data('contavel'),
			tr = $(this).closest('tr'),
  			warning = tr.attr('class');

		application.atualizarContavel(id, contavel);

		if (contavel === 1) {
			$(this).data('contavel',0);
		}
		else {
			$(this).data('contavel',1);
		}

  			

  		if (warning === 'warning') {
  			$(tr).removeClass( 'warning' );
  		}
  		else {
  			$(tr).addClass( 'warning' );
  		}
  		
	});

});