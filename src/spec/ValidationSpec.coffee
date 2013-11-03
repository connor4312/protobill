define [
	'jquery'
	'backbone/BaseClasses'
], ($, Base) ->
	describe "Validation", () ->

		model = null

		beforeEach () ->
			class TestModel extends Base.Model
				initialize: ->
					@rules = {
						required: ['required']
						between: ['between:5,10']
						alpha: ['alpha']
						alpha_dash: ['alpha_dash']
						alpha_int: ['alpha_int']
						alpha_num: ['alpha_num']
						url: ['url']
						integer: ['integer']
						numeric: ['numeric']
						confirm: ['confirm']
						email: ['email']
					}
			model = new TestModel
			model.set 'required', 'foo'

		it "enforces required", () ->
			model.unset 'required'
			expect(model.validate().success).toEqual(false);

		it "allows required", () ->
			expect(model.validate().success).toEqual(true);
		
		it "enforces between", () ->
			model.set 'between', 'foo'
			expect(model.validate().success).toEqual(false);
			model.set 'between', 'fooooooooooo'
			expect(model.validate().success).toEqual(false);

		it "allows between", () ->
			model.set 'between', 'foooooo'
			expect(model.validate().success).toEqual(true);
		
		it "enforces alpha", () ->
			model.set 'alpha', 'Foo!'
			expect(model.validate().success).toEqual(false);

		it "allows alpha", () ->
			model.set 'alpha', 'Foo'
			expect(model.validate().success).toEqual(true);
		
		it "enforces alpha_dash", () ->
			model.set 'alpha_dash', 'Foo!'
			expect(model.validate().success).toEqual(false);

		it "allows alpha_dash", () ->
			model.set 'alpha_dash', 'Foo-Bar'
			expect(model.validate().success).toEqual(true);
		
		it "enforces alpha_int", () ->
			model.set 'alpha_int', 'Foo!'
			expect(model.validate().success).toEqual(false);

		it "allows alpha_int", () ->
			model.set 'alpha_int', 'Foo15'
			expect(model.validate().success).toEqual(true);
		
		it "enforces alpha_num", () ->
			model.set 'alpha_num', 'Foo!'
			expect(model.validate().success).toEqual(false);

		it "allows alpha_num", () ->
			model.set 'alpha_num', 'Foo1.5'
			expect(model.validate().success).toEqual(true);
		
		it "enforces url", () ->
			model.set 'url', 'http://example .com'
			expect(model.validate().success).toEqual(false);

		it "allows url", () ->
			model.set 'url', 'http://example.com'
			expect(model.validate().success).toEqual(true);
		
		it "enforces integer", () ->
			model.set 'integer', '1.5'
			expect(model.validate().success).toEqual(false);

		it "allows integer", () ->
			model.set 'integer', '15'
			expect(model.validate().success).toEqual(true);
		
		it "enforces numeric", () ->
			model.set 'numeric', '1.5!'
			expect(model.validate().success).toEqual(false);

		it "allows numeric", () ->
			model.set 'numeric', '1.5'
			expect(model.validate().success).toEqual(true);
		
		it "enforces confirm", () ->
			model.set 'confirm', 'no'
			expect(model.validate().success).toEqual(false);

		it "allows confirm", () ->
			model.set 'confirm', 'yes'
			expect(model.validate().success).toEqual(true);
		
		it "enforces email", () ->
			model.set 'email', 'test@example'
			expect(model.validate().success).toEqual(false);

		it "allows email", () ->
			model.set 'email', 'test@example.com'
			expect(model.validate().success).toEqual(true);
