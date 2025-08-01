/**
 * Gruntfile for local_cuadrodemando plugin
 * 
 * This file builds the AMD modules for the dashboard plugin.
 * Run 'grunt' to build all modules.
 */

module.exports = function(grunt) {
    var path = require('path'),
        cwd = process.env.PWD || process.cwd();

    // Project configuration.
    grunt.initConfig({
        uglify: {
            amd: {
                files: [{
                    expand: true,
                    src: 'amd/src/*.js',
                    rename: function(dst, src) {
                        return src.replace('src', 'build').replace('.js', '.min.js');
                    }
                }],
                options: {
                    compress: {
                        global_defs: {
                            'DEBUG': false
                        },
                        dead_code: true
                    }
                }
            }
        },

        watch: {
            amd: {
                files: ['amd/src/*.js'],
                tasks: ['uglify:amd']
            }
        }
    });

    // Load the plugins
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');

    // Default task
    grunt.registerTask('default', ['uglify:amd']);
};
