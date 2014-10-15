/*-----------------------------
	DOCUMENT READY
-----------------------------*/

$(document).ready(function(){


	///////////////////////
	//AJOUT DE METHODES DE VALIDATION
	///////////////////////

	$.validator.addMethod("lettersonly", function(value, element) {
	  return this.optional(element) || /^[a-z]+$/i.test(value);
	}, "Que des lettres svp"); 


  $.validator.addMethod("FullDate", function() {
	//if all values are selected
	if($("#jj").val() != "" && $("#mm").val() != "" && $("#aaaa").val() != "") {
		return true;
	} else {
		return false;
	}
  }, 'Veuillez remplir votre date de naissance');

  //Validation du mot de passe
  $.validator.addMethod("mdpAdi", function(value, element) {
	return this.optional(element) || /^[a-zA-Z0-9]{8,12}$/.test( value );
  }, 'Le mot de passe doit faire entre 8 et 12 caractères et contenir au moins 1 chiffre et 1 lettre');

  //Validation du code maillot
  $.validator.addMethod("codeMaillotAdi", function(value, element) {
	return this.optional(element) || /^A|H|T\-[0-9]{6}$/.test( value );
  }, 'Le code maillot n\'est pas bon');


  // this function checks the selected date, returning true if older than 18 years
  $.validator.addMethod("AgeAdi", function() {
	//only compare date if all items have a value
	if($("#jj").val() != "" && $("#mm").val() != "" && $("#aaaa").val() != "") {
		var day = $('#jj').val();
		var month = $('#mm').val();
		var year = $('#aaaa').val();
		var dateBirth = new Date(year, month, day, 0, 0, 0, 0).getTime() / 1000;
		var currentDateobj = new Date();
		var date13 = new Date(currentDateobj.getFullYear() - 13, currentDateobj.getMonth(), currentDateobj.getDate(), 0, 0, 0, 0).getTime() / 1000;
		var date16 = new Date(currentDateobj.getFullYear() - 16, currentDateobj.getMonth(), currentDateobj.getDate(), 0, 0, 0, 0).getTime() / 1000;

		if (dateBirth > date13)
		{
			$.colorbox({html: $('#under13').clone(), width:"50%"});
			return false;
		}
		if (dateBirth < date13 && dateBirth > date16)
		{
			$.colorbox({html: $('#between1315').clone(), width:"50%", onComplete: function() {
				if ($('#pcf').val() == 'Y')
					$("#colorbox #between1315 input").attr("checked", "checked");
				else
					$("#colorbox #between1315 input").attr("checked", false);									
				$("#colorbox #between1315 input").change(function() {
					if ($(this).is(':checked'))
						$('#pcf').val('Y');
					else
						$('#pcf').val('N');
				});
				$("#colorbox .button").click(function() {
					if ($('#pcf').val() == 'Y')
					{
						$('#register_button').val('Y');
						form.submit();
					}
					return false;
				});
			}});
			return false;
		}
		return true;
	}
  }, 'Vous devez avoir 18 ans pour vous inscrire');




	///////////////////////
	//FORM INSCRIPTION
	///////////////////////
	var validator = $("#form_inscription").validate({
		rules: {
			civ: "required",
			nom: {
				required: true,
				lettersonly: true,
				maxlength: 50
			},
			prenom: {
				required: true,
				lettersonly: true,
				maxlength: 50
			},
			email: {
				required: true,
				email: true,
				maxlength: 50
			},
			cp: {
				required: true,
				digits: true,
				minlength: 5,
				maxlength: 5
			},
			aaaa: "FullDate",
			util: "required",
			mdp: {
				required: true,
				minlength: 8,
				maxlength: 12,
				mdpAdi: true
			},
			confirm_mdp: {
				equalTo: "#mdp"
			},
			reglement :"required",
			code : {
				codeMaillotAdi: true
			}
		},
		groups:{
			date_of_birth:"jj mm aaaa"
		},
		invalidHandler: function(e, validator) {
			var errors = validator.numberOfInvalids();
			if (errors) {
				var message = errors == 1
					? 'Vous avez oublié 1 champ. Il est indiqué ci-dessous'
					: errors + ' champs ne sont pas correctement renseignés. Veuillez les vérifier ci-dessous';
				$("#form_inscription").find("p.erreur").html(message).show();
			} else {
				$("#form_inscription").find("p.erreur").hide();
			}
		},
		/*messages: {
			email_push: {
				required: "L'email est obligatoire",
				email: "Veuillez renseigner un email valide",
				maxlength: "128 caractères maximum"
			}
		},*/
		// the errorPlacement has to take the table layout into account
		errorPlacement: function(error, element) {
			/*if ( element.is(":checkbox") )
				error.appendTo( element.parent().parent().find('.c_error') );
			else
				error.appendTo( element.parent().find('.c_error') );*/
		},
		// set the errorClass as a random string to prevent label disappearing when valid
		errorClass : "bob",
		// use highlight and unhighlight
		highlight: function (element, errorClass, validClass) {
			$(element.form).find("label[for=" + element.id + "]").parents('.ligne')
			.addClass("error");

			var errors = validator.numberOfInvalids();
			if (errors) {
				var message = errors == 1
					? 'Vous avez oublié 1 champ. Il est indiqué ci-dessous'
					: errors + ' champs ne sont pas correctement renseignés. Veuillez les vérifier ci-dessous';
				$(element.form).find("p.erreur").html(message).show();
			} else {
				$(element.form).find("p.erreur").hide();
			}
		},
		unhighlight: function (element, errorClass, validClass) {
			$(element.form).find("label[for=" + element.id + "]").parents('.ligne')
			.removeClass("error");

			var errors = validator.numberOfInvalids();
			if (errors) {
				var message = errors == 1
					? 'Vous avez oublié 1 champ. Il est indiqué ci-dessous'
					: errors + ' champs ne sont pas correctement renseignés. Veuillez les vérifier ci-dessous';
				$(element.form).find("p.erreur").html(message).show();
			} else {
				$(element.form).find("p.erreur").hide();
			}
		},
		// specifying a submitHandler prevents the default submit, good for the demo
		submitHandler: function(form) {

			//On validate l'Age
			var day = $('#jj').val();
			var month = $('#mm').val();
			var year = $('#aaaa').val();
			var dateBirth = new Date(year, month - 1, day, 0, 0, 0, 0).getTime() / 1000;
			var currentDateobj = new Date();
			var date13 = new Date(currentDateobj.getFullYear() - 13, currentDateobj.getMonth(), currentDateobj.getDate(), 0, 0, 0, 0).getTime() / 1000;
			var date16 = new Date(currentDateobj.getFullYear() - 16, currentDateobj.getMonth(), currentDateobj.getDate(), 0, 0, 0, 0).getTime() / 1000;

			// Si < 13
			if (dateBirth > date13)
			{
				$.colorbox({
					inline: true,
					href: '#under13', 
					width: largeurPopin,
					maxWidth: maxPopin
				});
				return false;
			}

			//Si entre 13 et 16
			if (dateBirth <= date13 && dateBirth > date16)
			{
				$.colorbox({
					inline: true,
					href: '#between1315', 
					width: largeurPopin,
					maxWidth: maxPopin,
					onComplete: function() {
						if ($('#pcf_try').val() == 'Y')
							$("#pcf_try").attr("checked", "checked");
						else
							$("#pcf_try").attr("checked", false);

						$("#pcf_try").change(function() {
							if ($(this).is(':checked'))
								$('#pcf_try').val('Y');
							else
								$('#pcf_try').val('N');
						});
						$("#colorbox .button").click(function() {
							if ($('#pcf_try').val() == 'Y')
							{
								form.submit();
							} else {
								return false;
							}
						});
					}
				});
				return false;
			}


			form.find('p.erreur').hide(0);
			form.submit();
		},
		// set this class to error-labels to indicate valid fields
		success: function(label) {
			// set &nbsp; as text for IE
			label.html("").addClass("checked");
		}
	});





	
	///////////////////////
	//FORM Connexion
	///////////////////////
	var validator2 = $("#form_connexion").validate({
		rules: {
			'utilb': "required",
			'mdpb': {
				required: true,
				minlength: 8,
				maxlength: 12,
				mdpAdi: true
			},
			'reglementb' :"required",
			code : {
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
		/*messages: {
			email_push: {
				required: "L'email est obligatoire",
				email: "Veuillez renseigner un email valide",
				maxlength: "128 caractères maximum"
			}
		},*/
		// the errorPlacement has to take the table layout into account
		errorPlacement: function(error, element) {
			/*if ( element.is(":checkbox") )
				error.appendTo( element.parent().parent().find('.c_error') );
			else
				error.appendTo( element.parent().find('.c_error') );*/
		},
		// set the errorClass as a random string to prevent label disappearing when valid
		errorClass : "bob",
		// use highlight and unhighlight
		highlight: function (element, errorClass, validClass) {
			$(element.form).find("label[for=" + element.id + "]").parents('.ligne')
			.addClass("error");

			var errors = validator2.numberOfInvalids();
			if (errors) {
				var message = errors == 1
					? 'Vous avez oublié 1 champ. Il est indiqué ci-dessous'
					: errors + ' champs ne sont pas correctement renseignés. Veuillez les vérifier ci-dessous';
				$(element.form).find("p.erreur").html(message);
				$(element.form).find("p.erreur").show();
			} else {
				$(element.form).find("p.erreur").hide();
			}
		},
		unhighlight: function (element, errorClass, validClass) {
			$(element.form).find("label[for=" + element.id + "]").parents('.ligne')
			.removeClass("error");

			var errors = validator2.numberOfInvalids();
			if (errors) {
				var message = errors == 1
					? 'Vous avez oublié 1 champ. Il est indiqué ci-dessous'
					: errors + ' champs ne sont pas correctement renseignés. Veuillez les vérifier ci-dessous';
				$(element.form).find("p.erreur").html(message);
				$(element.form).find("p.erreur").show();
			} else {
				$(element.form).find("p.erreur").hide();
			}
		},
		// specifying a submitHandler prevents the default submit, good for the demo
		submitHandler: function() {
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





$(window).load(function() {
	///////////////////////
	//ANCRE SUR FORMULAIRE
	///////////////////////
	ancre = window.location.hash;
	if ($('.landing').length) {
		if (ancre == "#form") {
			var timerScroll = setTimeout(
				function () {
					$.scrollTo((palierAnimSection2+100), 500, {easing:'easeOutQuint'} );
				}
			, 500);
		} else {
			$.scrollTo(0, 0, {easing:'easeOutQuint'} );
		}
	}
	
});