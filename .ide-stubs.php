<?php
/**
 * IDE Stub File for 4images Legacy Code
 * 
 * This file helps IDEs understand optional constants and legacy functions
 * that may or may not be defined at runtime.
 * 
 * @see https://github.com/JetBrains/phpstorm-stubs
 */

// Optional constants from config.php
if (!defined('SESSION_KEY')) {
    /**
     * Optional session encryption key (defined in config.php or auto-generated)
     * @var string
     */
    define('SESSION_KEY', '');
}

if (!defined('PRINT_STATS')) {
    /**
     * Optional debug flag for compression statistics
     * @var int
     */
    define('PRINT_STATS', 0);
}

// Legacy constants from old versions (only in update scripts)
if (!defined('SESSION_VARS_TABLE')) {
    /**
     * Legacy table name from 4images v1.x (removed in modern versions)
     * @var string
     */
    define('SESSION_VARS_TABLE', '4images_sessionvars');
}

// Legacy functions (removed in PHP 5.4+, never executed on modern PHP)
if (!function_exists('session_register')) {
    /**
     * Register session variable (removed in PHP 5.4.0)
     * @param string $name
     * @return bool
     * @deprecated Since PHP 5.3.0, removed in PHP 5.4.0
     */
    function session_register(string $name): bool {
        return false;
    }
}

