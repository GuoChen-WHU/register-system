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
      css: {
        src: ['src/css/*.css'],
        dest: 'build/css/mystyles.css'
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
        tasks: ['jshint', 'concat:js', 'uglify']
      },
      css: {
        files: ['src/css/*.css'],
        tasks: ['concat:css', 'cssmin']
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

};