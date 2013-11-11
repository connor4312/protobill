define [
	'jquery'
	'underscore'
	'backbone'
	'templates'
	'support/router'
	'backbonevalidation'
], ($, _, Backbone, Template, Router, Socket) ->
	class BaseView extends Backbone.View
		constructor: ->
			@nestedViews = []
			@binding = []
			Backbone.View.prototype.constructor.apply this, arguments;

		loadTemplate: (url, params = {}) ->
			path = url
			return Template['src/template/' + path] params

		nestView: (view, func)->
			require [view], (view) =>
				@nestedViews.push func(view)

		destroy: =>
			view.destroy() for view in @nestedViews
			e.off() for e in @binding
			@binding = []
			$(@el).empty()
			@remove()

		renderTemplate: (params = {}, template = @template, element = @el) ->

			$(element).html @loadTemplate(template, params)
			@bind 'a[href^="!"]', 'click', (e) ->
				e.preventDefault()
				Router().navigate $(@).attr('href').replace(/!\/?/g, ''), true
			return this

		render: (params = {}, template = @template, $element = @el) ->
			@renderTemplate params, template, $element
			@afterRender params, template, $element
			return this

		afterRender: (params, template, $element) ->

		bind: (elem, args...) ->
			if elem instanceof $
				$el = elem
			else 
				$el = $(elem, @el)

			$el.on.apply $el, args
			@binding.push $el

		showDialog: (view, params = {}) ->
			@nestView 'application/DialogBoxView', (DialogView) ->
				return new DialogView {
					view: view
					func: (container, ContentView, callback) ->
						new ContentView { container: container, params: params, callback: callback }
				}

		handleResponse: (data) ->

		handleError: (data) =>

	class ViewFX extends BaseView
		centerElements: ($element = @el) ->
			center = () ->
				$elem = $('.js-center', $element)
				width = $elem.attr('data-width') or $elem.width()
				left = Math.round(width/-2)
				top = Math.round($elem.height()/-2)
				$elem.css {
					'margin-left': left + 'px'
					'margin-top': top + 'px'
					'width': width
				}
			@bind window, 'resize', center
			center()
		animate: (elem, props, duration = 400, easing = 'swing', complete) ->
			if not complete?
				complete = () ->

			$el = $(elem)
			if $el.hasClass('js-center') and props.width?
				props['margin-left'] = Math.round(props.width/-2)
			if $el.hasClass('js-center') and props.height?
				props['margin-top'] = Math.round(props.height/-2)

			$el.stop().animate(props, duration, easing, complete)

		startLoading: ($el = @el) ->
			$('.js-loading', $el).remove()
			$loading = $('<div class="js-loading"></div>')
			$($el).append($loading)
			$loading.fadeOut(0).fadeIn(200)

		endLoading: ($el = @el) ->
			$('.js-loading', $el).stop().fadeOut 200, () ->
				@remove()

		submitForm: (e) ->
			$form = $ e.currentTarget
			e.preventDefault()
			@startLoading()
			$form.ajaxSubmit success: @handleResponse, error: @handleError

		formErrors: (validation, $form, success, fail) ->
			$('.js-help-block-error', $form).remove()
			if not validation?
				success()
			else
				for error in validation
					$item = $('input[name="' + error.attr + '"]', $form).closest '.form-group'
					$item.addClass('has-error').append('<span class="help-block js-help-block-error">' + error.message + '</span>')
				fail()
		stopPropagation: (e) ->
			e.stopPropagation()

	class DialogBoxView extends ViewFX

		initialize: (options) ->
			@el = options.container
			@cb = options.callback ? () ->
			@params = options.params

			@render()

		afterRender: () ->
			@cb()
			@load()

		load: () ->

	class Model extends Backbone.Model

		loadAttribsFrom: (form) ->
			_this = this
			$('input[name]', form).each () ->
				_this.set $(this).attr('name'), $(this).val()

	return {
		View: BaseView
		DialogBoxView: DialogBoxView
		ViewFX: ViewFX
		Model: Model
	}