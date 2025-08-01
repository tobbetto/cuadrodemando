# Asset Structure Documentation

## Overview
This document describes the asset structure for the Dashboard Moodle plugin following Moodle's recommended practices.

## Directory Structure

```
local/dashboard/
├── amd/                          # AMD JavaScript modules
│   ├── src/                      # Source JavaScript files
│   │   ├── dashboard.js          # Main dashboard functionality
│   │   └── charts.js             # Chart-related functionality
│   └── build/                    # Minified/built JavaScript files
│       ├── dashboard.min.js      # Built main dashboard module
│       └── charts.min.js         # Built charts module
├── thirdpartylibs/               # Third-party libraries
│   ├── adminlte/                 # AdminLTE theme files
│   │   ├── adminlte.min.css
│   │   └── adminlte.min.js
│   ├── chart/                    # Chart.js library
│   │   ├── chart.js
│   │   └── chart.umd.js
│   ├── datatables/               # DataTables library
│   │   ├── datatables.min.css
│   │   └── datatables.min.js
│   └── fontawesome/              # Font Awesome icons
├── pix/                          # Plugin-specific images
├── styles.css                    # Main plugin stylesheet
├── thirdpartylibs.xml           # Third-party library declarations
├── Gruntfile.js                 # Build configuration
└── package.json                 # Node.js dependencies
```

## AMD Modules

### dashboard.js
Main dashboard functionality including:
- Event binding for navigation
- Data refresh functionality
- Statistics updates
- Chart initialization

Usage in PHP:
```php
$PAGE->requires->js_call_amd('local_cuadrodemando/dashboard', 'init');
```

### charts.js
Chart-specific functionality:
- User activity charts
- Course statistics charts
- Geographic distribution charts

Usage in PHP:
```php
$PAGE->requires->js_call_amd('local_cuadrodemando/charts', 'init');
```

## CSS Structure

### Main Stylesheet (styles.css)
Contains:
- Dashboard-specific styling
- Responsive design rules
- Dark mode support
- Moodle theme integration

### Loading CSS in PHP
```php
$PAGE->requires->css('/local/dashboard/styles.css');
```

## Third-Party Libraries

### AdminLTE
- **Version**: 3.2.0
- **License**: MIT
- **Files**: adminlte.min.css, adminlte.min.js
- **Purpose**: Dashboard theme and components

### Chart.js
- **Version**: 4.4.0
- **License**: MIT
- **Files**: chart.js, chart.umd.js
- **Purpose**: Data visualization charts

### DataTables
- **Version**: 1.13.6
- **License**: MIT
- **Files**: datatables.min.css, datatables.min.js
- **Purpose**: Enhanced table functionality

### Font Awesome
- **Version**: 6.4.0
- **License**: Font Awesome Free License
- **Purpose**: Icon library

## Build Process

### Prerequisites
```bash
npm install
```

### Building Assets
```bash
# Build all assets
npm run build

# Watch for changes during development
npm run watch
```

### Manual Building
```bash
grunt
```

## Asset Loading in Code

### Controller Implementation
```php
private static function load_assets() {
    global $PAGE;
    
    // Load main CSS
    $PAGE->requires->css('/local/dashboard/styles.css');
    
    // Load third-party CSS
    $PAGE->requires->css('/local/dashboard/thirdpartylibs/adminlte/adminlte.min.css');
    $PAGE->requires->css('/local/dashboard/thirdpartylibs/datatables/datatables.min.css');
    
    // Load third-party JS
    $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/adminlte/adminlte.min.js');
    $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/chart/chart.umd.js');
    $PAGE->requires->js('/local/cuadrodemando/thirdpartylibs/datatables/datatables.min.js');
    
    // Load AMD modules
    $PAGE->requires->js_call_amd('local_cuadrodemando/dashboard', 'init');
    
    if (get_config('local_cuadrodemando', 'enablecharts')) {
        $PAGE->requires->js_call_amd('local_cuadrodemando/charts', 'init');
    }
}
```

## Migration Notes

### From Old Structure
The old structure used direct file includes in HTML. The new structure:

1. **Moves assets** to proper Moodle directories
2. **Uses AMD modules** for JavaScript functionality
3. **Implements proper CSS loading** through Moodle's API
4. **Declares third-party libraries** in thirdpartylibs.xml
5. **Provides build tools** for development

### Benefits
- Better performance through minification
- Proper dependency management
- Moodle theme integration
- Mobile responsiveness
- Accessibility compliance
- Security improvements

## Development Workflow

1. **Modify source files** in `amd/src/`
2. **Run build process** to generate minified files
3. **Test in Moodle** environment
4. **Update version numbers** as needed
5. **Document changes** in this file

## Troubleshooting

### Common Issues
1. **JavaScript not loading**: Check AMD module paths
2. **CSS not applying**: Verify CSS file paths
3. **Third-party libs missing**: Check thirdpartylibs directory
4. **Build failures**: Ensure Node.js and Grunt are installed

### Debug Mode
To enable debug output in JavaScript:
```javascript
// Add to dashboard.js
console.log('Dashboard module loaded');
```
