<?php
/**
 * Automated Verification Suite for Gamification, Points, Streak & Learning Progress
 * Code Elta6ur Platform - Gamification Test Suite
 */

$passCount = 0;
$failCount = 0;

function assertGamificationTest( $condition, $message ) {
	global $passCount, $failCount;
	if ( $condition ) {
		echo " [PASS] " . $message . "\n";
		$passCount++;
	} else {
		echo " [FAIL] " . $message . "\n";
		$failCount++;
	}
}

// -------------------------------------------------------------
// Setup Mock WordPress Environment for CLI execution if needed
// -------------------------------------------------------------
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/../' );
}
if ( ! defined( 'ARRAY_A' ) ) {
	define( 'ARRAY_A', 'ARRAY_A' );
}
if ( ! defined( 'OBJECT' ) ) {
	define( 'OBJECT', 'OBJECT' );
}
if ( ! defined( 'OBJECT_K' ) ) {
	define( 'OBJECT_K', 'OBJECT_K' );
}

if ( ! function_exists( 'sanitize_key' ) ) {
	function sanitize_key( $key ) {
		return preg_replace( '/[^a-z0-9_\-]/', '', strtolower( $key ) );
	}
}
if ( ! function_exists( 'sanitize_text_field' ) ) {
	function sanitize_text_field( $str ) {
		return trim( strip_tags( (string) $str ) );
	}
}
if ( ! function_exists( 'absint' ) ) {
	function absint( $val ) {
		return abs( (int) $val );
	}
}
if ( ! function_exists( 'wp_json_encode' ) ) {
	function wp_json_encode( $data ) {
		return json_encode( $data, JSON_UNESCAPED_UNICODE );
	}
}
if ( ! function_exists( '__' ) ) {
	function __( $text, $domain = 'default' ) {
		return $text;
	}
}
if ( ! function_exists( 'esc_html__' ) ) {
	function esc_html__( $text, $domain = 'default' ) {
		return $text;
	}
}
if ( ! function_exists( 'apply_filters' ) ) {
	function apply_filters( $tag, $value ) {
		return $value;
	}
}
if ( ! function_exists( 'do_action' ) ) {
	$GLOBALS['cdt_actions_fired'] = array();
	function do_action( $tag, ...$args ) {
		$GLOBALS['cdt_actions_fired'][ $tag ][] = $args;
	}
}
if ( ! function_exists( 'add_action' ) ) {
	function add_action( $tag, $callback, $priority = 10, $accepted_args = 1 ) {}
}
if ( ! function_exists( 'add_filter' ) ) {
	function add_filter( $tag, $callback, $priority = 10, $accepted_args = 1 ) {}
}

// In-Memory SQLite Mock for $wpdb to test real SQL queries and transactions!
class MockWPDB {
	public $prefix = 'wp_';
	public $insert_id = 0;
	private $pdo;

	public function __construct() {
		$this->pdo = new PDO( 'sqlite::memory:' );
		$this->pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
	}

	public function get_charset_collate() {
		return '';
	}

	public function query( $sql ) {
		// Convert MySQL DDL syntax to SQLite compatible for memory testing
		$sql = preg_replace( '/bigint\(\d+\)\s*unsigned/i', 'INTEGER', $sql );
		$sql = preg_replace( '/int\(\d+\)\s*unsigned/i', 'INTEGER', $sql );
		$sql = preg_replace( '/int\(\d+\)/i', 'INTEGER', $sql );
		$sql = preg_replace( '/varchar\(\d+\)/i', 'TEXT', $sql );
		$sql = preg_replace( '/longtext|text/i', 'TEXT', $sql );
		$sql = preg_replace( '/datetime|date/i', 'TEXT', $sql );
		$sql = preg_replace( '/AUTO_INCREMENT/i', 'PRIMARY KEY AUTOINCREMENT', $sql );
		$sql = preg_replace( '/PRIMARY KEY\s*\([^)]+\),?/i', '', $sql );
		$sql = preg_replace( '/UNIQUE KEY\s+[a-z0-9_]+\s*\(([^)]+)\),?/i', 'UNIQUE ($1),', $sql );
		$sql = preg_replace( '/(?<!UNIQUE\s)KEY\s+[a-z0-9_]+\s*\([^)]+\),?/i', '', $sql );
		$sql = preg_replace( '/,\s*\)/', ')', $sql );

		try {
			$this->pdo->exec( $sql );
			return true;
		} catch ( Exception $e ) {
			return false;
		}
	}

	public function prepare( $query, ...$args ) {
		if ( isset( $args[0] ) && is_array( $args[0] ) && 1 === count( $args ) ) {
			$args = $args[0];
		}
		$pdo = $this->pdo;
		$i = 0;
		$prepared = preg_replace_callback( '/(%d|%s|%f)/', function( $matches ) use ( &$i, $args, $pdo ) {
			if ( ! isset( $args[ $i ] ) ) {
				return "''";
			}
			$val = $args[ $i++ ];
			if ( '%d' === $matches[1] ) {
				return (int) $val;
			}
			return $pdo->quote( (string) $val );
		}, $query );
		return $prepared;
	}

	public function insert( $table, $data, $format = null ) {
		$cols = array_keys( $data );
		$placeholders = array_fill( 0, count( $cols ), '?' );
		$sql = "INSERT INTO {$table} (" . implode( ', ', $cols ) . ") VALUES (" . implode( ', ', $placeholders ) . ")";
		try {
			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( array_values( $data ) );
			$this->insert_id = (int) $this->pdo->lastInsertId();
			return 1;
		} catch ( Exception $e ) {
			return false;
		}
	}

	public function update( $table, $data, $where, $format = null, $where_format = null ) {
		$set = array();
		$vals = array();
		foreach ( $data as $col => $val ) {
			$set[] = "{$col} = ?";
			$vals[] = $val;
		}
		$where_clauses = array();
		foreach ( $where as $col => $val ) {
			$where_clauses[] = "{$col} = ?";
			$vals[] = $val;
		}
		$sql = "UPDATE {$table} SET " . implode( ', ', $set ) . " WHERE " . implode( ' AND ', $where_clauses );
		try {
			$stmt = $this->pdo->prepare( $sql );
			$stmt->execute( $vals );
			return $stmt->rowCount();
		} catch ( Exception $e ) {
			return false;
		}
	}

	public function get_var( $query ) {
		try {
			$stmt = $this->pdo->query( $query );
			return $stmt->fetchColumn();
		} catch ( Exception $e ) {
			return null;
		}
	}

	public function get_row( $query, $output = OBJECT ) {
		try {
			$stmt = $this->pdo->query( $query );
			$row = $stmt->fetch( PDO::FETCH_ASSOC );
			if ( ! $row ) return null;
			return $output === OBJECT ? (object) $row : $row;
		} catch ( Exception $e ) {
			return null;
		}
	}

	public function get_results( $query, $output = OBJECT ) {
		try {
			$stmt = $this->pdo->query( $query );
			$rows = $stmt->fetchAll( PDO::FETCH_ASSOC );
			if ( $output === ARRAY_A ) return $rows;
			$objs = array();
			foreach ( $rows as $r ) {
				$objs[] = (object) $r;
			}
			return $objs;
		} catch ( Exception $e ) {
			return array();
		}
	}
}

$GLOBALS['wpdb'] = new MockWPDB();

// Mock Options & User Meta stores
$GLOBALS['cdt_mock_options']   = array();
$GLOBALS['cdt_mock_usermeta']  = array();
$GLOBALS['cdt_mock_transients'] = array();

if ( ! function_exists( 'get_option' ) ) {
	function get_option( $key, $default = false ) {
		return isset( $GLOBALS['cdt_mock_options'][ $key ] ) ? $GLOBALS['cdt_mock_options'][ $key ] : $default;
	}
}
if ( ! function_exists( 'update_option' ) ) {
	function update_option( $key, $val ) {
		$GLOBALS['cdt_mock_options'][ $key ] = $val;
		return true;
	}
}
if ( ! function_exists( 'get_user_meta' ) ) {
	function get_user_meta( $user_id, $key, $single = false ) {
		if ( isset( $GLOBALS['cdt_mock_usermeta'][ $user_id ][ $key ] ) ) {
			return $GLOBALS['cdt_mock_usermeta'][ $user_id ][ $key ];
		}
		return $single ? '' : array();
	}
}
if ( ! function_exists( 'update_user_meta' ) ) {
	function update_user_meta( $user_id, $key, $val ) {
		$GLOBALS['cdt_mock_usermeta'][ $user_id ][ $key ] = $val;
		return true;
	}
}
if ( ! function_exists( 'get_transient' ) ) {
	function get_transient( $key ) {
		return isset( $GLOBALS['cdt_mock_transients'][ $key ] ) ? $GLOBALS['cdt_mock_transients'][ $key ] : false;
	}
}
if ( ! function_exists( 'set_transient' ) ) {
	function set_transient( $key, $val, $exp = 0 ) {
		$GLOBALS['cdt_mock_transients'][ $key ] = $val;
		return true;
	}
}
if ( ! function_exists( 'delete_transient' ) ) {
	function delete_transient( $key ) {
		unset( $GLOBALS['cdt_mock_transients'][ $key ] );
		return true;
	}
}
if ( ! function_exists( 'home_url' ) ) {
	function home_url( $path = '' ) {
		return 'https://code-elta6ur.com' . $path;
	}
}
if ( ! function_exists( 'current_time' ) ) {
	function current_time( $type ) {
		if ( 'Y-m-d' === $type ) return gmdate( 'Y-m-d' );
		if ( 'mysql' === $type ) return gmdate( 'Y-m-d H:i:s' );
		return time();
	}
}

// -------------------------------------------------------------
// Load Gamification Subsystem Files
// -------------------------------------------------------------
require_once __DIR__ . '/../code-elta6ur-theme/inc/gamification/db-schema.php';
require_once __DIR__ . '/../code-elta6ur-theme/inc/gamification/points-engine.php';
require_once __DIR__ . '/../code-elta6ur-theme/inc/gamification/streak-engine.php';
require_once __DIR__ . '/../code-elta6ur-theme/inc/gamification/reading-engine.php';
require_once __DIR__ . '/../code-elta6ur-theme/inc/gamification/course-progress.php';
require_once __DIR__ . '/../code-elta6ur-theme/inc/gamification/time-tracker.php';
require_once __DIR__ . '/../code-elta6ur-theme/inc/gamification/badges-levels.php';

echo "==================================================\n";
echo "1. TESTING DATABASE SCHEMA INSTALLATION\n";
echo "==================================================\n";

cdt_gamification_install_tables();
$db_ver = get_option( 'cdt_gamification_db_version' );
assertGamificationTest( '1.0.0' === $db_ver, "Database schema installer runs and sets version to 1.0.0" );

echo "\n==================================================\n";
echo "2. TESTING CENTRAL POINTS ENGINE & IDEMPOTENCY\n";
echo "==================================================\n";

$test_user_id = 99;

// Award 5 points for daily login
$res1 = cdt_award_points( $test_user_id, 'daily_login', 5, 0, 'daily_login_99_2026-10-11' );
assertGamificationTest( $res1['success'] === true && $res1['points_added'] === 5, "First point award succeeds (+5 pts)" );
assertGamificationTest( cdt_get_user_points( $test_user_id ) === 5, "User point balance is exactly 5" );

// Duplicate award attempt with same idempotency key
$res2 = cdt_award_points( $test_user_id, 'daily_login', 5, 0, 'daily_login_99_2026-10-11' );
assertGamificationTest( $res2['success'] === false && $res2['already_awarded'] === true, "Duplicate award with same idempotency key is rejected" );
assertGamificationTest( cdt_get_user_points( $test_user_id ) === 5, "User balance remains 5 without double-crediting" );

// Recalculation verification
$recalculated = cdt_recalculate_user_points( $test_user_id );
assertGamificationTest( $recalculated === 5, "Recalculation matches the ledger exact sum (5 pts)" );

echo "\n==================================================\n";
echo "3. TESTING DAILY LOGIN & STREAK LOGIC\n";
echo "==================================================\n";

// Day 1: New streak starts at 1
$streak_res1 = cdt_process_daily_activity( $test_user_id, 'login' );
assertGamificationTest( $streak_res1['current_streak'] === 1, "Initial qualifying activity starts streak at 1" );

// Same-day activity: Streak does not increase
$streak_res_same = cdt_process_daily_activity( $test_user_id, 'login' );
assertGamificationTest( $streak_res_same['current_streak'] === 1, "Repeating activity on the same calendar day does not increment streak" );

// Simulate Next Day (Yesterday was active)
update_user_meta( $test_user_id, 'cdt_last_streak_date', cdt_get_yesterday_date() );
update_user_meta( $test_user_id, 'cdt_current_streak', 1 );
$streak_res2 = cdt_process_daily_activity( $test_user_id, 'login' );
assertGamificationTest( $streak_res2['current_streak'] === 2, "Activity on consecutive day increases streak to 2" );
assertGamificationTest( $streak_res2['longest_streak'] >= 2, "Longest streak tracks peak performance" );

// Simulate Missed Calendar Day (Broken streak)
update_user_meta( $test_user_id, 'cdt_last_streak_date', '2026-01-01' );
update_user_meta( $test_user_id, 'cdt_current_streak', 5 );
update_user_meta( $test_user_id, 'cdt_longest_streak', 14 );

$streak_info_broken = cdt_get_user_streak( $test_user_id );
assertGamificationTest( $streak_info_broken['current_streak'] === 0, "Missed calendar day logically expires active streak to 0" );
assertGamificationTest( $streak_info_broken['longest_streak'] === 14, "Longest historical streak (14) is safely preserved" );

// Resuming activity after break resets streak to 1
$streak_res_resumed = cdt_process_daily_activity( $test_user_id, 'login' );
assertGamificationTest( $streak_res_resumed['current_streak'] === 1, "Resuming activity after a break starts new streak from 1" );

echo "\n==================================================\n";
echo "4. TESTING LEVELS & BADGES EVALUATION\n";
echo "==================================================\n";

// Level 1 test (0 pts)
$lvl1 = cdt_get_user_level_info( 101 );
assertGamificationTest( $lvl1['level'] === 1 && $lvl1['title'] === 'مبتدئ', "0 points corresponds to Level 1 (مبتدئ)" );

// Level 2 test (100 pts)
update_user_meta( 102, 'cdt_total_points_earned', 150 );
$lvl2 = cdt_get_user_level_info( 102 );
assertGamificationTest( $lvl2['level'] === 2 && $lvl2['title'] === 'مستكشف', "150 points corresponds to Level 2 (مستكشف)" );

// Level 5 test (1500+ pts)
update_user_meta( 103, 'cdt_total_points_earned', 2000 );
$lvl5 = cdt_get_user_level_info( 103 );
assertGamificationTest( $lvl5['level'] === 5 && $lvl5['title'] === 'خبير التطور', "2000 points corresponds to Level 5 (خبير التطور)" );

// Badges unlocking test
$badge_awarded = cdt_award_badge( $test_user_id, 'first_article' );
assertGamificationTest( $badge_awarded === true, "First badge awarded successfully" );

$badge_duplicate = cdt_award_badge( $test_user_id, 'first_article' );
assertGamificationTest( $badge_duplicate === false, "Duplicate badge award is rejected" );

$badges_status = cdt_get_user_badges_status( $test_user_id );
assertGamificationTest( $badges_status['first_article']['unlocked'] === true, "first_article badge is marked unlocked" );
assertGamificationTest( $badges_status['level_5']['unlocked'] === false, "level_5 badge remains locked" );

echo "\n==================================================\n";
echo "5. TESTING ACTIVE TIME TRACKING & DAILY CAP\n";
echo "==================================================\n";

$time_user = 200;
// Test safe ping of 15 seconds
$time_res = cdt_process_time_heartbeat( $time_user, 15 );
assertGamificationTest( $time_res['success'] === true && $time_res['active_seconds'] === 15, "Active heartbeat correctly logs safe elapsed seconds (15s)" );

// Test anti-spoofing clamp: large jump (e.g. 5000 seconds) is capped at max 25s
$spoof_res = cdt_process_time_heartbeat( $time_user, 5000 );
assertGamificationTest( $spoof_res['active_seconds'] <= 40, "Spoofed client seconds are strictly clamped by server" );

echo "\n==================================================\n";
echo "6. TESTING ADMIN ADJUSTMENT & AUDIT LOG\n";
echo "==================================================\n";

// Adjustment without reason must fail
$bad_adj = cdt_admin_adjust_points( $test_user_id, 50, '', 1 );
assertGamificationTest( $bad_adj['success'] === false, "Admin adjustment requires mandatory documented reason" );

// Valid adjustment
$good_adj = cdt_admin_adjust_points( $test_user_id, 50, 'مكافأة مسابقة برمجية', 1 );
assertGamificationTest( $good_adj['success'] === true && $good_adj['points_added'] === 50, "Documented admin adjustment succeeds and is logged in ledger" );

echo "\n==================================================\n";
echo "TEST RESULTS SUMMARY\n";
echo "==================================================\n";
echo "Total Passed: {$passCount}\n";
echo "Total Failed: {$failCount}\n\n";

if ( $failCount === 0 ) {
	echo ">>> ALL GAMIFICATION TESTS PASSED SUCCESSFULLY! <<<\n";
} else {
	echo ">>> SOME GAMIFICATION TESTS FAILED! <<<\n";
	exit( 1 );
}
