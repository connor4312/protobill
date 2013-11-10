define [
	'jquery'
	'backbone/BaseClasses'
	'backbone/AdminAuthUserModel'
	'support/router'
	'backbone/AdminLayoutView'
], ($, BaseClasses, User, Router) ->
	
	class view extends BaseClasses.ViewFX
		el: $ '#subpage'
		template: "admin/dashboard.html"

		initialize: ->
			@render()

	return view