<?php
/**
 * سكربت تحزيم قالب كود التطور لمعايير ووردبريس ولينكس
 * Code Elta6ur Standards-Compliant Theme ZIP Packager
 */

$sourceDir   = __DIR__ . '/code-elta6ur-theme';
$zipWPAdmin  = __DIR__ . '/code-elta6ur-theme.zip';
$zipRoot     = __DIR__ . '/code-elta6ur-theme-root.zip';
$zipCpanel   = __DIR__ . '/code-elta6ur-theme-cpanel.zip';

if ( ! is_dir( $sourceDir ) ) {
	die( "Error: Source directory $sourceDir not found!\n" );
}

$sourceDirReal = realpath( $sourceDir );

// Priority files to guarantee style.css is read first
$priorityFiles = array( 'style.css', 'functions.php', 'index.php', 'screenshot.png', 'README.md' );

/**
 * 1. Build WP-Admin Direct Install ZIP (code-elta6ur-theme.zip)
 * Files are at the root level so WordPress upgrader immediately locates style.css
 * and installs it to wp-content/themes/code-elta6ur-theme/ without double-directory bugs.
 */
if ( file_exists( $zipWPAdmin ) ) {
	unlink( $zipWPAdmin );
}

$zip = new ZipArchive();
if ( $zip->open( $zipWPAdmin, ZipArchive::CREATE | ZipArchive::OVERWRITE ) !== true ) {
	die( "Error: Cannot create zip archive $zipWPAdmin\n" );
}

// Add priority files first
foreach ( $priorityFiles as $pf ) {
	$pfReal = $sourceDirReal . DIRECTORY_SEPARATOR . $pf;
	if ( file_exists( $pfReal ) ) {
		$zip->addFile( $pfReal, $pf );
	}
}

// Add all remaining files and directories
$dirIterator = new RecursiveIteratorIterator(
	new RecursiveDirectoryIterator( $sourceDirReal, RecursiveDirectoryIterator::SKIP_DOTS ),
	RecursiveIteratorIterator::SELF_FIRST
);

foreach ( $dirIterator as $item ) {
	$realPath     = $item->getRealPath();
	$relativePath = substr( $realPath, strlen( $sourceDirReal ) + 1 );
	$standardPath = str_replace( '\\', '/', $relativePath );

	if ( in_array( $standardPath, $priorityFiles, true ) ) {
		continue;
	}

	if ( $item->isDir() ) {
		$zip->addEmptyDir( $standardPath );
	} elseif ( $item->isFile() ) {
		$zip->addFile( $realPath, $standardPath );
	}
}

$zip->close();
echo "Successfully built direct WordPress upload ZIP: {$zipWPAdmin}\n";

// 2. Also keep code-elta6ur-theme-root.zip as identical backup
copy( $zipWPAdmin, $zipRoot );
echo "Successfully updated: {$zipRoot}\n";

// 3. Build cPanel / File Manager ZIP with folder wrapper (code-elta6ur-theme/...)
if ( file_exists( $zipCpanel ) ) {
	unlink( $zipCpanel );
}

$zip3 = new ZipArchive();
if ( $zip3->open( $zipCpanel, ZipArchive::CREATE | ZipArchive::OVERWRITE ) !== true ) {
	die( "Error: Cannot create zip archive $zipCpanel\n" );
}

$zip3->addEmptyDir( 'code-elta6ur-theme' );
foreach ( $priorityFiles as $pf ) {
	$pfReal = $sourceDirReal . DIRECTORY_SEPARATOR . $pf;
	if ( file_exists( $pfReal ) ) {
		$zip3->addFile( $pfReal, 'code-elta6ur-theme/' . $pf );
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
		$zip3->addEmptyDir( 'code-elta6ur-theme/' . $standardPath );
	} elseif ( $item->isFile() ) {
		$zip3->addFile( $realPath, 'code-elta6ur-theme/' . $standardPath );
	}
}

$zip3->close();
echo "Successfully built cPanel/FTP extract ZIP: {$zipCpanel}\n";

// Verification
foreach ( array( 'WordPress Admin Upload' => $zipWPAdmin, 'cPanel Folder Extract' => $zipCpanel ) as $label => $file ) {
	$verify = new ZipArchive();
	if ( $verify->open( $file ) === true ) {
		$hasStyle = false;
		for ( $i = 0; $i < $verify->numFiles; $i++ ) {
			$name = $verify->getNameIndex( $i );
			if ( $name === 'style.css' || $name === 'code-elta6ur-theme/style.css' ) {
				$hasStyle = true;
			}
		}
		echo "[$label] Total entries: {$verify->numFiles}, style.css detected: " . ( $hasStyle ? 'YES' : 'NO' ) . "\n";
		$verify->close();
	}
}
