<?php
/**
 * English language strings for the Dashboard plugin
 *
 * @package    local_cuadrodemando
 * @author     Thorvaldur Konradsson
 * @version    1.0.0
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'Dashboard';
$string['dashboard'] = 'Dashboard';
$string['dashboard:view'] = 'View dashboard';
$string['dashboard:manage'] = 'Manage dashboard';

// Navigation strings
$string['home'] = 'Home';
$string['users'] = 'Users';
$string['courses'] = 'Courses';
$string['geography'] = 'Geography';

// Page titles
$string['pagetitle'] = '{$a}';
$string['welcometodashboard'] = 'Welcome to Dashboard';

// Statistics strings
$string['totalusers'] = 'Total Users';
$string['totalcourses'] = 'Total Courses';
$string['totalenrollments'] = 'Total Enrollments';
$string['activeusers'] = 'Active Users';
$string['activeenrolments'] = 'Active Enrollments';
$string['registeredusers'] = 'Registered Users';
$string['uniqueaccesses'] = 'Unique Accesses';

// Error messages
$string['nopermission'] = 'You do not have permission to view the dashboard';
$string['missingglobalvars'] = 'Missing required global variables';

// Settings
$string['dashboardsettings'] = 'Dashboard Settings';
$string['enablecharts'] = 'Enable Charts';
$string['enablecharts_desc'] = 'Enable chart displays on the dashboard';
$string['refreshinterval'] = 'Data Refresh Interval';
$string['refreshinterval_desc'] = 'How often to refresh dashboard data (in minutes)';

// Breadcrumbs
$string['breadcrumb:home'] = 'Home';
$string['breadcrumb:users'] = 'Users';
$string['breadcrumb:courses'] = 'Courses';
$string['breadcrumb:geography'] = 'Geography';

// Language selector
$string['language_selector'] = 'Language:';
$string['lang_english'] = 'English';
$string['lang_spanish'] = 'Español';
$string['lang_icelandic'] = 'Íslenska';
$string['lang_catalan'] = 'Català';
$string['selectlanguage'] = 'Select language';

// Error pages
$string['pagenotfound'] = 'Page Not Found';
$string['pagenotfound_desc'] = 'The requested page could not be found.';
$string['returntodashboard'] = 'Return to Dashboard';

// Geography page
$string['geo'] = 'Geography';
$string['geo_instructions'] = 'Hover over each province to see its data and click for more details';
$string['provinces_total'] = 'Total Provinces';
$string['province_last_30_days'] = 'Province data for the last 30 days';
$string['sessions_last_hour'] = 'Open sessions last hour';
$string['active_users_last_hour'] = 'Active users last hour';
$string['completions_last_month'] = 'Completions last month';
$string['enrollments_last_month'] = 'Enrollments last month';
$string['registrations_last_month'] = 'New registrations last month';
$string['deletions_last_month'] = 'Deletions last month';
$string['geo_data_loading'] = 'Loading geographical data...';
$string['map_loading'] = 'Loading interactive map...';
$string['map_unavailable'] = 'Map statistics unavailable';
$string['visiblecourses'] = 'Visible courses';
$string['toggle_navigation'] = 'Toggle navigation';

// User details strings
$string['userdetails_student'] = 'Student details: {$a}';
$string['userdetails_teacher'] = 'Teacher details: {$a}';
$string['userdetails_user'] = 'User details: {$a}';
$string['users_overview'] = 'Users overview';
$string['totalusers'] = 'Total users';
$string['activeusers_month'] = 'Active users (this month)';
$string['newusers_month'] = 'New users (this month)';
$string['onlineusers'] = 'Online users';
$string['login_statistics'] = 'Login statistics';
$string['user_changes'] = 'User changes';

// User statistics strings
$string['usersaddedtoday'] = 'Users added today';
$string['usersdeletedtoday'] = 'Users deleted today';
$string['userseditedtoday'] = 'Users edited today';
$string['accessestoday'] = 'Accesses today';

// Chart labels
$string['numberofaccesses'] = '# of accesses';
$string['numberofusers'] = '# of users';

// Course details
$string['coursedetails'] = 'Course details';
$string['coursesoverview'] = 'Courses overview';
$string['coursescreated'] = 'Courses created ({$a})';
$string['coursesactive'] = 'Active courses ({$a})';
$string['coursesfinished'] = 'Finished courses ({$a})';
$string['averageenrollment'] = 'Average enrollment';
$string['coursesmostenrollmentslastyear'] = 'Courses with most enrollments last year';
$string['completed'] = 'Completed';
$string['notcompleted'] = 'Not completed';
$string['totalenrolled'] = 'Total enrolled:';
$string['completedpercentage'] = 'Completed percentage:';
$string['viewcoursedetail'] = 'View course detail';
$string['gotocourseinplatform'] = 'Go to course in platform';
$string['uniqueaccessesplatformlastyearbyday'] = 'Unique accesses to platform last year by day';
$string['accessesofuserlastyearbyday'] = 'Accesses of <b>{$a}</b> last year by day';
$string['top10provincesmostusers'] = 'Top 10 provinces with most users';
$string['person'] = 'Person';
$string['addresslabel'] = 'Address:';
$string['citylabel'] = 'City:';
$string['provincelabel'] = 'Province:';
$string['phonelabel'] = 'Phone:';
$string['emaillabel'] = 'Email:';
$string['teamslabel'] = 'Teams:';
$string['talkonteams'] = 'Talk on Teams';
$string['openchatinplatform'] = 'Open chat in platform';
$string['viewprofileinplatform'] = 'View profile in platform';
$string['provincesmorepercentusers'] = 'Provinces with more % of users';
$string['categories'] = 'Categories';
$string['times'] = 'Times';
$string['percenttotalplatformcourses'] = '% of total platform courses';
$string['gotocategoryinplatform'] = 'Go to category in platform';
$string['coursegeographyandtimes'] = 'Course geography and times';
$string['variouscourseinformation'] = 'Various course information';
$string['numberstudentsneverentered'] = '# of students who never entered';
$string['numberstudentprovinces'] = '# of student provinces';
$string['numberenrolledteachers'] = '# of enrolled teachers';
$string['numberusedresources'] = '# of used resources';
$string['completionstatus'] = 'Completion status';
$string['categoriesandtimes'] = 'Categories and times';
$string['withoutinstitution'] = 'Without institution';
$string['intotal'] = 'In total';

// Links and actions
$string['viewindashboard'] = 'View in Dashboard';
$string['viewinmoodle'] = 'View in Moodle';
$string['viewcoursedetail'] = 'View course detail';
$string['viewteacherdetail'] = 'View teacher detail';
$string['viewstudentdetail'] = 'View student detail';
$string['viewuserdetail'] = 'View detailed information';
$string['viewenrolledstudents'] = 'View enrolled students';
$string['clickhere'] = 'Click here to';
$string['clickherefor'] = 'Click here to';
$string['sendemailtoperson'] = 'Send email to person';
$string['sendemail'] = 'Send email';
$string['managecourse'] = 'Manage course';
$string['backtolist'] = 'Back to complete user list';

// Tables and listings
$string['courselist'] = 'Course list ({$a})';
$string['enrolledinacourse'] = 'Enrolled in course: <strong>{$a}</strong>';
$string['platformusers'] = 'Platform users';
$string['courselistwhereisrole'] = 'Course list where <b>{$a->fullname}</b> is {$a->role}. Total: <b>{$a->count}</b>';
$string['teacher'] = 'teacher or manager';
$string['student'] = 'student';

// Course states
$string['notstarted'] = 'Not started';
$string['finished'] = 'Finished';
$string['active'] = 'Active';
$string['noenddate'] = 'No end date';
$string['notfinished'] = 'Not finished';

// Table headers
$string['id'] = 'ID';
$string['identification'] = 'Identification';
$string['fullname'] = 'Full name';
$string['shortname'] = 'Short name';
$string['name'] = 'Name';
$string['email'] = 'Email';
$string['city'] = 'City';
$string['department'] = 'Department';
$string['province'] = 'Province';
$string['address'] = 'Address';
$string['teachers'] = 'Teacher(s)';
$string['students_count'] = '# of students';
$string['completed_count'] = '# Completed';
$string['completed_percent'] = '% Completed';
$string['status'] = 'Status';
$string['manageinmoodle'] = 'Manage in Moodle';
$string['coursestartdate'] = 'Course start date';
$string['courseenddate'] = 'Course end date';
$string['completiondate'] = 'Completion date';
$string['enrollmentdate'] = 'Enrollment date';
$string['coursefinished'] = 'Course finished';
$string['user'] = 'User';

// Messages
$string['noactiveusers'] = 'No active users';
$string['noenrolled'] = '0 enrolled';
$string['noopensessions'] = 'No open sessions';
$string['notcompleted'] = 'Not completed';

// Navigation and titles  
$string['navigateyeardata'] = 'Navigate year data';
$string['viewstatisticsof'] = 'View statistics of {$a->month} {$a->year}';
$string['back'] = 'Back';
$string['viewdetail'] = 'View detail';
$string['collapse'] = 'Collapse';
$string['remove'] = 'Remove';

// Dates
$string['startdate'] = 'Start date';
$string['enddate'] = 'End date';
$string['enrolldate'] = 'Enrollment date';
$string['completiondate'] = 'Completion date';
$string['coursestartdate'] = 'Course start date';
$string['courseenddate'] = 'Course end date';

// Button actions
$string['viewcourse'] = 'View course';
$string['configurecourse'] = 'Configure course';
$string['viewenrolled'] = 'View enrolled';
$string['totalcourses'] = 'Total Courses';
$string['totalenrollments'] = 'Total Enrollments';
$string['activeusers'] = 'Active Users';
$string['activeenrolments'] = 'Active enrolments';
$string['registeredusers'] = 'Registered users';
$string['uniqueaccesses'] = 'Unique accesses';

// Hardcoded strings for home.php
$string['opensessionsnow'] = 'Open sessions now:';
$string['completionsthismonth'] = 'Completions this month:';
$string['nocompletionsthismonth'] = 'No completions this month 😭';
$string['registrationsthismonth'] = 'Registrations this month:';
$string['noregistrationsthismonth'] = 'No registrations this month 😭';
$string['accessesthismonth'] = 'Accesses this month:';
$string['noaccessesthismonth'] = 'No accesses this month 😭';
$string['activeuserslasthour'] = 'Active users last hour:';
$string['noactiveusers'] = 'No active users 😭';
$string['enrollmentsthismonth'] = 'Enrollments this month:';
$string['noenrollmentsthismonth'] = 'No enrollments this month 😭';
$string['suspensionsthismonth'] = 'Suspensions this month:';
$string['nosuspensionsthismonth'] = 'No suspensions this month 😀';
$string['messagesthismonth'] = 'Messages this month:';
$string['nomessagesthismonth'] = 'No messages this month 😭';
$string['calendar'] = 'Calendar';

// DataTables strings
$string['copytable'] = 'Copy table';
$string['exportcsv'] = 'Export CSV';
$string['exportexcel'] = 'Export Excel';
$string['exportpdf'] = 'Export PDF';
$string['printtable'] = 'Print table';
$string['filtercolumns'] = 'Filter columns';
$string['showingrecords'] = 'Showing _START_ to _END_ of _TOTAL_ records';
$string['previous'] = 'Previous';
$string['first'] = 'First';
$string['last'] = 'Last';
$string['next'] = 'Next';
$string['copy'] = 'Copy';
$string['hidecolumns'] = 'Hide columns';
$string['collection'] = 'Collection';
$string['restorevisibility'] = 'Restore visibility';
$string['copykeys'] = 'Press ctrl or ⌘ + C to copy the table data to your system clipboard.<br /><br />To cancel, click this message or press escape.';
$string['copytitle'] = 'Copy to clipboard';
$string['csv'] = 'CSV';
$string['excel'] = 'Excel';
$string['showallrows'] = 'Show all rows';
$string['showrows'] = 'Show %d rows';
$string['pdf'] = 'PDF';
$string['print'] = 'Print';
$string['processing'] = 'Processing...';
$string['lengthmenu'] = 'Show _MENU_ entries per page';
$string['zerorecords'] = 'No matching records found';
$string['emptytable'] = 'No data available in table';
$string['infoempty'] = 'Showing 0 to 0 of 0 entries';
$string['infofiltered'] = '(filtered from _MAX_ total entries)';
$string['search'] = 'Search:';
$string['loadingrecords'] = 'Loading...';
$string['loadmessage'] = 'Loading search panes';
$string['showmessage'] = 'Show All';
$string['emptypanes'] = 'No search panes';
$string['title'] = 'Active Filters - %d';
$string['collapsemessage'] = 'Collapse All';
$string['clearmessage'] = 'Clear all';
$string['searchpanes'] = 'Search Panes';
$string['searchpanesplural'] = 'Search Panes (%d)';
$string['all'] = 'All';

// Month names for DataTables internationalization
$string['january'] = 'January';
$string['february'] = 'February';
$string['march'] = 'March';
$string['april'] = 'April';
$string['may'] = 'May';
$string['june'] = 'June';
$string['july'] = 'July';
$string['august'] = 'August';
$string['september'] = 'September';
$string['october'] = 'October';
$string['november'] = 'November';
$string['december'] = 'December';

// Weekday abbreviations for DataTables internationalization
$string['sunday'] = 'Sun';
$string['monday'] = 'Mon';
$string['tuesday'] = 'Tue';
$string['wednesday'] = 'Wed';
$string['thursday'] = 'Thu';
$string['friday'] = 'Fri';
$string['saturday'] = 'Sat';

// Additional missing strings
$string['copyrow'] = 'Copy 1 row to clipboard';
$string['copyrows'] = 'Copy %d rows to clipboard';
$string['averagecompletionindays'] = 'Average completion in days';
$string['completionindays'] = 'Completion in days';
$string['numbercoursesincategory'] = '# of courses in category';
