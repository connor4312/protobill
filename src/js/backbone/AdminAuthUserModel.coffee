define [
	'jquery'
	'backbone/BaseClasses'
	'support/router'
], ($, BaseClasses, Router) ->

	class AdminAuthUserModel extends BaseClasses.Model
		initialize: ->
			@rules =
				username: ['required', 'between:3,32']
				password: ['required', 'between:3,32']
				email: ['email']

		authenticate: (onSuccess, onFail, always) ->
			$.ajax
				url: window.baseurl + '/api/authenticate'
				data:
					username: @get('username')
					password: @get('password')
				dataType: 'json'
				success: (data) =>
					@set data
					onSuccess()
				fail: onFail
				always: always
	
	return new AdminAuthUserModel