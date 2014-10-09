module.exports = function(grunt) {

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		jshint: {
			options: {
				jshintrc: '.jshintrc'
			},
			dist: {
				files: {
					src: [
						'assets/js/**/*.js',
						'!assets/js/**/*.min.js'
					]
				}
			}
		},

		uglify: {
			dist: {
				files: {
					'assets/js/theme.min.js': 'assets/js/theme.js'
				}
			}
		},

		scsslint: {
			dist: [
				'assets/sass/**/*.scss',
			]
		},

		sass: {
			dist: {
				options: {
					style: 'expanded',
					cacheLocation: 'assets/sass/.sass-cache'
				},
				files: {
					'assets/css/style.css': 'assets/sass/style.scss'
				}
			}
		},

		autoprefixer: {
			dist: {
				files: {
					'assets/css/style.css': 'assets/css/style.css'
				}
			}
		},

		cssmin: {
			options: {
				keepSpecialComments: 1,
				report: 'min'
			},
			dist: {
				files: {
					'assets/css/style.min.css': 'assets/css/style.css'
				}
			}
		},

		watch: {
			options: {
				livereload: true
			},
			scripts: {
				files: ['assets/js/**/*.js'],
				tasks: ['jshint', 'uglify'],
				options: {
					spawn: false,
				}
			},
			sass: {
				files: ['assets/sass/**/*.scss'],
				tasks: ['scsslint', 'sass', 'autoprefixer', 'cssmin'],
				options: {
					spawn: false,
				}
			}
		}

	});

	require('load-grunt-tasks')(grunt);

	grunt.registerTask('default', [
		'jshint',
		'uglify',
		'scsslint',
		'sass',
		'autoprefixer',
		'cssmin',
		'watch'
	]);

};
