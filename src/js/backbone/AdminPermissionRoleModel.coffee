define [
	'jquery'
	'backbone/BaseClasses'
	'support/router'
], ($, BaseClasses, Router) ->

	class AdminPermissionRoleModel extends BaseClasses.Model
		initialize: ->
			@rules =
				name: ['alpha']
	
	return new AdminPermissionRoleModel