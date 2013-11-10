define [
	'jquery'
	'backbone/BaseClasses'
], ($, BaseClasses) ->
	
	class AdminPermissionRoleDialog extends BaseClasses.DialogBoxView
		template: "admin/permissionRoleDialog.html"

	return AdminPermissionRoleDialog
