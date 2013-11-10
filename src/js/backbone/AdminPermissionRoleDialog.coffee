define [
	'jquery'
	'backbone/BaseClasses'
], ($, BaseClasses) ->
	
	class AdminPermissionRoleDialog extends BaseClasses.ViewFX
		template: "admin/permissionRoleDialog.html"

		initialize: (options) ->
			@el = options.container
			@cb = options.callback ? () ->
			
			@render()

		afterRender: ()->
			@cb()

	return AdminPermissionRoleDialog
