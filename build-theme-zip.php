<?php
/**
 * سكربت تحزيم قالب كود التطور لمعايير ووردبريس ولينكس
 * Code Elta6ur Standards-Compliant Theme ZIP Packager
 */

$sourceDir = __DIR__ . '/code-elta6ur-theme';
$zipStandard = __DIR__ . '/code-elta6ur-theme.zip';
$zipRoot     = __DIR__ . '/code-elta6ur-theme-root.zip';

if ( ! is_dir( $sourceDir ) ) {
	die( "Error: Source directory $sourceDir not found!\n" );
}

$sourceDirReal = realpath( $sourceDir );

// 1. Build Standard Theme ZIP (with code-elta6ur-theme/ directory and style.css at the top)
if ( file_exists( $zipStandard ) ) {
	unlink( $zipStandard );
}

$zip = new ZipArchive();
if ( $zip->open( $zipStandard, ZipArchive::CREATE | ZipArchive::OVERWRITE ) !== true ) {
	die( "Error: Cannot create zip archive $zipStandard\n" );
}

// Explicit root directory entry
$zip->addEmptyDir( 'code-elta6ur-theme' );

// Priority files added FIRST so WordPress upgrader immediately locates stylesheet
$priorityFiles = array( 'style.css', 'functions.php', 'index.php', 'screenshot.png', 'README.md' );
foreach ( $priorityFiles as $pf ) {
	$pfReal = $sourceDirReal . DIRECTORY_SEPARATOR . $pf;
	if ( file_exists( $pfReal ) ) {
		$zip->addFile( $pfReal, 'code-elta6ur-theme/' . $pf );
	}
}

// Collect all directories and remaining files
$dirIterator = new RecursiveIteratorIterator(
	new RecursiveDirectoryIterator( $sourceDirReal, RecursiveDirectoryIterator::SKIP_DOTS ),
	RecursiveIteratorIterator::SELF_FIRST
);

foreach ( $dirIterator as $item ) {
	$realPath     = $item->getRealPath();
	$relativePath = substr( $realPath, strlen( $sourceDirReal ) + 1 );
	$standardPath = str_replace( '\\', '/', $relativePath );

	// Skip priority files already added
	if ( in_array( $standardPath, $priorityFiles, true ) ) {
		continue;
	}

	if ( $item->isDir() ) {
		$zip->addEmptyDir( 'code-elta6ur-theme/' . $standardPath );
	} elseif ( $item->isFile() ) {
		$zip->addFile( $realPath, 'code-elta6ur-theme/' . $standardPath );
	}
}

$zip->close();
echo "Successfully built standard: {$zipStandard}\n";

// 2. Build Root-Level Theme ZIP (files directly in root of archive, guaranteed for direct extract)
if ( file_exists( $zipRoot ) ) {
	unlink( $zipRoot );
}

$zip2 = new ZipArchive();
if ( $zip2->open( $zipRoot, ZipArchive::CREATE | ZipArchive::OVERWRITE ) !== true ) {
	die( "Error: Cannot create zip archive $zipRoot\n" );
}

// Priority files added first in root archive
foreach ( $priorityFiles as $pf ) {
	$pfReal = $sourceDirReal . DIRECTORY_SEPARATOR . $pf;
	if ( file_exists( $pfReal ) ) {
		$zip2->addFile( $pfReal, $pf );
	}
}

foreach ( $dirIterator as $item ) {
	$realPath     = $item->getRealPath();
	$relativePath = substr( $realPath, strlen( $sourceDirReal ) + 1 );
	$standardPath = str_replace( '\\', '/', $relativePath );

	if ( in_array( $standardPath, $priorityFiles, true ) ) {
		continue;
	}

	if ( $item->isDir() ) {
		$zip2->addEmptyDir( $standardPath );
	} elseif ( $item->isFile() ) {
		$zip2->addFile( $realPath, $standardPath );
	}
}

$zip2->close();
echo "Successfully built root-level: {$zipRoot}\n";

// Verify both archives
foreach ( array( 'Standard' => $zipStandard, 'Root-level' => $zipRoot ) as $label => $file ) {
	$verify = new ZipArchive();
	if ( $verify->open( $file ) === true ) {
		$hasStyle = false;
		for ( $i = 0; $i < $verify->numFiles; $i++ ) {
			$name = $verify->getNameIndex( $i );
			if ( $name === 'code-elta6ur-theme/style.css' || $name === 'style.css' ) {
				$hasStyle = true;
			}
		}
		echo "[$label] Total entries: {$verify->numFiles}, style.css detected: " . ( $hasStyle ? 'YES' : 'NO' ) . "\n";
		$verify->close();
	}
}
