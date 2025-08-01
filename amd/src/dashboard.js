/**
 * Dashboard main JavaScript module
 *
 * @module     local_cuadrodemando/dashboard
 * @copyright  2025 Thorvaldur Konradsson
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery', 'core/ajax', 'core/notification'], function($, Ajax, Notification) {
    'use strict';

    /**
     * Dashboard module
     */
    var Dashboard = {
        
        /**
         * Initialize the dashboard
         */
        init: function() {
            this.bindEvents();
            this.loadCharts();
            this.refreshData();
        },

        /**
         * Bind event listeners
         */
        bindEvents: function() {
            // Add click handlers for navigation
            $('.dashboard-nav a').on('click', function(e) {
                e.preventDefault();
                var target = $(this).attr('href');
                Dashboard.loadPage(target);
            });

            // Refresh button handler
            $('#refresh-dashboard').on('click', function() {
                Dashboard.refreshData();
            });
        },

        /**
         * Load charts if enabled
         */
        loadCharts: function() {
            // Check if Chart.js is available
            if (typeof Chart !== 'undefined') {
                this.initUserActivityChart();
                this.initCourseChart();
            }
        },

        /**
         * Initialize user activity chart
         */
        initUserActivityChart: function() {
            var ctx = document.getElementById('userActivityChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                        datasets: [{
                            label: 'User Logins',
                            data: [12, 19, 3, 5, 2, 3, 7],
                            borderColor: 'rgb(75, 192, 192)',
                            tension: 0.1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }
        },

        /**
         * Initialize course enrollment chart
         */
        initCourseChart: function() {
            var ctx = document.getElementById('courseChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: ['Active', 'Completed', 'Pending'],
                        datasets: [{
                            data: [300, 50, 100],
                            backgroundColor: [
                                '#28a745',
                                '#17a2b8',
                                '#ffc107'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false
                    }
                });
            }
        },

        /**
         * Refresh dashboard data
         */
        refreshData: function() {
            var promises = Ajax.call([{
                methodname: 'local_cuadrodemando_get_stats',
                args: {}
            }]);

            promises[0].done(function(data) {
                Dashboard.updateStats(data);
            }).fail(function(error) {
                Notification.exception(error);
            });
        },

        /**
         * Update statistics on the page
         * @param {Object} data Statistics data
         */
        updateStats: function(data) {
            if (data.total_users) {
                $('.stat-users').text(data.total_users);
            }
            if (data.total_courses) {
                $('.stat-courses').text(data.total_courses);
            }
            if (data.total_enrollments) {
                $('.stat-enrollments').text(data.total_enrollments);
            }
        },

        /**
         * Load a specific page via AJAX
         * @param {string} url Page URL to load
         */
        loadPage: function(url) {
            // Implementation for AJAX page loading
            window.location.href = url;
        },

        /**
         * Change dashboard language
         * @param {string} langCode Language code (en, es, is, ca)
         */
        changeLanguage: function(langCode) {
            var currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('lang', langCode);
            window.location.href = currentUrl.toString();
        }
    };

    // Make changeLanguage globally available for the select onchange event
    window.changeDashboardLanguage = Dashboard.changeLanguage;

    return Dashboard;
});
