define [
	'underscore'
	'backbone'
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
				'admin/permissions': 'showAdminPermissions'
				':foo': 'fourohfour'
			
			navto: (view) ->
				if @currentView?
					@currentView.destroy()
					
				require ['application/' + view], (view) ->
					@currentView = new view

				$('body').scrollTop 0


		router = new AppRouter

		router.on 'route:showAdminLogin', () ->
			@navto 'AdminLoginView'

		router.on 'route:showAdminDashboard', () ->
			@navto 'AdminDashboardView'

		router.on 'route:showAdminPermissions', () ->
			@navto 'AdminPermissionView'

		router.on 'route:adminDirect', () ->
			require ['application/AdminAccessFilter'], (filter) ->
				filter()

		router.on 'route:fourohfour', () ->
			console.log '404'

		Backbone.history.start({pushState: true, root: window.basepath})

		return router

	return -> return (instance = instance or init())