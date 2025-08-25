$.fn.cargarMapa = function(geoData, provinceData) {
	$(this).load('views/pages/geo/mapa/mapa.php', function(){
		cargarDatos(geoData, provinceData);
	});
}
/*
Añadimos un elemento por cada provincia, el id de cada elemento tiene que coincidir con el que tiene el trazado de ese misma provincia en mapa.html
Añadimos un data-loquesea por cada valor que necesitemos mostrar en el modal
*/
function cargarDatos(geoData, provinceData){

    geoData.forEach(function(value, key, geoData) {
       /* console.log(geoData[key]); */
        $("#" + geoData[key][0]).data("name", geoData[key][1]).data("courses", geoData[key][2]).data("enrolled", geoData[key][3]).data("users", geoData[key][4]).addClass("color-" + geoData[key][5]).data("graduates", geoData[key][6]).data("enrolments", geoData[key][7]).data("registrations", geoData[key][8]).data("deletes", geoData[key][9]).data("sessions", provinceData[key][2]).data("views", provinceData[key][3]);// Mañana !!!!

	});

	$('.path-provincia-activa').click(function(){
		
        $('#modalDatosProvincia').modal('toggle');
        $('#datos-provincia span').empty();
        $('#datos-provincia li').show();

        /*
            Recuperamos los valores con el data de cada elemento y lo cargamos en su span correspondiente
            Añadimos cada span que contenga el modal
        */

		incluirDato($('#datos-provincia .courses'), $(this).data("courses")),
		incluirDato($('#datos-provincia .enrolled'), $(this).data("enrolled")),
		incluirDato($('#datos-provincia .users'), $(this).data("users")),
		incluirDato($('#datos-provincia .graduates'), $(this).data("graduates")),
		incluirDato($('#datos-provincia .enrolments'), $(this).data("enrolments")),
		incluirDato($('#datos-provincia .registrations'), $(this).data("registrations")),
		incluirDato($('#datos-provincia .deletes'), $(this).data("deletes")),
		incluirDato($('#datos-provincia .sessions'), $(this).data("sessions")),
		incluirDato($('#datos-provincia .views'), $(this).data("views"))

        $('#modalDatosProvincia .modal-title').text($(this).data("name"));
        


        function incluirDato(clase, dato){
            if (dato!="0"){
                clase.html(dato);
            }else{
                clase.parent().hide();
            }
        }
	});



    /*Muestra un badge con el nombre de la provincia onhover*/
	$('.path-provincia-activa').hover(function(){
		var nombre_provincia = $(this).data("name");
		var number_courses = $(this).data("courses");
		var number_enrol = $(this).data("enrolled");
		var number_users = $(this).data("users");
		var pos_tt = $(this).data("data-posicion-tt");
		const medium_enrolled =  ((number_enrol / number_courses)).toFixed(2);
		var text_tooltip = 'Hola <i class="fa-solid fa-hand-point-up"></i>, soy la provincia de <b>' + nombre_provincia + '</b>.<br /><br /> <ul><li>Hay un total de <b>' + number_courses + ' cursos <i class="fas fa-book-open mr-1 ml-1" aria-hidden="true"></i></b> con usuarios míos.</li><li> En estos ' + number_courses + ' cursos hay un total de <b>' + number_enrol + ' usuarios <i class="fas fa-user-graduate mr-1 ml-1" aria-hidden="true"></i></b> míos.</li><li>Hay una media de <b>' + medium_enrolled + '</b> de mis usuarios por curso.</li><li> En total, mis usuarios <b><i class="fas fa-users mr-1 ml-1" aria-hidden="true"></i></b> son <b>' + number_users + '</b>.</li></ul>';
		$('#tooltip-provincia').removeClass('hidden');
		$('#tooltip-provincia .badge').append(text_tooltip);

		$(document).on('mousemove', function(e){

			if(pos_tt=="bottom"){
			    $('#tooltip-provincia').css({
			       left:  e.pageX + 20,
			       top:   e.pageY +20
			    });
			}else{
				$('#tooltip-provincia').css({
			       left:  e.pageX - 320,
			       top:   e.pageY - 180
			    });
			}
		});
	}, function(){
		$('#tooltip-provincia').addClass('hidden');
		$('#tooltip-provincia .badge').text("");
	});
}


