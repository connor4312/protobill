define [
	'jquery'
	'application/BaseClasses'
], ($, BaseClasses) ->
	class ErrorMessage extends BaseClasses.View
		template: "error.html"
		initialize: (options) ->

			@el = $ '.js-error', options.container.el
			@render { level: options.level, message: options.message, dismissable: options.dismissable ? true }

		afterRender: () ->
			@bind '.close', 'click', @destroy

	return ErrorMessage