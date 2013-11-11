define [
	'jquery'
	'application/BaseClasses'
], ($, BaseClasses) ->
	describe "Base view testing", () ->

		View = null
		$container = $('<div />');

		beforeEach () ->
			class TestView extends BaseClasses.ViewFX
				$el: $container
				template: "admin/login.html"

			View = new TestView

		it "binds correctly", () ->
			View.bind $('<div />'), 'click', () ->
			expect(View.binding.length).toEqual(1)

		it "renders", () ->
			View.render null, null, $container
			expect($container.html()).not.toEqual('')

			expect(View.binding.length).toEqual(1)

		it "nests and destroys", () ->

			done = false
			foo = { destroy: () -> }
			spyOn(foo, 'destroy');

			runs () ->
				View.nestView 'application/ErrorMessageView', (view) ->
					done = true
					return foo

				waitsFor(
					() -> done,
					'View loaded',
					1000
				)
			runs () ->
				expect(View.nestedViews.length).toEqual(1)

				View.render null, null, $container
				View.destroy()

				expect(foo.destroy).toHaveBeenCalled()
				expect($container.html()).not.toEqual('')
				expect(View.binding.length).toEqual(0)