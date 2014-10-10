module.exports = function(grunt) {
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    sass: {
      options: {
        includePaths: ['assets/app/bower_components/foundation/scss']
      },
      build: {
        options: {
          outputStyle: 'compressed'
        },
        files: {
          'assets/public/css/app.css': 'assets/app/scss/app.scss'
        }
      }
    },

    autoprefixer: {
      options: {
        browsers: ['last 2 versions', 'ie 9'],
        cascade: false,

      },
      build: {
        src: 'assets/public/css/app.css'
      }
    },

    // concat: {
    //   build: {
    //     src: [
    //       'assets/app/bower_components/modernizr/modernizr.js',
    //       'assets/app/bower_components/foundation/js/foundation/foundation.js',
    //       'assets/app/js/scripts.js'
    //     ],
    //     dest: 'assets/public/js/app.js'
    //   }
    // },

    uglify: {
      build: {
        src: 'assets/public/js/app.js',
        dest: 'assets/public/js/app.min.js'
      }
    },

    imagemin: {
      build: {
        files: [{
          expand: true,
          cwd: 'assets/app/images/',
          src: ['**/*.{png,jpg,gif}'],
          dest: 'assets/public/imgs/'
        }]
      }
    },

    watch: {
      grunt: { files: ['Gruntfile.js'] },

      sass: {
        files: 'assets/app/scss/**/*.scss',
        tasks: ['sass']
      },

      autoprefixer: {
        files: 'assets/public/css/app.css',
        tasks: ['autoprefixer']
      },

      scripts: {
        files: [
          'assets/app/bower_components/*.js',
          'assets/app/js/*.js'
        ],
        tasks: ['concat', 'uglify'],
        options: {
          spawn: false
        }
      },

      images: {
          files: 'assets/app/images/*.{png,jpg,gif}',
          tasks: ['imagemin'],
          options: {
              spawn: false
          }
      }
    }
  });

  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-autoprefixer');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-imagemin');

  grunt.registerTask('dev', [
    'sass',
    'autoprefixer:dev',
    'concat'
  ]);

  grunt.registerTask('build', [
    'sass',
    'autoprefixer:build',
    'concat',
    'uglify',
    'imagemin'
  ]);

  grunt.registerTask('default', ['dev','watch']);
}
