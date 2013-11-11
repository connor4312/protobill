define [
	'jquery'
	'application/BaseClasses'
	'support/router'
], ($, BaseClasses, Router) ->

	class AdminAuthUserModel extends BaseClasses.Model

		validation:
			password:
				required: true
				range: [3, 32]
			email:
				required: true
				pattern: 'email'
				msg: 'Please enter an email address'

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