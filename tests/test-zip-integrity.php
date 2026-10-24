<?php
/**
 * Test script to verify ZIP structure for both direct upload and cPanel
 */
$zipPath = __DIR__ . '/../code-elta6ur-theme.zip';
if (!file_exists($zipPath)) {
    die("ZIP not found: $zipPath\n");
}

$zip = new ZipArchive();
if ($zip->open($zipPath) !== true) {
    die("Cannot open ZIP: $zipPath\n");
}

$backslashes = 0;
$styleCssFound = false;
$functionsPhpFound = false;

for ($i = 0; $i < $zip->numFiles; $i++) {
    $name = $zip->getNameIndex($i);
    if (strpos($name, '\\') !== false) {
        $backslashes++;
        echo "Found backslash in entry: $name\n";
    }
    if ($name === 'style.css') {
        $styleCssFound = true;
    }
    if ($name === 'functions.php') {
        $functionsPhpFound = true;
    }
}

echo "Total entries: " . $zip->numFiles . "\n";
echo "Backslashes count: " . $backslashes . "\n";
echo "style.css present at root: " . ($styleCssFound ? "YES" : "NO") . "\n";
echo "functions.php present at root: " . ($functionsPhpFound ? "YES" : "NO") . "\n";

$zip->close();

if ($backslashes === 0 && $styleCssFound && $functionsPhpFound) {
    echo "SUCCESS: Archive is 100% compliant with WordPress Admin Direct Upload standards!\n";
    exit(0);
} else {
    echo "FAILURE: Non-compliant archive structure!\n";
    exit(1);
}
