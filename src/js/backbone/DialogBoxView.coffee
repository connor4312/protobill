define [
	'jquery'
	'backbone/BaseClasses'
], ($, BaseClasses) ->

	class DialogBoxView extends BaseClasses.ViewFX

		template: "dialog.html"

		initialize: (options) ->
			@view = options.view
			@func = options.func
			@params = options.params ? {}

			@el = $("#js-message-container")

			@render { dismissable: dismissable ? true }

		afterRender: () ->
			@nestView @view, (view) => @func($('#dialog-content'), view, () => @centerElements())

			@bind '.close', 'click', () => @destroy()
			@bind '#dialog-backdrop', 'click', () => @destroy()
			@bind '#dialog-content', 'click', (e) => @stopPropagation(e)


	return DialogBoxView