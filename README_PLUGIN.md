# Dashboard Plugin for Moodle

## Overview
This plugin provides a comprehensive dashboard for Moodle administrators and course creators to view statistics and manage their Moodle installation.

## Installation

1. Copy the plugin files to your Moodle installation's `local/dashboard/` directory
2. Visit your Moodle site's administration area to complete the installation
3. Configure permissions as needed

## Plugin Structure

### Core Files
- `version.php` - Plugin version and metadata
- `lib.php` - Core plugin functions and navigation hooks
- `settings.php` - Admin settings configuration
- `index.php` - Main dashboard entry point

### Language Files
- `lang/en/local_dashboard.php` - English language strings

### Database
- `db/access.php` - Capability definitions

### Classes
- `classes/dashboard_controller.php` - Main controller class

### Pages
- `pages/home.php` - Dashboard home page content
- Additional page files will go here

### Assets
- `styles.css` - Main plugin stylesheet
- `assets/` - Static assets (move from views/assets/)

## Migration from Original Structure

### What Changed
1. **File Structure**: Reorganized to follow Moodle plugin standards
2. **Entry Point**: New `index.php` that properly integrates with Moodle
3. **Navigation**: Uses Moodle's navigation API instead of manual routing
4. **Assets**: CSS/JS loading through Moodle's `$PAGE->requires` API
5. **Permissions**: Proper capability system implementation

### Next Steps
1. **Move Assets**: Copy your `views/assets/` content to new structure
2. **Convert Pages**: Migrate your existing page files to new format
3. **Update Database Calls**: Ensure all database queries use Moodle's `$DB` object
4. **Test Navigation**: Verify all links work with new structure

## Capabilities

- `local/dashboard:view` - View the dashboard (granted to teachers, course creators, managers)
- `local/dashboard:manage` - Manage dashboard settings (granted to managers only)

## Configuration

After installation, configure the plugin in:
`Site Administration > Plugins > Local plugins > Dashboard`

Available settings:
- Enable/disable charts
- Set data refresh interval

## Usage

Access the dashboard through:
1. Main navigation (if user has appropriate permissions)
2. Site administration menu
3. Direct URL: `/local/dashboard/index.php`

## Development Notes

- All code follows Moodle coding standards
- Uses proper Moodle APIs for database access, navigation, and output
- Includes proper capability checks and security measures
- Language strings are properly externalized

## License

GPL v3 or later
