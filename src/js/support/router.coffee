define [
	'underscore'
	'backbonejs'
], (_, Backbone) ->

	instance = null

	init = () ->
		insertRoutes = {}
		currentView = null

		class AppRouter extends Backbone.Router
			initialize: ->
				@currentView = null

			routes:
				'admin': 'adminDirect'
				'admin/login': 'showAdminLogin'
				'admin/dashboard': 'showAdminDashboard'
			
			navto: (view) ->
				if @currentView?
					@currentView.destroy()
					
				require ['backbone/' + view], (view) ->
					@currentView = new view
					@currentView.render()

				$('body').scrollTop 0


		router = new AppRouter

		router.on 'route:showAdminLogin', () ->
			@navto 'AdminLoginView'
		router.on 'route:showAdminDashboard', () ->
			@navto 'AdminDashboardView'
		router.on 'route:adminDirect', () ->
			require ['backbone/AdminIndexController'], (controller) ->
				i = new controller
				i.initialize()
		
		Backbone.history.start({pushState: true, root: window.basepath})

		return router

	return -> return (instance = instance or init())