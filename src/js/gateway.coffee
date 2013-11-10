require.config 
	paths:
		jquery: ['//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min', '../lib/jquery']
		jqueryform: ['//cdnjs.cloudflare.com/ajax/libs/jquery.form/3.32/jquery.form', '../lib/jqueryform']
		underscore: ['//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min', '../lib/underscore']
		backbonejs: ['//cdnjs.cloudflare.com/ajax/libs/backbone.js/1.1.0/backbone-min', '../lib/backbone']
		fusejs: ['//cdnjs.cloudflare.com/ajax/libs/fuse.js/1.0.0/fuse.min', '../lib/fuse']
	shim:
		underscore:
			exports: '_'
		backbonejs:
			deps: ["underscore", "jquery"]
			exports: "Backbone"
		fusejs:
			exports: "Fuse"
		jqueryform: ["jquery"]
		
require ['app'], (App) ->
	App.initialize()