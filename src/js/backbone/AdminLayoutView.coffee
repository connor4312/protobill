define [
	'jquery'
	'backbone/BaseClasses'
	'backbone/AdminAuthUserModel'
	'support/router'
], ($, BaseClasses, User, Router) ->
	
	class view extends BaseClasses.ViewFX
		el: $ '#page'
		template: "admin/layout.html"

		initialize: () ->
			@render()

		render: () ->
			@renderTemplate { user: User}

	return new view