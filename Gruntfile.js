module.exports = function(grunt) {
	
  var lrPort = 35729;
  var lrSnippet = require('connect-livereload')({ port: lrPort });

  var lrMiddleware = function(connect, options) {
    return [
      lrSnippet,
      connect.static(options.base[0]),
      connect.directory(options.base[0])
    ];
  };

  grunt.initConfig({
    connect: {
      options: {
        port: 8000,
        hostname: 'localhost',
        base: '.'
      },
      livereload: {
        options: {
          middleware: lrMiddleware
        }
      }
    },
    clean: {
      files: ['build']
    },
    jshint: {
      files: ['src/js/*.js', 'Gruntfile.js']
    },
    concat: {
      js: {
        src: ['src/js/*.js'],
        dest: 'build/js/myscripts.js'
      },
      js_thr: {
        src: [ 
          'node_modules/jquery/dist/jquery.min.js',
          'node_modules/bootstrap/dist/js/bootstrap.min.js',
          'node_modules/angular/angular.min.js'
        ],
        dest: 'build/js/thrscripts.js'
      },
      css: {
        src: ['src/css/*.css'],
        dest: 'build/css/mystyles.css'
      },
      css_thr: {
        src: [
          'node_modules/octicons/build/font/octicons.min.css',
          'node_modules/bootstrap/dist/css/bootstrap.min.css'
        ],
        dest: 'build/css/thrstyles.css'
      }
    },
    watch: {
      livereload: {
        options: {
          livereload: lrPort
        },
        files: [ 
          'src/css/*.css',
          './index.html',
          'src/js/*.js' 
        ]
      },
      js: {
        files: ['src/js/*.js'],
        tasks: ['jshint']
      }
    },
    uglify: {
      target: {
        files: {
          'build/js/myscripts.min.js': ['build/js/myscripts.js']
        }
      }
    },
    cssmin: {
      target: {
        files: {
          'build/css/mystyles.min.css': ['build/css/mystyles.css']
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-contrib-connect');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-cssmin');

  grunt.registerTask( 'live', [ 'connect', 'watch' ] );
  grunt.registerTask( 'build', [ 'concat', 'uglify', 'cssmin' ]);

};