<?php
$dir = __DIR__ . '/../code-elta6ur-theme';
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$errorCount = 0;
$checked = 0;

foreach ($files as $file) {
    if ($file->isFile() && $file->getExtension() === 'php') {
        $checked++;
        $cmd = 'php -l ' . escapeshellarg($file->getRealPath()) . ' 2>&1';
        $output = shell_exec($cmd);
        if (strpos($output, 'No syntax errors detected') === false) {
            echo "ERROR in " . $file->getRealPath() . ":\n" . $output . "\n";
            $errorCount++;
        }
    }
}

echo "Checked $checked PHP files.\n";
if ($errorCount === 0) {
    echo "SUCCESS: 100% of theme PHP files have zero syntax errors!\n";
    exit(0);
} else {
    echo "FAILED: Found $errorCount syntax errors.\n";
    exit(1);
}
