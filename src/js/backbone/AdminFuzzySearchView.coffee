define [
	'jquery'
	'backbone/BaseClasses'
	'fusejs'
], ($, BaseClasses, Fuse) ->
	
	class AdminFuzzySearchView extends BaseClasses.View
		template: "admin/fuzzysearch.html"

		initialize: (el, collection, fields, schema) ->
			if el instanceof $
				@$el = el
			else 
				@$el = $(el)

			@collection = collection
			@fields = fields
			@collection.on 'add remove reset change', => _.debounce (=> @rebuildIndex()), 1000

			@schema = schema
			@replaces = schema.match(/{[A-z_\-]+}/g)

			@render()

		afterRender: () ->

			@rebuildIndex()
			_this = this
			@bind '.js-fuzzyinput', 'keyup', (e) ->

				if e.which is 13 then e.preventDefault()
				_this.search $(@).val()

		showResults: (results, query) ->

			content = ''
			query = query.toLowerCase();

			for result in results
				schema = @schema
				for replace in @replaces
					input = result[replace.match(/[A-z_\-]+/g)[0]].split('')
					spool = ''
					
					for letter in input
						if query.indexOf(letter.toLowerCase()) is -1
							spool += letter
						else
							spool += '<span>' + letter + '</span>'

					schema = schema.replace replace, spool
				content += '<li>' + schema + '</li>'

			$('.js-fuzzyresults', @$el).html content

		rebuildIndex: (options = {}) ->
			@_fuse = new Fuse _.pluck(@collection.models, 'attributes'), { keys: options.keys ? @fields, threshold: 0.2 }

		search: (query) ->
			@showResults @_fuse.search(query), query


	return AdminFuzzySearchView