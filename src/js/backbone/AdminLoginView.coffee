define [
	'jquery'
	'backbone/BaseClasses'
	'backbone/AdminAuthUserModel'
	'support/router'
], ($, BaseClasses, User, Router) ->
	
	class view extends BaseClasses.ViewFX
		el: $ '#page'
		template: "admin/login.html"

		afterRender: ->
			@centerElements()

		events:
			'submit form': 'checkUser'

		checkUser: (e) ->
			$form = $ e.currentTarget
			e.preventDefault()
			@startLoading()

			User.loadAttribsFrom $form

			$('.form-group', $form).removeClass('has-error').remove('.js-help-block-error')

			success = () =>
				User.authenticate () =>
						@nestView 'backbone/ErrorMessageView', (view) ->
							return new view this, 'success', 'Logging you in...'
						Router().navigate 'admin/dashboard', {trigger: true}
					, () =>
						@nestView 'backbone/ErrorMessageView', (view) ->
							return new view this, 'danger', 'Invalid username or password!'
						@endLoading()
					, () =>
			fail = () =>
				@endLoading()

			@formErrors User.validate(), $form, success, fail

	return view