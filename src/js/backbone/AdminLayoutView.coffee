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

		render: () ->
			@renderTemplate { user: User}

	return new view