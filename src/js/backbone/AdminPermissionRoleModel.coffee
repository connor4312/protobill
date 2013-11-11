define [
	'jquery'
	'backbone/BaseClasses'
	'support/router'
], ($, BaseClasses, Router) ->

	class AdminPermissionRoleModel extends BaseClasses.Model
		url: window.baseurl + '/api/role'
		
		initialize: ->
			@rules =
				name: ['alpha']
	
	return AdminPermissionRoleModel