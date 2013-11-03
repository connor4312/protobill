define [
	'jquery'
	'support/router'
	'backbone/AdminAuthUserModel'
], ($, Router, AdminAuthUserModel) ->
	
	class controller
		initialize: ->
			$.ajax
				url: window.baseurl + '/api/authenticate/current'
				dataType: 'json'
				type: 'POST'
				success: (data) ->
					AdminAuthUserModel.set data
					Router().navigate 'admin/dashboard', {trigger: true}
				error: ()-> 
					Router().navigate 'admin/login', {trigger: true}

	return controller