define [
	'jquery'
	'backbone/BaseClasses'
	'backbone/AdminPermissionCollection'
	'backbone/AdminLayoutView'
], ($, BaseClasses, Permissions) ->
	
	class view extends BaseClasses.ViewFX
		el: $ '#subpage'
		template: "admin/permission.html"

		initialize: () ->
			@permissions = new Permissions

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
