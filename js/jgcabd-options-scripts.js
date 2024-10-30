jQuery(document).ready(function() {

	jQuery(".jgcabd_content_tab").hide(); //Ocultar capas
	jQuery("h2.nav-tab-wrapper a:first").addClass("nav-tab-active");
	jQuery(".jgcabd_content_tab:first").show();

	jQuery("h2.nav-tab-wrapper a").click(function() {
		var tab_activa = jQuery(this).attr('href');

		jQuery("h2.nav-tab-wrapper a").removeClass("nav-tab-active");
		jQuery(this).addClass("nav-tab-active");
		jQuery(".jgcabd_content_tab").hide();
		jQuery(tab_activa).show();

		return false;
	});

});
