define [
	'jquery'
	'backbone/BaseClasses'
], ($, BaseClasses) ->
	describe "Base view testing", () ->

		$container = $('<div />');
		class TestView extends BaseClasses.ViewFX
			$el: $container
			template: "admin/login.html"

		View = new TestView

		it "renders", () ->
			View.render null, null, $container
			expect($container.html()).not.toEqual('')

			expect(View.binding.length).toEqual(1)
