define [
	'jquery'
	'underscore'
	'backbonejs'
	'templates'
	'support/router'
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
			e.element.unbind(e.ev) for e in @binding
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

		bind: (elem, ev, handler) ->
			if elem instanceof $
				$el = elem
			else 
				$el = $(elem, @el)

			$el.bind ev, handler
			@binding.push { element: $el, ev: ev }

		showDialog: (view, params = {}) ->
			@nestView 'backbone/DialogBoxView', (DialogView) ->
				return new DialogView {
					view: view
					func: (container, ContentView, callback) ->
						new ContentView { container: container, params: params, callback: callback }
				}

		handleResponse: (data) ->

		handleError: (data) =>

	class DialogBoxView extends BaseView

		initialize: (options) ->
			@el = options.container
			@cb = options.callback ? () ->
			
			@render()

		afterRender: ()->
			@cb()

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
			if validation.success is true
				success()
			else
				for error in validation.errors
					$item = $('input[name="' + error.attr + '"]', $form).closest '.form-group'
					$item.addClass('has-error').append('<span class="help-block js-help-block-error">' + error.message + '</span>')
				fail()
		stopPropagation: (e) ->
			e.stopPropagation()

	class Model extends Backbone.Model

		loadAttribsFrom: (form) ->
			_this = this
			$('input[name]', form).each () ->
				_this.set $(this).attr('name'), $(this).val()

		validate: (options) =>
			errors = [];
			patterns = {
				alpha: (input) ->
					if input.match(/^[A-z]+$/g)
						return true
					else
						return 'This may only contain alphabetic characters'
				alpha_dash: (input) ->
					if input.match(/^[A-z\-]+$/g)
						return true
					else
						return 'This may only contain letters and dashes'
				alpha_int: (input) ->
					if input.match(/^[A-z0-9]+$/g)
						return true
					else
						return 'This must be alphanumeric'
				alpha_num: (input) ->
					if input.match(/^[A-z0-9\.]+$/g)
						return true
					else
						return 'This must be alphanumeric'
				email: (input) ->
					if input.match(/^([a-z0-9_\.-]+)@([\da-z\.-]+)\.([a-z\.]{2,6})$/g)
						return true
					else
						return 'This is not a valid email'
				url: (input) ->
					if input.match(/^(https?:\/\/)?([\da-z\.-]+)\.([a-z\.]{2,6})([\/\w \.-]*)*\/?$/g)
						return true
					else
						return 'This must be a URL'
				integer: (input) ->
					if input.match(/^[0-9]+$/g)
						return true
					else
						return 'This must be an integer'
				numeric: (input) ->
					if input.match(/^[0-9\.]+$/g)
						return true
					else
						return 'This must be a numeric value'
				confirm: (input) ->
					if input is true or input is 'yes' or input is 1 or input is '1'
						return true
					else
						return false
					return false
				between: (input, args) ->
					if args.length is 1
						if input.length > args[0]
							return true
						else
							return 'This must longer than ' + args[0] + ' characters'
					if args.length is 2
						if input.length > args[0] and input.length < args[1]
							return true
						else
							return 'This must be between ' + args[0] + ' and ' + args[1] + ' characters'
			}
			for key, rules of @rules
				if (not key in @attributes or not @attributes[key]) and 'required' in rules
					errors.push { attr: key, message: 'This is required'}
					continue

				for rule in rules
					if _.isRegExp(rule) and not @attributes[key].match(rule)
						errors.push { attr: key, message: 'This in the wrong format'}
						continue

					parts = rule.split ':'
					name = parts[0]
					if parts.length is 2
						args = parts[1].split ','
					else
						args = []

					if patterns[name]? and @attributes[key]?
						valid = patterns[name](@attributes[key], args)
						if valid isnt true
							errors.push { attr: key, message: valid }
							continue

			success = if errors.length then false else true
			return { success: success, errors: errors }

	return {
		View: BaseView
		DialogBoxView: DialogBoxView
		ViewFX: ViewFX
		Model: Model
	}