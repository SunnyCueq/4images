<?php
/**************************************************************************
 *                                                                        *
 *    4images - Update Script                                             *
 *    ----------------------------------------------------------------    *
 *                                                                        *
 *    Updates existing 4images installation after cleanup                 *
 *    (Removal of backup system, postcard system, old templates)          *
 *                                                                        *
 **************************************************************************/

define('IN_CP', 1);
define('ROOT_PATH', './');

error_reporting(E_ALL & ~E_DEPRECATED);
ini_set('display_errors', 1);

$nozip = 1;

// Load config first
if (!file_exists(ROOT_PATH.'config.php')) {
    die('<h1>Error</h1><p>config.php not found. Please ensure 4images is properly installed.</p>');
}
require(ROOT_PATH.'config.php');

// Load database class
require(ROOT_PATH.'includes/db_mysqli.php');

// Load constants (required for sessions)
require(ROOT_PATH.'includes/constants.php');

// Load sessions
require(ROOT_PATH.'includes/sessions.php');

// Initialize database connection
$site_db = new Db($db_host, $db_user, $db_password, $db_name);

// Initialize session
$user_info = $site_session->return_user_info();

// Check if user is logged in as admin
$user_access = ($user_info['user_level'] != GUEST) ? $user_info['user_level'] : 0;
if ($user_access < ADMIN) {
    die("<h1>Access Denied</h1><p>You must be logged in as administrator to run this update.</p><p><a href=\"".ROOT_PATH."admin/\">Go to Admin Login</a></p>");
}

$updates_performed = array();
$errors = array();

// ============================================================================
// UPDATE 0: Delete old/removed files
// ============================================================================
$files_to_delete = array(
    'admin/backup.php',
    'postcards.php',
    'update_1.0_to_1.5.php',
    'update_1.5_to_1.6.php',
    'update_1.6.1_to_1.7.php',
    'update_RC-1_to_RC-2.php'
);

$dirs_to_delete = array(
    'templates/default_960px',
    'templates/default_full'
);

// Delete files
foreach ($files_to_delete as $file) {
    $filepath = ROOT_PATH . $file;
    if (file_exists($filepath)) {
        if (unlink($filepath)) {
            $updates_performed[] = "✅ Deleted: $file";
        } else {
            $errors[] = "❌ Failed to delete: $file (check permissions)";
        }
    } else {
        $updates_performed[] = "ℹ️ Already removed: $file";
    }
}

// Delete directories (recursive)
function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }
    if (!is_dir($dir)) {
        return unlink($dir);
    }
    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }
        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }
    }
    return rmdir($dir);
}

foreach ($dirs_to_delete as $dir) {
    $dirpath = ROOT_PATH . $dir;
    if (file_exists($dirpath)) {
        if (deleteDirectory($dirpath)) {
            $updates_performed[] = "✅ Deleted directory: $dir";
        } else {
            $errors[] = "❌ Failed to delete directory: $dir (check permissions)";
        }
    } else {
        $updates_performed[] = "ℹ️ Already removed: $dir";
    }
}

// ============================================================================
// UPDATE 1: Change template_dir from default_960px to default
// ============================================================================
$result = $site_db->query("SELECT setting_value FROM ".SETTINGS_TABLE." WHERE setting_name = 'template_dir'");
$row = $site_db->fetch_array($result);

if ($row && ($row['setting_value'] == 'default_960px' || $row['setting_value'] == 'default_full')) {
    $sql = "UPDATE ".SETTINGS_TABLE." 
            SET setting_value = 'default' 
            WHERE setting_name = 'template_dir'";
    
    if ($site_db->query($sql)) {
        $updates_performed[] = "✅ Template directory changed from '{$row['setting_value']}' to 'default'";
    } else {
        $errors[] = "❌ Failed to update template_dir setting";
    }
} else {
    $updates_performed[] = "ℹ️ Template directory already set correctly";
}

// ============================================================================
// UPDATE 2: Drop postcards table (if exists)
// ============================================================================
$result = $site_db->query("SHOW TABLES LIKE '".$table_prefix."postcards'");
if ($site_db->get_numrows($result) > 0) {
    $sql = "DROP TABLE IF EXISTS ".$table_prefix."postcards";
    if ($site_db->query($sql)) {
        $updates_performed[] = "✅ Dropped unused postcards table";
    } else {
        $errors[] = "❌ Failed to drop postcards table";
    }
} else {
    $updates_performed[] = "ℹ️ Postcards table already removed";
}

// ============================================================================
// UPDATE 3: Remove auth_sendpostcard column from groupaccess table
// ============================================================================
$result = $site_db->query("SHOW COLUMNS FROM ".GROUP_ACCESS_TABLE." LIKE 'auth_sendpostcard'");
if ($site_db->get_numrows($result) > 0) {
    $sql = "ALTER TABLE ".GROUP_ACCESS_TABLE." DROP COLUMN auth_sendpostcard";
    if ($site_db->query($sql)) {
        $updates_performed[] = "✅ Removed unused auth_sendpostcard column from groupaccess table";
    } else {
        $errors[] = "❌ Failed to remove auth_sendpostcard column";
    }
} else {
    $updates_performed[] = "ℹ️ auth_sendpostcard column already removed";
}

// ============================================================================
// Display results
// ============================================================================
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>4images Update</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        h1 {
            color: #333;
            border-bottom: 3px solid #0066cc;
            padding-bottom: 10px;
        }
        h2 {
            color: #666;
            margin-top: 30px;
        }
        .success {
            color: #28a745;
            padding: 10px;
            margin: 5px 0;
            background: #d4edda;
            border-left: 4px solid #28a745;
        }
        .info {
            color: #0066cc;
            padding: 10px;
            margin: 5px 0;
            background: #e7f3ff;
            border-left: 4px solid #0066cc;
        }
        .error {
            color: #dc3545;
            padding: 10px;
            margin: 5px 0;
            background: #f8d7da;
            border-left: 4px solid #dc3545;
        }
        .warning {
            color: #856404;
            padding: 10px;
            margin: 5px 0;
            background: #fff3cd;
            border-left: 4px solid #ffc107;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            margin: 20px 10px 0 0;
            background: #0066cc;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        .btn:hover {
            background: #0052a3;
        }
        .btn-success {
            background: #28a745;
        }
        .btn-success:hover {
            background: #218838;
        }
        code {
            background: #f4f4f4;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: monospace;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔄 4images Update Script</h1>
        <p>Update executed: <strong><?php echo date('Y-m-d H:i:s'); ?></strong></p>

        <h2>📋 Updates Performed:</h2>
        <?php
        if (!empty($updates_performed)) {
            foreach ($updates_performed as $update) {
                if (strpos($update, '✅') !== false) {
                    echo '<div class="success">' . $update . '</div>';
                } else {
                    echo '<div class="info">' . $update . '</div>';
                }
            }
        } else {
            echo '<div class="info">ℹ️ No updates were necessary</div>';
        }
        ?>

        <?php if (!empty($errors)): ?>
        <h2>⚠️ Errors:</h2>
        <?php foreach ($errors as $error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endforeach; ?>
        <?php endif; ?>

        <h2>📝 What Changed:</h2>
        <div class="info">
            <strong>Files Automatically Deleted:</strong>
            <ul>
                <li>❌ Backup System: <code>admin/backup.php</code></li>
                <li>❌ Postcard System: <code>postcards.php</code></li>
                <li>❌ Old Templates: <code>templates/default_960px/</code>, <code>templates/default_full/</code></li>
                <li>❌ Old Update Scripts: <code>update_1.0_to_1.5.php</code>, <code>update_1.5_to_1.6.php</code>, etc.</li>
            </ul>
            <p><strong>Note:</strong> These files are automatically removed by this script.</p>
        </div>

        <div class="warning">
            <strong>⚠️ Important:</strong>
            <ul>
                <li>Clear your browser cache after update</li>
                <li>Delete <code>update.php</code> after successful update for security</li>
                <li>Upload new template files via FTP to <code>templates/default/</code></li>
            </ul>
        </div>

        <h2>✅ Next Steps:</h2>
        <div class="info">
            <ol>
                <li>Test your gallery: <a href="<?php echo ROOT_PATH; ?>">Visit Homepage</a></li>
                <li>Check admin panel: <a href="<?php echo ROOT_PATH; ?>admin/">Visit Admin</a></li>
                <li>Verify template settings: Settings → Template Directory should be <code>default</code></li>
                <li>Delete this file: <code>rm <?php echo __FILE__; ?></code></li>
            </ol>
        </div>

        <a href="<?php echo ROOT_PATH; ?>" class="btn btn-success">Go to Homepage</a>
        <a href="<?php echo ROOT_PATH; ?>admin/" class="btn">Go to Admin Panel</a>
    </div>
</body>
</html>
<?php
// End of script
?>

