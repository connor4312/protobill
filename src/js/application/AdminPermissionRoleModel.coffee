define [
	'jquery'
	'application/BaseClasses'
	'support/router'
], ($, BaseClasses, Router) ->

	class AdminPermissionRoleModel extends BaseClasses.Model
		url: window.baseurl + '/api/role'

		initialize: (options) ->
			@uniqueCheck = options.uniqueCheck
		
		validation:
			name:
				name: 'uniqueCheck'
				pattern: /^[A-z0-9]+$/g
	
	return AdminPermissionRoleModel