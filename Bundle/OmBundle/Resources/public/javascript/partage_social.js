/*-----------------------------
	DOCUMENT READY
-----------------------------*/

$(document).ready(function(){
	var urlFb = $('.partage').data('url');
	var imgFb = $('meta[name="og:image"]');
	var titleFb = $('meta[name="og:title"]');
	var descFb = $('meta[name="og:description"]');

	function shareFb() {
		u=location.href;
		t=document.title;
		window.open('http://www.facebook.com/sharer.php?s=100&p[title]='+encodeURIComponent(titleFb)+'&p[url]='+encodeURIComponent(urlFb)+'&p[summary]='+encodeURIComponent(descFb)+'&p[images][0]='+encodeURIComponent(imgFb)+'','sharer','toolbar=0,status=0,width=650,height=450,left=100,top=100');
		return false;
	}
	function shareTwitt() {
		var desc = encodeURIComponent('Aux armes, marseillais ! Comme moi, rejoignez la tribu OM et venez faire le plein de cadeaux exceptionnels sur');
		ff=window.open('http://twitter.com/share?text='+desc+'&url='+url,'popup', 'width=700,height=500,left=100,top=100');
	}
	$('#lien_fb').click(function(event) {
		shareFb();
	});
	$('#lien_twitt').click(function(event) {
		shareTwitt();
	});

	
});