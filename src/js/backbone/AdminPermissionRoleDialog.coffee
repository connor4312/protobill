define [
	'jquery'
	'backbone/BaseClasses'
	'backbone/AdminPermissionRoleModel'
], ($, BaseClasses, AdminPermissionRoleModel) ->
	
	class AdminPermissionRoleDialog extends BaseClasses.DialogBoxView
		template: "admin/permissionRoleDialog.html"

		submitRole: (e) =>
			$form = $ e.currentTarget
			e.preventDefault()
			@startLoading $(@el)

			@model.loadAttribsFrom $form

			$('.form-group', $form).removeClass('has-error').remove('.js-help-block-error')

			success = () =>
				@params.addNew(@model)
				@endLoading $(@el)
			fail = () =>
				@endLoading $(@el)

			@formErrors @model.validate(), $form, success, fail

		load: () ->
			@model = new AdminPermissionRoleModel
			@bind 'form', 'submit', @submitRole

	return AdminPermissionRoleDialog
