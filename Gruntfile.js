module.exports = function( grunt ) {

	require( 'load-grunt-tasks' )( grunt );

	'use strict';

	// Project configuration
	grunt.initConfig( {

		pkg: grunt.file.readJSON( 'package.json' ),

		gitinfo: {},

		// Clean up build directory
		clean: {
			main: [ 'build/<%= pkg.name %>' ]
		},

		// Copy the theme into the build directory
		copy: {
			main: {
				src: [
					'**',
					'!node_modules/**',
					'!bin/**',
					'!tests/**',
					'!build/**',
					'!.git/**',
					'!Gruntfile.js',
					'!package.json',
					'!.gitignore',
					'!.gitmodules',
					'!.gitattributes',
					'!.editorconfig',
					'!**/Gruntfile.js',
					'!**/package.json',
					'!**/phpunit.xml',
					'!**/composer.lock',
					'!**/README.md',
					'!**/readme.md',
					'!**/CHANGELOG.md',
					'!**/CONTRIBUTING.md',
					'!**/travis.yml',
					'!**/*~'
				],
				dest: 'build/<%= pkg.name %>/'
			}
		},

		version: {
			readmetxt: {
				options: {
					prefix: 'Stable tag: *'
				},
				src: [ 'readme.txt' ]
			},
			tested_up_to: {
				options: {
					pkg: {
						"version": "<%= pkg.tested_up_to %>"
					},
					prefix: 'Tested up to: *'
				},
				src: [ 'readme.txt', 'readme.md' ]
			},
			requires_at_least: {
				options: {
					pkg: {
						"version": "<%= pkg.requires_at_least %>"
					},
					prefix: 'Requires at least: *'
				},
				src: [ 'readme.txt', 'readme.md' ]
			},
			plugin: {
				options: {
					prefix: 'Version: *'
				},
				src: [ 'readme.md', 'related-posts-by-taxonomy.php' ]
			},
		},

		replace: {
			replace_branch: {
				src: [ 'readme.md' ],
				overwrite: true, // overwrite matched source files
				replacements: [ {
					from: /related-posts-by-taxonomy.svg\?branch=(master|develop)/g,
					to: "related-posts-by-taxonomy.svg?branch=<%= gitinfo.local.branch.current.name %>"
				}, {
					from: /related-posts-by-taxonomy\/tree\/(master|develop)#pull-requests/g,
					to: "related-posts-by-taxonomy/tree/<%= gitinfo.local.branch.current.name %>#pull-requests"
				} ]
			}
		}

	} );

	grunt.registerTask( 'travis', [ 'gitinfo', 'replace:replace_branch' ] );

	// Creates build
	grunt.registerTask( 'build', [ 'clean:main', 'version', 'travis', 'copy:main' ] );

	grunt.util.linefeed = '\n';

};