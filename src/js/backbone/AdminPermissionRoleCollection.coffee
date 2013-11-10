define [
	'backbone/AdminPermissionRoleModel'
], (AdminPermissionRoleModel) ->

	class AdminPermissionRoleCollection extends Backbone.Collection
		model: AdminPermissionRoleModel
		url: window.baseurl + '/api/role'
		
	return AdminPermissionRoleCollection