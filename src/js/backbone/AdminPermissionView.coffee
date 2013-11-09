define [
	'jquery'
	'backbone/BaseClasses'
	'backbone/AdminPermissionCollection'
	'backbone/AdminLayoutView'
], ($, BaseClasses, Permissions) ->
	
	class view extends BaseClasses.ViewFX
		el: $ '#subpage'
		template: "admin/permission.html"

		initialize: () ->
			@permissions = new Permissions
			@permissions.fetch();
			console.log(@permissions);

		render: () ->
			debugger;
			@renderTemplate()
			@startLoading @el

	return view
