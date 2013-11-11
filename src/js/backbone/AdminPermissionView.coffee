define [
	'jquery'
	'backbone/BaseClasses'
	'backbone/AdminPermissionRoleCollection'
	'backbone/AdminPermissionCollection'
	'backbone/AdminPermissionRoleModel'
	'backbone/AdminLayoutView'
], ($, BaseClasses, PermissionRoleCollection, PermissionCollection, AdminPermissionRoleModel) ->
	
	class view extends BaseClasses.ViewFX
		el: $ '#subpage'
		template: "admin/permission.html"

		initialize: () ->

			@permissions = new PermissionCollection

			@roles = new PermissionRoleCollection
			update = () =>
				@render { roles: @roles }

			@roles.fetch { success: update }
			@roles.on 'change add', update
					

		afterRender: () ->
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
			@showDialog 'backbone/AdminPermissionRoleDialog', {
				addNew: (role) =>
					role.save()
					@roles.add role
			}

	return view
