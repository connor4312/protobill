define [
	'jquery'
	'backbone/BaseClasses'
], ($, BaseClasses) ->

	class DialogBoxView extends BaseClasses.ViewFX

		template: "dialog.html"

		initialize: (options) ->
			console.log 'Its alive!'
			@view = options.view
			@func = options.func
			@params = options.params ? {}

			@el = $("#js-message-container")

			@render { dismissable: dismissable ? true }

		afterRender: () ->
			@nestView @view, (view) => @func($('#dialog-content'), view, () => @centerElements())

		events:
			'click .close': 'destroy'
			'click #dialog-backdrop': 'destroy'
			'click #dialog-content': 'stopPropagation'


	return DialogBoxView