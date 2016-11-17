module.exports = function(grunt) {
	
  // LiveReload的默认端口号
  var lrPort = 35729;
  // 使用connect-livereload模块，生成一个LiveReload脚本
  // <script src="http://127.0.0.1:35729/livereload.js?snipver=1" type="text/javascript"></script>
  var lrSnippet = require('connect-livereload')({ port: lrPort });

  var lrMiddleware = function(connect, options) {
    return [
      // 把脚本，注入到静态文件中
      lrSnippet,
      // 静态文件服务器的路径
      connect.static(options.base[0]),
      // 启用目录浏览
      connect.directory(options.base[0])
    ];
  };

  grunt.initConfig({
    // 通过connect任务，创建一个静态服务器
    connect: {
      options: {
        // 服务器端口号
        port: 8000,
        // 服务器地址(可以使用主机名localhost，也能使用IP)
        hostname: 'localhost',
        // 物理路径(默认为. 即根目录) 
        base: '.'
      },
      livereload: {
        options: {
          // 通过LiveReload脚本，让页面重新加载。
          middleware: lrMiddleware
        }
      }
    },
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
      livereload: {
        options: {
          livereload: lrPort
        },
        files: [ 
          'css/**/*.css',
          './index.html',
          'js/*.js' 
        ]
      },
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

  grunt.loadNpmTasks('grunt-contrib-connect');
  grunt.loadNpmTasks('grunt-contrib-jshint');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-cssmin');

  grunt.registerTask( 'live', [ 'connect', 'watch' ] );

};