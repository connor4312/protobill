define [
	'application/BaseClasses'
], (BaseClasses) ->

	class PermissionModel extends BaseClasses.Model
		defaults: {
			name: null,
			display_name: null
		}
		url: window.baseurl + '/api/permission'
		
	return PermissionModel