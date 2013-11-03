module.exports = function(grunt) {

  grunt.initConfig({
    coffee: {
      app: {
        expand: true,
        cwd: 'src/js',
        src: ['**/*.coffee'],
        dest: '.tmp/js',
        ext: '.js'
      },
      tests: {
        expand: true,
        cwd: 'src/spec',
        src: ['**/*.coffee'],
        dest: '.tmp/spec',
        ext: '.js'
      }
    },
    uglify: {
      options: {
        banner: '/*! Built with Grunt. Copyright 2013 ioDefend, all rights reserved */\n',
        compress: false
      },
      coffee: {
        expand: true,
        cwd: '.tmp/js',
        src: ['**/*.js'],
        dest: 'public/js',
        ext: '.js'
      }
    },
    copy: {
      main: {
        expand: true,
        cwd: 'src/lib',
        dest: 'public/lib',
        src: ['**/*.js']
      },
    },
    less: {
      production: {
        options: {
          yuicompress: true
        },
        files: {
          "public/css/core.css": "src/css/core.less",
          "public/css/style.css": "src/css/style.less",
          "public/css/admin.css": "src/css/admin.less"
        }
      }
    },
    jst: {
      production: {
        options: {
          amd: true
        },
        files: {
          "public/js/templates.js": ["src/template/**/*.html"]
        }
      }
    },
    connect: {
      test : {
        options: {
          port: 8008,
          hostname: '127.0.0.1'
        }
      }
    },
    jasmine: {
      tests: {
        src: 'public/js/**/*.js',
        options: {
          specs: '.tmp/spec/**/*.js',
          host: 'http://127.0.0.1:8008/',
          template: require('grunt-template-jasmine-requirejs'),
          templateOptions: {
            requireConfigFile: 'public/js/gateway.js',
            requireConfig: {
              baseUrl: 'public/js'
            }
          }
        }
      }
    },
    clean: ['.tmp']
  });

  grunt.loadNpmTasks('grunt-contrib-coffee');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-jst');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-jasmine');
  grunt.loadNpmTasks('grunt-contrib-connect');
  grunt.loadNpmTasks('grunt-contrib-clean');

  grunt.registerTask('test', ['clean', 'connect', 'coffee:tests', 'jasmine']);
  grunt.registerTask('default', ['clean', 'less', 'coffee:app', 'uglify:*', 'jst', 'copy', 'connect', 'coffee:tests', 'jasmine']);

};