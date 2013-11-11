define [
	'jquery'
	'application/BaseClasses'
	'application/AdminAuthUserModel'
	'support/router'
], ($, BaseClasses, User, Router) ->
	
	class view extends BaseClasses.ViewFX
		el: $ '#page'
		template: "admin/login.html"

		initialize: ->
			@render()

		afterRender: ->
			@centerElements()

		events:
			'submit form': 'checkUser'

		checkUser: (e) ->
			$form = $ e.currentTarget
			e.preventDefault()
			@startLoading $('.container-body', @el)

			User.loadAttribsFrom $form

			$('.form-group', $form).removeClass('has-error').remove('.js-help-block-error')

			success = () =>
				User.authenticate () =>
						@nestView 'application/ErrorMessageView', (ErrorMessage) ->
							return new ErrorMessage { container: this, level: 'success', message: 'Logging you in...'}
						Router().navigate 'admin/dashboard', {trigger: true}
					, () =>
						@nestView 'application/ErrorMessageView', (ErrorMessage) ->
							return new ErrorMessage { container: this, level: 'danger', message: 'Invalid username or password!'}
						@endLoading $('.container-body', @el)
					, () =>
			fail = () =>
				@endLoading $('.container-body', @el)

			@formErrors User.validate(), $form, success, fail

	return view