define [
	'jquery'
	'backbone/BaseClasses'
], ($, BaseClasses) ->
	class ErrorMessage extends BaseClasses.View
		initialize: (container, level, message, dismissable) ->

			dismissable = true if not dismissable?

			@$el = $ '.js-error', container.el
			@render("error.html", { level: level, message: message, dismissable: dismissable })

		events:
			'click .close': 'destroy'

	return ErrorMessage