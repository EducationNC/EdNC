'use strict';
module.exports = function(grunt) {
  // Load all tasks
  require('load-grunt-tasks')(grunt);
  // Show elapsed time
  require('time-grunt')(grunt);

  var jsFileList = [
    'assets/app/vendor/bootstrap-sass-official/assets/javascripts/bootstrap/transition.js',
    // 'assets/app/vendor/bootstrap-sass-official/assets/javascripts/bootstrap/alert.js',
    // 'assets/app/vendor/bootstrap-sass-official/assets/javascripts/bootstrap/button.js',
    'assets/app/vendor/bootstrap-sass-official/assets/javascripts/bootstrap/carousel.js',
    'assets/app/vendor/bootstrap-sass-official/assets/javascripts/bootstrap/collapse.js',
    // 'assets/app/vendor/bootstrap-sass-official/assets/javascripts/bootstrap/dropdown.js',
    'assets/app/vendor/bootstrap-sass-official/assets/javascripts/bootstrap/modal.js',
    'assets/app/vendor/bootstrap-sass-official/assets/javascripts/bootstrap/tooltip.js',
    'assets/app/vendor/bootstrap-sass-official/assets/javascripts/bootstrap/popover.js',
    // 'assets/app/vendor/bootstrap-sass-official/assets/javascripts/bootstrap/scrollspy.js',
    // 'assets/app/vendor/bootstrap-sass-official/assets/javascripts/bootstrap/tab.js',
    'assets/app/vendor/bootstrap-sass-official/assets/javascripts/bootstrap/affix.js',
    'assets/app/js/plugins/*.js',
    'assets/app/js/_*.js'
  ];

  grunt.initConfig({
    jshint: {
      options: {
        jshintrc: '.jshintrc'
      },
      all: [
        'Gruntfile.js',
        'assets/app/js/*.js',
        '!assets/app/js/scripts.js',
        '!assets/**/*.min.*'
      ]
    },
    // libsass using grunt-sass
    // sass: {
    //   dev: {
    //     options: {
    //       outputStyle: 'nested',
    //       sourceMap: true
    //     },
    //     files: {
    //       'assets/public/css/main.css': [
    //       'assets/app/sass/main.scss'
    //       ]
    //     }
    //   },
    //   build: {
    //     options: {
    //       outputStyle: 'compressed',
    //       sourcemap: false
    //     },
    //     files: {
    //       'assets/public/css/main.min.css': [
    //       'assets/app/sass/main.scss'
    //       ]
    //     }
    //   }
    // },
    // regular sass using grunt-contrib-sass
    sass: {
      dev: {
        options: {
          style: 'expanded',
          compass: false,
          // SASS source map
          // To disable, set sourcemap to false
          // https://github.com/gruntjs/grunt-contrib-sass#sourcemap
          sourcemap: 'auto'
        },
        files: {
          'assets/public/css/main.css': [
            'assets/app/sass/main.scss'
          ],
          'assets/public/css/editor-style.css': [
            'assets/app/sass/editor-style.scss'
          ]
        }
      },
      build: {
        options: {
          style: 'compressed',
          compass: false,
          // SASS source map
          // To disable, set sourcemap to false
          // https://github.com/gruntjs/grunt-contrib-sass#sourcemap
          sourcemap: 'auto'
        },
        files: {
          'assets/public/css/main.min.css': [
            'assets/app/sass/main.scss'
          ],
          'assets/public/css/editor-style.css': [
            'assets/app/sass/editor-style.scss'
          ]
        }
      },
      salsa: {
        option: {
          style: 'compressed',
          compass: false,
          sourcemap: false
        },
        files: {
          'assets/public/css/salsa.min.css': [
            'assets/app/sass/salsa.scss'
          ]
        }
      }
    },
    concat: {
      options: {
        separator: ';',
      },
      dist: {
        src: [jsFileList],
        dest: 'assets/public/js/scripts.js',
      },
    },
    uglify: {
      dist: {
        files: {
          'assets/public/js/scripts.min.js': [jsFileList]
        }
      }
    },
    autoprefixer: {
      options: {
        browsers: ['last 2 versions', 'ie 8', 'ie 9', 'android 2.3', 'android 4', 'opera 12']
      },
      dev: {
        options: {
          map: {
            prev: 'assets/public/css/'
          }
        },
        src: 'assets/public/css/main.css'
      },
      build: {
        src: 'assets/public/css/main.min.css'
      },
      salsa: {
        src: 'assets/public/css/salsa.min.css'
      }
    },
    modernizr: {
      build: {
        devFile: 'assets/app/vendor/modernizr/modernizr.js',
        outputFile: 'assets/public/js/modernizr.min.js',
        files: {
          'src': [
            ['assets/public/js/scripts.min.js'],
            ['assets/public/css/main.min.css']
          ]
        },
        uglify: true,
        parseFiles: true
      }
    },
    version: {
      default: {
        options: {
          format: true,
          length: 32,
          manifest: 'assets/manifest.json',
          querystring: {
            style: 'roots_css',
            script: 'roots_js'
          }
        },
        files: {
          'lib/scripts.php': 'assets/public/{css,js}/{main,scripts}.min.{css,js}'
        }
      }
    },
    watch: {
      sass: {
        files: [
          'assets/app/sass/*.scss',
          'assets/app/sass/**/*.scss'
        ],
        tasks: ['sass:dev', 'autoprefixer:dev']
        // tasks: ['sass:dev']
      },
      js: {
        files: [
          jsFileList,
          '<%= jshint.all %>'
        ],
        tasks: ['jshint', 'concat']
      },
      livereload: {
        // Browser live reloading
        // https://github.com/gruntjs/grunt-contrib-watch#live-reloading
        options: {
          livereload: true
        },
        files: [
          'assets/public/css/main.css',
          'assets/public/js/scripts.js',
          'templates/*.php',
          '*.php'
        ]
      }
    }
  });

  // Register tasks
  grunt.registerTask('default', [
    'dev'
  ]);
  grunt.registerTask('dev', [
    'jshint',
    'sass:dev',
    'autoprefixer:dev',
    'concat'
  ]);
  grunt.registerTask('build', [
    'jshint',
    'sass:build',
    'autoprefixer:build',
    'uglify',
    'modernizr',
    'version'
  ]);
  grunt.registerTask('salsa', [
    'sass:salsa',
    'autoprefixer:salsa'
  ]);
};
