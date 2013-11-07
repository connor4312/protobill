define [
	'jquery'
	'backbone/BaseClasses'
	'backbone/AdminAuthUserModel'
	'backbone/AdminAccessFilter'
	'support/router'
], ($, BaseClasses, User, Filter, Router) ->
	
	class view extends BaseClasses.ViewFX
		el: $ '#page'
		template: "admin/layout.html"

		initialize: () ->
			if not jasmine?
				Filter()

			@render()

			@bind $('#sidebar > ul > li'), 'click', () ->
				$('#sidebar > ul > li').removeClass('active');
				$('#sidebar > ul > li > ul').stop().slideUp(200);

				$(@).addClass('active');
				$('ul', @).stop().slideDown(200);


		render: () ->
			@renderTemplate { user: User}

	return new view