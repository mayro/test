/*-----------------------------
	VARIABLES & FONCTIONS
-----------------------------*/


/*-----------------------------
	DOCUMENT READY
-----------------------------*/

$(document).ready(function(){
	
	//VERIF FORM PARTICIP

	///////////////////////
	//FORM Connexion
	///////////////////////
	var validator3 = $("#formchallenge").validate({
		rules: {
			'reglement': "required"
		},
		invalidHandler: function(e, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				var message = errors == 1
					? 'Vous avez oublié 1 champ. Il est indiqué ci-dessous'
					: errors + ' champs ne sont pas correctement renseignés. Veuillez les vérifier ci-dessous';
				$("#form_connexion").find("p.erreur").html(message);
				$("#form_connexion").find("p.erreur").show();
			} else {
				$("#form_connexion").find("p.erreur").hide();
			}
		},
		// set the errorClass as a random string to prevent label disappearing when valid
		errorClass : "bob",
		// use highlight and unhighlight
		highlight: function (element, errorClass, validClass) {
			$(element.form).find("label[for=" + element.id + "]").parents('.ligne')
			.addClass("error");
		},
		unhighlight: function (element, errorClass, validClass) {
			$(element.form).find("label[for=" + element.id + "]").parents('.ligne')
			.removeClass("error");
		},
		// the errorPlacement has to take the table layout into account
		errorPlacement: function(error, element) {
			/*if ( element.is(":checkbox") )
				error.appendTo( element.parent().parent().find('.c_error') );
			else
				error.appendTo( element.parent().find('.c_error') );*/
		},
		// specifying a submitHandler prevents the default submit, good for the demo
		submitHandler: function(form) {
			form.submit();
		},
		// set this class to error-labels to indicate valid fields
		success: function(label) {
			// set &nbsp; as text for IE
			label.html("").addClass("checked");
		}
	});
	

	///////////////////////
	//FORM Premium
	///////////////////////

	//Validation du code maillot
	$.validator.addMethod("codeMaillotAdi", function(value, element) {
		return this.optional(element) || /^A|H|T\-[0-9]{6}$/.test( value );
	}, 'Le code maillot n\'est pas bon');

	var validator4 = $("#form_premium").validate({
		rules: {
			code : {
				required: true,
				codeMaillotAdi: true
			}
		},
		invalidHandler: function(e, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				var message = errors == 1
					? 'Vous avez oublié 1 champ. Il est indiqué ci-dessous'
					: errors + ' champs ne sont pas correctement renseignés. Veuillez les vérifier ci-dessous';
				$("#form_connexion").find("p.erreur").html(message);
				$("#form_connexion").find("p.erreur").show();
			} else {
				$("#form_connexion").find("p.erreur").hide();
			}
		},
		// set the errorClass as a random string to prevent label disappearing when valid
		errorClass : "bob",
		// use highlight and unhighlight
		highlight: function (element, errorClass, validClass) {
			$(element.form).find("label[for=" + element.id + "]").parents('.ligne')
			.addClass("error");
		},
		unhighlight: function (element, errorClass, validClass) {
			$(element.form).find("label[for=" + element.id + "]").parents('.ligne')
			.removeClass("error");
		},
		// the errorPlacement has to take the table layout into account
		errorPlacement: function(error, element) {
			/*if ( element.is(":checkbox") )
				error.appendTo( element.parent().parent().find('.c_error') );
			else
				error.appendTo( element.parent().find('.c_error') );*/
		},
		// specifying a submitHandler prevents the default submit, good for the demo
		submitHandler: function(form) {
			form.find('p.erreur').hide(0);
			form.submit();
		},
		// set this class to error-labels to indicate valid fields
		success: function(label) {
			// set &nbsp; as text for IE
			label.html("").addClass("checked");
		}
	});




});