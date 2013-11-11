define [
	'jquery'
	'application/AdminLoginView'
], ($, LoginView) ->
	describe "Login Page", () ->
		view = new LoginView
		$container = $('<div />');

		it "shows the view", () ->
			view.render null, null, $container
			expect($('#signupform', $container).length).toEqual(1);