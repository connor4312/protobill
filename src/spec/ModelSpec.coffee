define [
	'jquery'
	'application/BaseClasses'
], ($, Base) ->
	describe "Model specs", () ->

		model = null

		beforeEach () ->
			class TestModel extends Base.Model

			model = new TestModel
			model.set 'required', 'foo'

		it "loads attributes from form", () ->
			$form = $('<form>
				<input name="password" value="password123">
				<input name="username" value="johndoe">
			</form>');
			model.loadAttribsFrom $form

			expect(model.get('username')).toEqual('johndoe');
			expect(model.get('password')).toEqual('password123');