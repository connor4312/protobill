define [
	'application/AdminPermissionModel'
], (AdminPermissionModel) ->

	class PermissionCollection extends Backbone.Collection
		searchableFields: ['name', 'display_name']
		model: AdminPermissionModel
		url: window.baseurl + '/api/permission'

		initialize: ->
			@on 'add remove reset', => @trigger 'change'
			@on 'change', =>
				_.debounce (=> @rebuildIndex()), 1000
				
		rebuildIndex: (options = {}) ->
			@_fuse = new Fuse _.pluck(@models, 'attributes'), options.keys ? @searchablefields

		search: (query) ->
			@_fuse.search query
		
	return PermissionCollection