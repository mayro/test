/*-----------------------------
	VARIABLES & FONCTIONS
-----------------------------*/
var largeurEcran;
var format;

var detectPalier = function () {
	
	largeurEcran = $(window).width();
	
	// SI WEB ///////////////////////
	if (largeurEcran >= 960 && format != 'web') {
		$('body').removeClass('mobile').removeClass('tablette').addClass('web');
		format = 'web';
		changePalier ();
	}
	// SI TABLETTE///////////////////
	else if (760 <= largeurEcran && largeurEcran <= 959 && format != 'tablette') {
		$('body').removeClass('mobile').removeClass('web').addClass('tablette');
		format = 'tablette';
		changePalier ();
	}
	// SI MOBILE/////////////////////
	else if (largeurEcran <= 759 && format != 'mobile') {
		$('body').removeClass('tablette').removeClass('web').addClass('mobile');
		format = 'mobile';
		changePalier ();
	}
}




///////////////////
//Variables
///////////////////
var scrollTop;
var rubActive =1;
var palierAnimSection1;
var palierAnimSection2;
var palierAnimSection3;
var palierAnimSection4;
var palierAnimFooter;

var hauteurHeader;
var hauteurFooter;

///////////////////
//Resize visuels full
///////////////////
var resizeVisu =  function () {
	$('.visu_full').each(function(index, element) {
		$this = $(this);
		var largeurInitVisu = ($(this).attr('width')) ? $this.attr('width') : $this.data('w');
		var hauteurInitVisu = ($(this).attr('height')) ? $this.attr('height') : $this.data('h');
		var ratioVisu = largeurInitVisu/hauteurInitVisu;
		
		var largeurConteneur = $this.parents('.conteneur_visu').innerWidth();
		var hauteurConteneur = $this.parents('.conteneur_visu').innerHeight();
		var ratioConteneur = largeurConteneur/hauteurConteneur;
		
		if (ratioConteneur > ratioVisu) {
			$this.innerWidth(largeurConteneur);
			$this.innerHeight(largeurConteneur/ratioVisu);
			if ($this.hasClass('valign_t')) {
				//VAlignee au en haut
				var decalage = 0;
			} else if($this.hasClass('valign_b')) {
				//VAlignee au en bas
				var decalage = hauteurConteneur-$this.innerHeight();
			} else {
				//VAlignee au milieu
				var decalage = (hauteurConteneur-$this.innerHeight())/2;
			}
			
			$this.css('left', 0);
			$this.css('top',decalage);
		} else {
			$this.innerHeight(hauteurConteneur);
			$this.innerWidth(hauteurConteneur*ratioVisu);
			if ($this.hasClass('align_l')) {
				//Alignee au en haut
				var decalage = 0;
			} else if($this.hasClass('align_r')) {
				//Alignee au en bas
				var decalage = largeurConteneur-$this.innerWidth();
			} else {
				//Alignee au milieu
				var decalage = (largeurConteneur-$this.innerWidth())/2;
			}
			
			$this.css('top', 0);
			$this.css('left',decalage);
			
		}
		
	});
	
}

///////////////////
//Change Viewport 
///////////////////
var changeViewport = function () {
	if (format == 'mobile') {
		$('meta[name="viewport"]').attr('content', 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0');
	} else {
		$('meta[name="viewport"]').attr('content', 'width=1024');
	}
}

///////////////////
//Resize ecrans
///////////////////
var resizeEcrans = function () {
	if ($('section.rubrique').length) {
		$('.rubrique, .page_om').height($(window).height());
	}
	resizeVisu ();
}
///////////////////
//Rubriques actives
///////////////////
var rendreActif = function (num) {
	//console.log ('rubActive avant'+$('.page_om nav li a.actif').parents('li').index()+' ----- New rubActive'+num);
	//if ($('.page_om nav li a.actif').parents('li').index() != num) {
		$('.page_om nav li a.actif').removeClass('actif');
		$('.page_om nav li:eq('+num+') a').addClass('actif');
	//}
}

///////////////////
//Meme hauteur
///////////////////
equalheight = function(container){

var currentTallest = 0,
	currentRowStart = 0,
	rowDivs = new Array(),
	$el,
	topPosition = 0;
	
	$(container).each(function() {
		$el = $(this);
		$($el).height('auto')
		topPostion = $el.position().top;
	
		if (currentRowStart != topPostion) {
			for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
				rowDivs[currentDiv].height(currentTallest);
			}
			rowDivs.length = 0; // empty the array
			currentRowStart = topPostion;
			currentTallest = $el.height();
			rowDivs.push($el);
		} else {
			rowDivs.push($el);
			currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
		}
		for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
			rowDivs[currentDiv].height(currentTallest);
		}
	});
}



//LANCE EQUAL HEIGHT
var lanceEqualheight = function(container){
	//equalheight('#menu_pdt .conteneur_niv2 .col_1_5');
}

// REINIT STYLES
var reinitMenu = function () {
	//$('#conteneur_global, .conteneur_niv2, .row .contenu > ul, header .bloc_recherche fieldset').removeAttr('style');
}

//CHANGEMENT DE PALIERS RESPONSIVES
var changePalier = function () {
	//On reinitialise les scripts voulus
	reinitMenu ();
	changeViewport();
	if (format == 'mobile') {
		largeurPopin = '300px';
	}
	if (format != 'mobile') {
		largeurPopin = '562px';
	}
}


var maxPopin = '562px';
var largeurPopin = '562px';

/*-----------------------------
	DOCUMENT READY
-----------------------------*/

$(document).ready(function(){
	
	hauteurHeader = $('#header').height();
	hauteurFooter = $('#footer').outerHeight();


	if ($('.bg_triangle').length) { //Si Home page

		$('body').addClass('landing');

		//On fixe le top de la page
		$('.page_om .conteneur_corps').css({top: hauteurHeader+'px'});

		//On fixe la hauteur voulue au body
		$('body').height(hauteurHeader + 3000 + hauteurFooter);

		//LOADER
		$('img').imagesLoaded({
			callback: function($images, $proper, $broken)
			{
				$('.loader .obj_loader').fadeOut(100);
				$('.loader').fadeOut(1000);
			},
			progress: function (isBroken, $images, $proper, $broken) {
				var pct = Math.round( ( ( $proper.length + $broken.length ) * 100 ) / $images.length );
				$('.loader .pct span').html(pct+' %');

			}

		});
	}
	




	//Au chargement de la page et au resize de la fenetre on relance la detection des paliers
	detectPalier ();
	resizeEcrans ();

	//On style les éléments de formul
	//$('input, select').uniform();
	

	//On empeche les click sur les liens vides(#)
	$('a[href="#"]').click(function (event) {
		event.preventDefault();
	});

	//On étend les clicks pour les pushes challenges
	$('.liste_social li.challenge').click(function(event) {
		var href = $(this).find('a').attr('href');
		document.location = href;
	});

	

	///////////////////
	//Onglets form
	///////////////////
	$('#section2 .onglets li a').click(function () {
		var $this = $(this);
		var index = $this.parents('li').index();
		$this.parents('.onglets').find('a').removeClass('actif');
		$this.addClass('actif');

		$this.parents('.onglets').next('.contenus_onglets').find('.contenu_onglet:visible').hide().removeClass('actif');
		$this.parents('.onglets').next('.contenus_onglets').find('.contenu_onglet:eq('+index+')').show().addClass('actif');
	});

	///////////////////
	//Affiche/cache video
	///////////////////
	$('#section3 .conteneur_videos li .vignette').click(function () {
		var $this = $(this).parents('li');
		$this.find('.conteneur_video').addClass('actif');
		TweenMax.to(
			$this.find('.conteneur_video'), 
			0.5, 
			{css:{
				width: '200%',
				height: '200%'
			}, 
			ease:Power4.easeOut,
			onComplete: function () {
				$this.find('.close_video').fadeIn();
			}
		});
	});

	$('#section3 .conteneur_videos li .conteneur_video .close_video').click(function () {
		//on met en pause les videos
		try {
			// Instructions sensibles
			$('video,audio').each(function() {
				$(this)[0].player.pause();
			});
		}
		catch(err) {
			// Gestion des erreurs
		}
		


		var $this = $(this).parents('li');
		$this.find('.close_video').fadeOut();
		TweenMax.to($this.find('.conteneur_video'), 0.5, {css:{width: '0%',height: '0%'}, ease:Power4.easeOut,
			onComplete: function () {
				$this.find('.conteneur_video').removeClass('actif');
			}
		});
		
	});
	

	///////////////////
	//Popins
	///////////////////
	$('.show_popin').colorbox({
		inline: true,
		width: largeurPopin,
		maxWidth: maxPopin,
		onComplete: function () {
			$.colorbox.resize();
		}
	});
	$('.help_mdp').colorbox({
		inline: true,
		width: largeurPopin,
		maxWidth: maxPopin,
		href: '#templateMdp',
		onComplete: function () {
			$.colorbox.resize();
		}
	});
	$('.popin_connexion').colorbox({
		width: largeurPopin,
		maxWidth: maxPopin,
		onOpen : function () {$('#colorbox').addClass('popin_adi');},
		onComplete: function () {
			$.colorbox.resize();
		},
		onClosed : function () {$('#colorbox').removeClass('popin_adi');}
	});
	$('.popinModif').colorbox({
		width: largeurPopin,
		maxWidth: maxPopin,
		onOpen : function () {$('#colorbox').addClass('popin_adi');},
		onComplete: function () {
			$.colorbox.resize();
		},
		onClosed : function () {$('#colorbox').removeClass('popin_adi');}
	});
	$('.popinPremium').colorbox({
		width: largeurPopin,
		maxWidth: maxPopin,
		onOpen : function () {$('#colorbox').addClass('popin_adi');},
		onComplete: function () {
			$.colorbox.resize();
		},
		onClosed : function () {$('#colorbox').removeClass('popin_adi');}
	});

	$('body').on('click', '.close_cbox', function () {
		$.colorbox.close();
	});


	///////////////////
	//Players
	///////////////////
	if ($('.no-touch #page_accueil').length) {
		$('video.classique').mediaelementplayer({
			enableAutosize: true,
			iPadUseNativeControls: true,
			iPhoneUseNativeControls: true,
			success: function (mediaElement, domObject) {
				mediaElement.addEventListener('ended', function(e) {
					$this.find('.mejs-poster').show();
				}, false);
			}
		}); 
	} else  {
		$('video.classique').mediaelementplayer({ 
			videoWidth: '100%', 
			videoHeight: '100%',
			enableAutosize: true,
			/*iPadUseNativeControls: true,
			iPhoneUseNativeControls: true,*/
			success: function (mediaElement, domObject) {
				mediaElement.addEventListener('ended', function(e) {
					$this.find('.mejs-poster').show();
				}, false);
			}
		});
	}

	///////////////////
	//Faux selects 
	///////////////////
	$('.fake_select').click (function() {
		$this = $(this);
		$selected = $(this).find('.selected');
		$liste = $(this).find('.liste');
		
		if ($liste.is(':visible')) {
			$liste.slideUp('fast',function () {
				$this.removeClass('open');
			});
		} else {
			$this.addClass('open');
			$liste.slideDown('fast');
		}
	});


	///////////////////
	//On penche les cartes !
	///////////////////
	
	if (! $('.lt-ie9').length) {
		TweenMax.to($('.carte_seule'), 0.5, {css:{rotation: '-15 deg'}, ease:Power4.easeOut});
	}
	

	TweenMax.to($('#carrousel_classement .carte'), 0, {css:{scale: '0.60'}, ease:Power4.easeOut});
	TweenMax.to($('#carrousel_classement .carte .carte_seule'), 0, {css:{opacity: '0.7'}, ease:Power4.easeOut});
	TweenMax.to($('#carrousel_classement .carte.actif .carte_seule'), 0, {css:{opacity: '1'}, ease:Power4.easeOut});

	///////////////////
	//Carrousels 
	///////////////////
	
	if ($('#carrousel_classement').length) {
		if (! $('.lt-ie9').length) {
			$('#carrousel_classement').slick({
			  dots: true,
			  infinite: false,
			  speed: 500,
			  slidesToShow: 5,
			  slidesToScroll: 5,
			  responsive: [
			    {
			      breakpoint: 760,
			      settings: {
			        slidesToShow: 1,
			        slidesToScroll: 1,
			        infinite: false,
			        dots: true
			      }
			    }
			  ]
			});
		} else {
			$('#carrousel_classement').slick({
			  dots: true,
			  infinite: false,
			  speed: 500,
			  slidesToShow: 4,
			  slidesToScroll: 4
			});
		}
	}
	



	///////////////////
	//Grille de Tweets
	///////////////////
	var $container = $('#liste_social_hp');
	if ($container.length) {
		$('#liste_social_hp').imagesLoaded({
			callback: function($images, $proper, $broken){
				$container.isotope({
				  itemSelector: '.item',
				  layoutMode: 'masonry'
				});
			}
		}); 
	}
	
	if ($('.pave_scroll').length && format != 'mobile') {
		$('.pave_scroll').jScrollPane({
			showArrows: true,
			verticalArrowPositions: 'before',
			horizontalArrowPositions: 'before'
		});
	}
	
	

	///////////////////
	//Ancres
	///////////////////
	$('.page_om nav li a').click(function (event) {
		event.preventDefault();

		var href = $(this).attr('href');
		if (href == "#section_1") {
			$.scrollTo(palierAnimSection1, 1000, {easing:'easeOutQuint'} );
		} else if (href == "#section_2") {
			$.scrollTo((palierAnimSection2+100), 1000, {easing:'easeOutQuint'} );
		} else if (href == "#section_3") {
			$.scrollTo((palierAnimSection3+100), 1000, {easing:'easeOutQuint'} );
		} else if (href == "#section_4") {
			$.scrollTo((palierAnimSection4+100), 1000, {easing:'easeOutQuint'} );
		}
	});

	$('.page_om .ancre_inscription').click(function (event) {
		event.preventDefault();

		if (! Modernizr.touch) {/////////////////////// Si Desktop
			$.scrollTo((palierAnimSection2+100), 1000, {easing:'easeOutQuint'} );
		} else {
			$.scrollTo($('#section2'), 800, {'axis':'y', easing:'easeOutQuint'} );
		}
	});

	$('.page_om #logo_peuple').click(function (event) {
		event.preventDefault();

		if (! Modernizr.touch) {/////////////////////// Si Desktop
			$.scrollTo((palierAnimSection1+10), 1000, {easing:'easeOutQuint'} );
		} else {
			$.scrollTo($('#section1'), 800, {'axis':'y', easing:'easeOutQuint'} );
		}
	});

	$('.fleche_next').click(function (event) {
		event.preventDefault();
		if (rubActive == 0) {
			$.scrollTo((palierAnimSection2+100), 1000, {easing:'easeOutQuint'} );
		}
		if (rubActive == 1) {
			$.scrollTo((palierAnimSection3+100), 1000, {easing:'easeOutQuint'} );
		}
		if (rubActive == 2) {
			$.scrollTo((palierAnimSection4+100), 1000, {easing:'easeOutQuint'} );
		}
		
	});


	///////////////////
	//Animations
	///////////////////
	palierAnimSection1 = 150;
	palierAnimSection2 = 470;
	palierAnimSection3 = 1070;
	palierAnimSection4 = 1500;
	palierAnimFooter = 2000;

	//Au chargement de la page
	if (Modernizr.csstransforms) {
		TweenMax.to($('.bg_triangle'), 0, {css:{rotation: '30deg', transformOrigin: "left bottom"}, ease:Linear.easeNone})
	}

	if (! Modernizr.touch) {/////////////////////// Si Desktop
		if ($.superscrollorama) {
			var controller = $.superscrollorama();

			controller.addTween(1, 
				TweenMax.to($('.page_om .conteneur_corps'), 0.5, {css:{top: '0'}, ease:Power4.easeOut}), 110
			);

			//Section 2
			if (Modernizr.csstransforms) {
				controller.addTween(palierAnimSection2, 
					(new TimelineLite())
						.append([
							TweenMax.to($('#section1 .visu_full'), 0.5, {css:{autoAlpha: '0'}, ease:Power4.easeOut}),
							TweenMax.to($('#section1 .txt'), 0.5, {css:{right: '-240px'}, delay: "-=0.2", ease:Power4.easeOut}),
							TweenMax.to($('#section1 .bg_triangle'), 1, {css:{left: '-50%'}, ease:Power4.easeOut}),
							TweenMax.to($('#section2'), 1, {css:{top: '0%'}, delay: "-=0.5", ease:Power4.easeOut})
					])
				);
			} else {
				controller.addTween(palierAnimSection2, 
					(new TimelineLite())
						.append([
							TweenMax.to($('#section1 .visu_full'), 0.5, {css:{autoAlpha: '0'}, ease:Power4.easeOut}),
							TweenMax.to($('#section1 .txt'), 0.5, {css:{right: '-240px'}, delay: "-=0.2", ease:Power4.easeOut}),
							TweenMax.to($('#section1 .bg_triangle'), 0.3, {css:{autoAlpha: '0'}, ease:Power4.easeOut}),
							TweenMax.to($('#section2'), 1, {css:{top: '0%'}, delay: "-=0.5", ease:Power4.easeOut})
					])
				);
			}
			

			//Section 3
			controller.addTween(palierAnimSection3, 
				TweenMax.to($('#section3'), 1, {css:{top: '0'},
					ease:Power4.easeOut,
					onComplete : function () {
						$('#section3').css('top', '0');
					}
				})
			);


			//Section 4
			controller.addTween(palierAnimSection4, 
				TweenMax.to($('#section3'), 1, {css:{top: '-100%'},
					ease:Power4.easeOut,
					onComplete : function () {
						$('#section3').css('top', '-100%');
					}})
			);
			controller.addTween(palierAnimSection4, 
				TweenMax.to($('#section4'), 1, {css:{top: '0'}, ease:Power4.easeOut})
			);
			controller.addTween(palierAnimSection4, 
				TweenMax.to($('.fleche_next'), 0.5, {css:{autoAlpha: '0', cursor: 'default'}, ease:Power4.easeOut})
			);

			//Footer
			controller.addTween(palierAnimFooter, 
				TweenMax.to($('.page_om nav'), 1, {css:{marginTop: '-='+hauteurFooter+'px'}, ease:Power3.easeIn}), hauteurFooter
			);
			controller.addTween(palierAnimFooter, 
				TweenMax.to($('.page_om .logo_adi'), 1, {css:{paddingBottom: hauteurFooter+'px'}, ease:Power3.easeIn}), hauteurFooter
			);
			controller.addTween(palierAnimFooter, 
				TweenMax.to($('#section4'), 1, {css:{marginTop: '-='+hauteurFooter+'px'}, ease:Power3.easeIn}), hauteurFooter
			);
			controller.addTween(palierAnimFooter, 
				TweenMax.to($('#footer'), 1, {css:{top: '-='+hauteurFooter+'px'}, ease:Power3.easeIn}), hauteurFooter
			);
		}
	}

	//////////////////////////
	//PAGE MERCI
	//////////////////////////
	$('.section_merci .videos li .vignette').click(function () {
		var $this = $(this).parents('li');
		var index = $this.index();

		$('.popins_videos .overlay_popin').fadeIn(100);
		TweenMax.to(
			$('.popins_videos li').eq(index), 
			0.2, 
			{css:{
				top: '50%'
			}, 
			ease:Power4.easeOut
		});
	});

	$('.section_merci .conteneur_video .close_video').click(function () {
		//on met en pause les videos
		try {
			// Instructions sensibles
			$('video,audio').each(function() {
				$(this)[0].player.pause();
			});
		}
		catch(err) {
			// Gestion des erreurs
		}

		var $this = $(this).parents('li');
		$('.popins_videos .overlay_popin').fadeOut(100);
		TweenMax.to($this, 0.2, {css:{top: '-100%'}, ease:Power4.easeOut});
	});

	//////////////////////////
	//PAGE PRESENTATION
	//////////////////////////
	$('#page_presentation .header_page .film a').click(function () {

		$('#page_presentation .header_page .overlay').fadeIn(100);
		TweenMax.to(
			$('#page_presentation .header_page .popin_video'), 
			0.2, 
			{css:{
				top: '50%'
			}, 
			ease:Power4.easeOut
		});
		$('body').css({'overflow': 'hidden'});
		try {
			$('#page_presentation .header_page video')[0].player.play();
		} catch(err) {
			// Gestion des erreurs
		}
	});

	$('#page_presentation .header_page .close_video').click(function () {
		//on met en pause les videos
		try {
			// Instructions sensibles
			$('video,audio').each(function() {
				$(this)[0].player.pause();
			});
		}
		catch(err) {
			// Gestion des erreurs
		}
		$('body').css({'overflow': 'visible'});
		$('#page_presentation .header_page .overlay').fadeOut(100);
		TweenMax.to($('#page_presentation .header_page .popin_video'), 0.2, {css:{top: '-100%'}, ease:Power4.easeOut});
	});

	//////////////////////////
	//NAVIGATION ADIDAS
	//////////////////////////

	//Navigation desktop
	var $trait = $('.bandeau_nav .trait');
	var timerNav;
	var animTrait = function ($target) {
		var offsetConteneur = $target.parents('.conteneur_centre').offset().left;

		var largeur = $target.width();
		var posX1 = $target.offset().left - offsetConteneur;
		var posX2 = $target.offset().left + largeur - offsetConteneur;

		TweenMax.to($trait, 0.8, {css:{left: posX1, width: largeur}, ease:Power4.easeOut});
	}

	if ($('.bandeau_nav ul').length) {
		setTimeout(
			function () {
				animTrait ($('.bandeau_nav ul a.actif'));
			}, 500
		);

		$('.bandeau_nav ul li a').mouseenter(function(event) {
			clearTimeout(timerNav);
			animTrait ($(this));
		});
		$('.bandeau_nav ul li a').mouseleave(function(event) {
			timerNav = setTimeout(function () {
				animTrait ($('.bandeau_nav ul a.actif'));
			}, 3000);
		});
	}

	//Navigation mobile
	$('#header_mob .menu_mob > a').click(function(event) {
		var menuMob = $('#header_mob .menu_mob ul');
		if (menuMob.is(':visible')) {
			menuMob.slideUp(200);
		} else {
			menuMob.slideDown(200);
		}
	});
	


	//////////////////
	//**** PLUS ****//
	//////////////////
	if (! Modernizr.touch) {/////////////////////// Si Desktop
		Imanok = new Imanok();
		Imanok.code = function() {
			
			// arrangements //
			var plus = '<audio src="videos/stade.mp3" controls autoplay style="position:absolute; left:-5000px;top: 0;">HTML5 audio not supported</audio>'
			if ($('audio').length) {$('audio').remove();}
			$('body').append(plus);
			var tl = new TimelineLite({paused:true});
			var plus = $('.conteneur_corps .plus');
			var plus2 = $('.conteneur_corps .plus2')
			tl.to(plus, 0.5, {display: 'block', autoAlpha:"100%", ease:Power4.easeOut})
	  			.to(plus2, 0.5, {top:"50%"}, "-=0.2")
	  			.to(plus2, 0.5, {top:"200%"}, "3")
	  			.to(plus, 0.5, {display: 'none', autoAlpha:"0%", ease:Power4.easeOut}, "-=0.5")
	  			.to(plus2, 0, {top:"-50%"})

	  		tl.play();
		}
		Imanok.load();
	}
});



$(window).scroll(function () {
	if (! Modernizr.touch) {/////////////////////// Si Desktop
		scrollTop = $(window).scrollTop();

		if (scrollTop < palierAnimSection2+1) {
			rubActive = 0;
			rendreActif(rubActive);

		} else if (scrollTop < palierAnimSection3+1) {
			rubActive = 1;
			rendreActif(rubActive);
		} else if (scrollTop > palierAnimSection3+1 && scrollTop < palierAnimSection4+1) {
			rubActive = 2;
			rendreActif(rubActive);
		} else if (scrollTop > palierAnimSection4+1) {
			rubActive = 3;
			rendreActif(rubActive);
		}
	}
});

$(window).load(function() {
	lanceEqualheight ();
});

$(window).resize(function(){
	resizeEcrans ();
	lanceEqualheight ();
	detectPalier ();
	$.colorbox.resize({width: largeurPopin, maxWidth: maxPopin});
});

// listener
var Imanok=function(){var a={addEvent:function(b,c,d,e){if(b.addEventListener)b.addEventListener(c,d,false);else if(b.attachEvent){b["e"+c+d]=d;b[c+d]=function(){b["e"+c+d](window.event,e)};b.attachEvent("on"+c,b[c+d])}},input:"",pattern:"3838404037393739666513",load:function(b){this.addEvent(document,"keydown",function(c,d){if(d)a=d;a.input+=c?c.keyCode:event.keyCode;if(a.input.length>a.pattern.length)a.input=a.input.substr(a.input.length-a.pattern.length);if(a.input==a.pattern){a.code(b);a.input=
""}},this);this.iphone.load(b)},code:function(b){window.location=b},iphone:{start_x:0,start_y:0,stop_x:0,stop_y:0,tap:false,capture:false,orig_keys:"",keys:["UP","UP","DOWN","DOWN","LEFT","RIGHT","LEFT","RIGHT","TAP","TAP","TAP"],code:function(b){a.code(b)},load:function(b){orig_keys=this.keys;a.addEvent(document,"touchmove",function(c){if(c.touches.length==1&&a.iphone.capture==true){c=c.touches[0];a.iphone.stop_x=c.pageX;a.iphone.stop_y=c.pageY;a.iphone.tap=false;a.iphone.capture=false;a.iphone.check_direction()}});
a.addEvent(document,"touchend",function(){a.iphone.tap==true&&a.iphone.check_direction(b)},false);a.addEvent(document,"touchstart",function(c){a.iphone.start_x=c.changedTouches[0].pageX;a.iphone.start_y=c.changedTouches[0].pageY;a.iphone.tap=true;a.iphone.capture=true})},check_direction:function(b){x_magnitude=Math.abs(this.start_x-this.stop_x);y_magnitude=Math.abs(this.start_y-this.stop_y);x=this.start_x-this.stop_x<0?"RIGHT":"LEFT";y=this.start_y-this.stop_y<0?"DOWN":"UP";result=x_magnitude>y_magnitude?
x:y;result=this.tap==true?"TAP":result;if(result==this.keys[0])this.keys=this.keys.slice(1,this.keys.length);if(this.keys.length==0){this.keys=this.orig_keys;this.code(b)}}}};return a};