define [
	'jquery'
	'backbone'
	'support/router'
	'application/AdminAuthUserModel'
], ($, Backbone, Router, AdminAuthUserModel) ->
	
	return () ->
		$.ajax
			url: window.baseurl + '/api/authenticate/current'
			dataType: 'json'
			type: 'POST'
			success: (data) ->
				AdminAuthUserModel.set data

				if Backbone.history.fragment is 'admin'
					Router().navigate 'admin/dashboard', {trigger: true}
			error: ()-> 
				Router().navigate 'admin/login', {trigger: true}