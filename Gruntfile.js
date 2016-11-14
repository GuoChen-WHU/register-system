module.exports = function(grunt) {
	
  grunt.initConfig({
    jshint: {
      beforeconcat: ['js/*.js', 'Gruntfile.js'],
      afterconcat: ['build/js/myscripts.js']
    },
    concat: {
      js: {
        src: ['js/*.js'],
        dest: 'build/js/myscripts.js'
      },
      css: {
        src: ['css/*.css'],
        dest: 'build/css/mystyles.css'
      }
    },
    watch: {
      js: {
        files: ['js/*.js'],
        tasks: ['jshint', 'uglify'],
      },
      css: {
        files: ['css/*.css'],
        tasks: ['cssmin'],
      }
    },
    uglify: {
      target: {
        files: {
          'build/js/init.min.js': ['js/init.js'],
          'build/js/confirmapply.min.js': ['js/confirmapply.js'],
          'build/js/playerform.min.js': ['js/playerform.js'],
          'build/js/refereeform.min.js': ['js/refereeform.js'],
        }
      }
    },
    cssmin: {
      target: {
        files: {
          'build/css/main.min.css': ['css/main.css'],
          'build/css/popup.min.css': ['css/popup.css'],
          'build/css/schedule.min.css': ['css/schedule.css']
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-cssmin');

};