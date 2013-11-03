define [
	'jquery'
	'backbone/BaseClasses'
	'support/router'
], ($, BaseClasses, Router) ->

	class AdminAuthUserModel extends BaseClasses.Model
		initialize: ->
			@rules =
				password: ['between:3,32']
				email: ['email']

		authenticate: (onSuccess, onFail, always) ->
			$.ajax
				url: window.baseurl + '/api/authenticate'
				data:
					email: @get('email')
					password: @get('password')
				dataType: 'json'
				type: 'POST'
				success: (data) =>
					@set data
					onSuccess()
				error: onFail
				always: always
	
	return new AdminAuthUserModel