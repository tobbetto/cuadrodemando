# Migration Script for Dashboard Plugin Assets
# Run this script to complete the asset migration

Write-Host "Dashboard Plugin Asset Migration Script" -ForegroundColor Green
Write-Host "=====================================" -ForegroundColor Green

$sourcePath = "c:\Users\tkonradsson\Downloads\xampp\htdocs\cuadrodemando\views\assets\scripts"
$targetPath = "c:\Users\tkonradsson\Downloads\xampp\htdocs\cuadrodemando\thirdpartylibs"

Write-Host "Source: $sourcePath" -ForegroundColor Yellow
Write-Host "Target: $targetPath" -ForegroundColor Yellow
Write-Host ""

# Copy FontAwesome
Write-Host "Copying FontAwesome..." -ForegroundColor Cyan
if (Test-Path "$sourcePath\fontawesome") {
    Copy-Item "$sourcePath\fontawesome\*" "$targetPath\fontawesome\" -Recurse -Force
    Write-Host "✓ FontAwesome copied" -ForegroundColor Green
} else {
    Write-Host "✗ FontAwesome source not found" -ForegroundColor Red
}

# Copy remaining DataTables files
Write-Host "Copying additional DataTables files..." -ForegroundColor Cyan
if (Test-Path "$sourcePath\datatables") {
    $dtFiles = @(
        "buttons.bootstrap5.min.css",
        "buttons.bootstrap5.min.js", 
        "buttons.colVis.min.js",
        "buttons.html5.min.js",
        "buttons.print.min.js",
        "jszip.min.js",
        "pdfmake.min.js",
        "vfs_fonts.js"
    )
    
    foreach ($file in $dtFiles) {
        if (Test-Path "$sourcePath\datatables\$file") {
            Copy-Item "$sourcePath\datatables\$file" "$targetPath\datatables\"
            Write-Host "✓ Copied $file" -ForegroundColor Green
        }
    }
}

# Copy jQuery files (though Moodle has its own jQuery)
Write-Host "Copying jQuery extensions..." -ForegroundColor Cyan
if (Test-Path "$sourcePath\jquery") {
    $jqPath = "$targetPath\jquery"
    New-Item -ItemType Directory -Path $jqPath -Force | Out-Null
    
    $jqFiles = @(
        "jquery-ui.min.js",
        "jquery.knob.min.js",
        "jquery.flot.min.js",
        "jquery.flot.pie.min.js",
        "jquery.flot.resize.min.js"
    )
    
    foreach ($file in $jqFiles) {
        if (Test-Path "$sourcePath\jquery\$file") {
            Copy-Item "$sourcePath\jquery\$file" "$jqPath\"
            Write-Host "✓ Copied $file" -ForegroundColor Green
        }
    }
}

# Copy map files
Write-Host "Copying map files..." -ForegroundColor Cyan
if (Test-Path "$sourcePath\map") {
    $mapPath = "$targetPath\map"
    New-Item -ItemType Directory -Path $mapPath -Force | Out-Null
    Copy-Item "$sourcePath\map\*" "$mapPath\" -Recurse -Force
    Write-Host "✓ Map files copied" -ForegroundColor Green
}

# Copy overlay scrollbars
Write-Host "Copying OverlayScrollbars..." -ForegroundColor Cyan
if (Test-Path "$sourcePath\overlayscrollbars") {
    $osPath = "$targetPath\overlayscrollbars"
    New-Item -ItemType Directory -Path $osPath -Force | Out-Null
    Copy-Item "$sourcePath\overlayscrollbars\*" "$osPath\" -Recurse -Force
    Write-Host "✓ OverlayScrollbars copied" -ForegroundColor Green
}

# Copy fonts
Write-Host "Copying Google Fonts..." -ForegroundColor Cyan
if (Test-Path "$sourcePath\fonts-googleapi") {
    $fontsPath = "$targetPath\fonts"
    New-Item -ItemType Directory -Path $fontsPath -Force | Out-Null
    Copy-Item "$sourcePath\fonts-googleapi\*" "$fontsPath\" -Recurse -Force
    Write-Host "✓ Google Fonts copied" -ForegroundColor Green
}

# Copy Ionicons
Write-Host "Copying Ionicons..." -ForegroundColor Cyan
if (Test-Path "$sourcePath\ionicons") {
    $ionPath = "$targetPath\ionicons"
    New-Item -ItemType Directory -Path $ionPath -Force | Out-Null
    Copy-Item "$sourcePath\ionicons\*" "$ionPath\" -Recurse -Force
    Write-Host "✓ Ionicons copied" -ForegroundColor Green
}

Write-Host ""
Write-Host "Migration completed!" -ForegroundColor Green
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "1. Test the plugin in Moodle"
Write-Host "2. Update any hardcoded asset paths in your PHP files"
Write-Host "3. Run 'npm install' and 'npm run build' to build JavaScript modules"
Write-Host "4. Remove the old 'views/assets' directory when satisfied"
Write-Host ""
Write-Host "New asset structure:" -ForegroundColor Cyan
Write-Host "- thirdpartylibs/ - Third-party libraries"
Write-Host "- amd/src/ - JavaScript source files"
Write-Host "- amd/build/ - Built JavaScript files"
Write-Host "- styles.css - Main stylesheet"
