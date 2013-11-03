define [
	'underscore'
	'backbonejs'
], (_, Backbone) ->

	init = () ->
		insertRoutes = {}
		currentView = null

		class AppRouter extends Backbone.Router
			initialize: ->
				@currentView = null

			routes:
				'login': 'showLogin'
				'register': 'showRegister'
				'dashboard': 'showDashboard'
			
			navto: (view) ->
				if @currentView?
					@currentView.destroy()
					
				require ['backbone/' + view], (view) ->
					@currentView = new view
					@currentView.render()

				$('body').scrollTop 0


		router = new AppRouter

		router.on 'route:showLogin', () ->
			console.log 'Nav to login'
			@navto 'LoginView'

		Backbone.history.start({pushState: true, root: window.basepath})

		return router

	return -> return (instance = instance or init())