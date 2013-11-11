define [
	'jquery'
	'underscore'
	'backbone/BaseClasses'
	'backbone/AdminPermissionRoleCollection'
	'backbone/AdminPermissionCollection'
	'backbone/AdminPermissionRoleModel'
	'backbone/AdminLayoutView'
], ($, _, BaseClasses, PermissionRoleCollection, PermissionCollection, AdminPermissionRoleModel) ->
	
	class view extends BaseClasses.ViewFX
		el: $ '#subpage'
		template: "admin/permission.html"

		initialize: () ->

			@permissions = new PermissionCollection
			@roles = new PermissionRoleCollection

			@startLoading()

			render = () =>

				@render { roles: @roles }
				@renderList()
				fuzzybox = $('#js-fuzzypermsearch', @el)
				@nestView 'backbone/AdminFuzzySearchView', (view) =>
					view = new view fuzzybox, @permissions, ['name', 'display_name'], '{name}: <b>{display_name}</b>'
					@endLoading()
					return view;

			@roles.fetch { success: @renderList }
			@permissions.fetch { success: render }

			@roles.on 'add', @renderList

		renderList: () =>

			content = ''
			@roles.forEach (value) ->
				content += '<li data-id="' + value.get('id') + '">' + value.get('name') + '</li>'

			$('#js-roles', @el).html content

			if role = @roles.at(@roles.length - 1)
				@selectRole role.get('id')
		
		events:
			'click #js-addrole': 'displaySelectionBox'
			'click #js-roles li': 'chooseRole'

		displaySelectionBox: () ->
			@showDialog 'backbone/AdminPermissionRoleDialog', {
				addNew: (role) =>
					role.save()
					@roles.add role
			}

		chooseRole: (e) =>
			id = $(e.target).attr 'data-id'
			@selectRole id

		selectRole: (id) ->

			role = @roles.get id

			$('#js-roles li').removeClass 'active'
			$('#js-roles li[data-id="' + id + '"]').addClass 'active'

			$('#js-role-name').html role.get 'name'

			content = '';
			for permission in role.get('permissions')
				p = @permissions.get permission
				content += p.get('name') + ': <b>' + p.get('display_name') + '</b>'

			$('#js-fuzzypermsearch .js-fuzzystatic').html content

	return view
