define [
	'jquery'
	'backbone/BaseClasses'
	'backbone/AdminPermissionCollection'
	'backbone/AdminPermissionRoleCollection'
	'backbone/AdminLayoutView'
], ($, BaseClasses, PermissionRoleCollection, PermissionCollection) ->
	
	class view extends BaseClasses.ViewFX
		el: $ '#subpage'
		template: "admin/permission.html"

		initialize: () ->
			@permissions = new PermissionCollection
			@roles = new PermissionRoleCollection
			@roles.fetch
				success: =>
					@render { roles: @roles }

		render: () ->
			@renderTemplate()
			@startLoading()
			fuzzybox = $('#js-fuzzypermsearch', @el)
			@permissions.fetch
				success: =>
					@endLoading()
					@nestView 'backbone/AdminFuzzySearchView', (view) =>
						new view fuzzybox, @permissions, ['name', 'display_name'], '{name}: <b>{display_name}</b>'

		events:
			'click #js-addrole': 'displaySelectionBox'

		displaySelectionBox: () ->
			@showDialog 'backbone/AdminPermissionRoleDialog'

	return view
