define [
	'jquery'
	'application/BaseClasses'
	'application/AdminAuthUserModel'
	'support/router'
	'application/AdminLayoutView'
], ($, BaseClasses, User, Router) ->
	
	class view extends BaseClasses.ViewFX
		el: $ '#subpage'
		template: "admin/dashboard.html"

		initialize: ->
			@render()

	return view