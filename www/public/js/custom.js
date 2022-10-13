

/*****************************************************************
					   Accessory 
*****************************************************************/

function ajaxAccessory() {
	$.ajax({
		url: "/ajax/getaccessories",
		type: "post",
		data: {

		},
		dataType: 'html',

		success: function (data) {

			$('.ajax-accessory').append(data);

		}
	});

}
/************************************************************
 * 						Clothes
 ***********************************************************/

function ajaxClothes() {
	$.ajax({
		url: "/ajax/getclothes",
		type: "post",
		dataType: 'html',
		data: {},
		success: function (data) {
			$('.ajax-clothes').html(data);

		}
	});
}
/************************************************
 * 				shoes
 * 
 ***************************************************/
/*function ajaxShoes() {
	$.ajax({
		url: "/ajax/getshoes",
		type: "post",
		dataType: 'html',
		data: {},
		success: function (data) {
			$('.ajax-shoes').html(data);
				$window.on('load', SEMICOLON.documentOnLoad.ajaxAccessory);

		}
	});

}*/

/*-----------------------------------------------------------------------------
---------------------------------------------------------------------------*/

$(document).ready(function () {

	if ($('.ajax-clothes').length) {
		ajaxClothes();
		$('#portfolio').click(function () {
			window.location.reload();
		});
	}
	if ($('.ajax-shoes').length) {
		//ajaxShoes();
	}
	if ($('.ajax-accessory').length) {

		ajaxAccessory();


		$('#loadMore').on('click', function () {


			var compteur = $('#compteur').html();
			compteur = parseInt(compteur) + 1;
			$.ajax({
				url: 'ajax/getoneaccessory',
				type: "post",
				data: { compteur: compteur },
				dataType: 'html',
				success: function (data) {

					$('#compteur').html(compteur);
					$('.ajax-accessory').append(data);

				}
			});

		});

		$('#portfolio').click(function () {
			location.load();
		});


	}
});

