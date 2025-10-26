<?php
/**************************************************************************
 *                                                                        *
 *    4images - A Web Based Image Gallery Management System               *
 *    ----------------------------------------------------------------    *
 *                                                                        *
 *             File: upload_definitions.php                               *
 *        Copyright: (C) 2002-2024 4homepages.de                          *
 *            Email: 4images@4homepages.de                                * 
 *              Web: http://www.4homepages.de                             * 
 *    Scriptversion: 2.0 (PHP 8.4+)                                       *
 *                                                                        *
 **************************************************************************
 *                                                                        *
 *    Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-       *
 *    bedingungen (Lizenz.txt) für weitere Informationen.                 *
 *    ---------------------------------------------------------------     *
 *    This script is NOT freeware! Please read the Copyright Notice       *
 *    (Licence.txt) for further information.                              *
 *                                                                        *
 **************************************************************************
 * 
 * MODERNIZED (2024): PHP 8.4+ - Uses fileinfo extension for accurate
 * MIME-Type detection. No legacy fallback needed.
 * 
 *************************************************************************/

if (!defined('ROOT_PATH')) {
    die("Security violation");
}

/**
 * Modern MIME-Type Definitions for PHP 8.4+
 * Based on IANA standards + common variations
 */

// Canonical MIME types for each file extension
$mime_type_match = [
    // ========== IMAGES ==========
    'jpg'  => ['image/jpeg', 'image/jpg', 'image/pjpeg'],
    'jpeg' => ['image/jpeg', 'image/jpg', 'image/pjpeg'],
    'png'  => ['image/png', 'image/x-png'],
    'gif'  => ['image/gif'],
    'webp' => ['image/webp'],
    'svg'  => ['image/svg+xml'],
    'bmp'  => ['image/bmp', 'image/x-bmp', 'image/x-ms-bmp'],
    'ico'  => ['image/x-icon', 'image/vnd.microsoft.icon'],
    'tif'  => ['image/tiff'],
    'tiff' => ['image/tiff'],
    'heic' => ['image/heic', 'image/heif'],
    'heif' => ['image/heif'],
    'avif' => ['image/avif'],
    
    // ========== AUDIO ==========
    'mp3'  => ['audio/mpeg', 'audio/mp3'],
    'wav'  => ['audio/wav', 'audio/x-wav', 'audio/wave'],
    'ogg'  => ['audio/ogg'],
    'flac' => ['audio/flac'],
    'm4a'  => ['audio/mp4', 'audio/x-m4a'],
    'aac'  => ['audio/aac', 'audio/x-aac'],
    'wma'  => ['audio/x-ms-wma'],
    'aif'  => ['audio/x-aiff', 'audio/aiff'],
    'aiff' => ['audio/x-aiff', 'audio/aiff'],
    'au'   => ['audio/basic'],
    'snd'  => ['audio/basic'],
    'mid'  => ['audio/midi', 'audio/x-midi'],
    'midi' => ['audio/midi', 'audio/x-midi'],
    'ra'   => ['audio/x-realaudio', 'audio/x-pn-realaudio'],
    'ram'  => ['audio/x-pn-realaudio'],
    'rm'   => ['audio/x-pn-realaudio', 'application/vnd.rn-realmedia'],
    'rpm'  => ['audio/x-pn-realaudio-plugin'],
    
    // ========== VIDEO ==========
    'mp4'  => ['video/mp4'],
    'avi'  => ['video/x-msvideo', 'video/avi', 'video/msvideo'],
    'mov'  => ['video/quicktime'],
    'qt'   => ['video/quicktime'],
    'wmv'  => ['video/x-ms-wmv'],
    'flv'  => ['video/x-flv'],
    'webm' => ['video/webm'],
    'mkv'  => ['video/x-matroska'],
    'mpg'  => ['video/mpeg'],
    'mpeg' => ['video/mpeg'],
    'mpe'  => ['video/mpeg'],
    'm4v'  => ['video/x-m4v'],
    '3gp'  => ['video/3gpp'],
    
    // ========== DOCUMENTS ==========
    'pdf'  => ['application/pdf', 'application/x-pdf'],
    'doc'  => ['application/msword'],
    'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
    'xls'  => ['application/vnd.ms-excel', 'application/msexcel', 'application/x-msexcel'],
    'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
    'ppt'  => ['application/vnd.ms-powerpoint', 'application/mspowerpoint'],
    'pptx' => ['application/vnd.openxmlformats-officedocument.presentationml.presentation'],
    'odt'  => ['application/vnd.oasis.opendocument.text'],
    'ods'  => ['application/vnd.oasis.opendocument.spreadsheet'],
    'odp'  => ['application/vnd.oasis.opendocument.presentation'],
    'txt'  => ['text/plain'],
    'rtf'  => ['text/rtf', 'application/rtf', 'text/richtext'],
    'rtx'  => ['text/richtext'],
    'csv'  => ['text/csv', 'text/comma-separated-values'],
    
    // ========== ARCHIVES ==========
    'zip'  => ['application/zip', 'application/x-zip-compressed'],
    'rar'  => ['application/x-rar-compressed', 'application/vnd.rar'],
    '7z'   => ['application/x-7z-compressed'],
    'tar'  => ['application/x-tar'],
    'gtar' => ['application/x-gtar'],
    'gz'   => ['application/gzip', 'application/x-gzip', 'application/x-gzip-compressed'],
    'bz2'  => ['application/x-bzip2'],
    'sit'  => ['application/x-stuffit'],
    
    // ========== WEB ==========
    'html' => ['text/html'],
    'htm'  => ['text/html'],
    'css'  => ['text/css'],
    'js'   => ['text/javascript', 'application/javascript'],
    'json' => ['application/json'],
    'xml'  => ['application/xml', 'text/xml'],
    
    // ========== OTHER ==========
    'swf'  => ['application/x-shockwave-flash'],
    'psd'  => ['image/vnd.adobe.photoshop', 'application/octet-stream'],
    'fla'  => ['application/octet-stream'],
    'ai'   => ['application/postscript'],
    'eps'  => ['application/postscript'],
    'ps'   => ['application/postscript'],
];

/**
 * Detect MIME type using PHP fileinfo extension (PHP 8.4+)
 * 
 * @param string $file_path Path to uploaded file
 * @param string $extension File extension (fallback)
 * @return string|null Detected MIME type or null
 */
function detect_mime_type(string $file_path, string $extension = ''): ?string 
{
    global $mime_type_match;
    
    // Primary: fileinfo extension (most accurate)
    if (function_exists('finfo_file') && file_exists($file_path)) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if ($finfo) {
            $mime = finfo_file($finfo, $file_path);
            finfo_close($finfo);
            
            // Valid MIME detected (not generic octet-stream)
            if ($mime && $mime !== 'application/octet-stream') {
                return $mime;
            }
        }
    }
    
    // Fallback: Extension-based (if file is unreadable or generic)
    if ($extension) {
        $extension = strtolower(trim($extension, '.'));
        if (isset($mime_type_match[$extension])) {
            return $mime_type_match[$extension][0]; // Return canonical MIME type
        }
    }
    
    return null;
}

/**
 * Validate detected MIME type against allowed types for extension
 * 
 * @param string $detected_mime Detected MIME type
 * @param string $extension File extension
 * @return bool True if valid, false otherwise
 */
function validate_mime_type(string $detected_mime, string $extension): bool 
{
    global $mime_type_match;
    
    $extension = strtolower(trim($extension, '.'));
    
    // Unknown extension = reject
    if (!isset($mime_type_match[$extension])) {
        return false;
    }
    
    $allowed_mimes = $mime_type_match[$extension];
    
    // Empty or generic octet-stream: allow if extension is whitelisted
    if (empty($detected_mime) || $detected_mime === 'application/octet-stream') {
        return true; // Trust extension (already validated)
    }
    
    // Check if detected MIME is in allowed list
    return in_array($detected_mime, $allowed_mimes, true);
}

/**
 * Get allowed MIME types for an extension
 * 
 * @param string $extension File extension
 * @return array Allowed MIME types
 */
function get_allowed_mime_types(string $extension): array 
{
    global $mime_type_match;
    
    $extension = strtolower(trim($extension, '.'));
    return $mime_type_match[$extension] ?? [];
}
