<?php
/**************************************************************************
 *                                                                        *
 *    4images - A Web Based Image Gallery Management System               *
 *    ----------------------------------------------------------------    *
 *                                                                        *
 *             File: admin_global.php                                     *
 *        Copyright: (C) 2002-2023 4homepages.de                          *
 *            Email: 4images@4homepages.de                                *
 *              Web: http://www.4homepages.de                             *
 *    Scriptversion: 1.10                                                 *
 *                                                                        *
 **************************************************************************
 *                                                                        *
 *    Dieses Script ist KEINE Freeware. Bitte lesen Sie die Lizenz-       *
 *    bedingungen (Lizenz.txt) für weitere Informationen.                 *
 *    ---------------------------------------------------------------     *
 *    This script is NOT freeware! Please read the Copyright Notice       *
 *    (Licence.txt) for further information.                              *
 *                                                                        *
 *************************************************************************/
if (!defined('ROOT_PATH')) {
    die("Security violation");
}

define('GET_CACHES', 1);
include_once(ROOT_PATH.'global.php');
include_once(ROOT_PATH.'includes/sessions.php');
include_once(ROOT_PATH.'admin/admin_functions.php');

if ($csrf_protection_enable && $csrf_protection_backend) {
    csrf_start();
}

if (isset($_GET['redirect']) || isset($_POST['redirect'])) {
    $redirect = (isset($_GET['redirect'])) ? trim($_GET['redirect']) : trim($_POST['redirect']);
    $redirect = htmlspecialchars($redirect);
} else {
    $redirect = "";
}

if (isset($_GET['goto']) || isset($_POST['goto'])) {
    $goto = (isset($_GET['goto'])) ? trim($_GET['goto']) : trim($_POST['goto']);
} else {
    $goto = "";
}

if (isset($PHP_SELF) && preg_match("/\/plugins\//i", $PHP_SELF)) {
    $self_url = "plugins/".$self_url;
}

if ($goto != "") {
    $self_url .= preg_match("/\?/", $self_url) ? "&" : "?";
    $self_url .= "goto=".urlencode($goto);
}

$newlangfile = 0;
if (!file_exists(ROOT_PATH.'lang/'.$config['language_dir'].'/admin.php')) {
    $old_language_dir = $config['language_dir'];
    $handle = opendir(ROOT_PATH.'lang/');
    while ($folder = @readdir($handle)) {
        if (is_dir(ROOT_PATH."/lang/".$folder) && $folder != "." && $folder != "..") {
            unset($config['language_dir']);
            $config['language_dir'] = $folder;
            $newlangfile = 1;
        }
    }
    closedir($handle);
    if (!file_exists(ROOT_PATH.'lang/'.$config['language_dir'].'/admin.php')) {
        $newlangfile = 0;
        show_admin_header();
        echo "<p>".$lang['admin_no_lang']."</p>";
        show_admin_footer();
        exit;
    }
}

// Include default languages
@include_once(ROOT_PATH.'lang/english/admin.php');
include_once(ROOT_PATH.'lang/'.$config['language_dir'].'/admin.php');

if (strstr(getenv("HTTP_USER_AGENT"), "MSIE")) { // Browser Detection
    $textinput_size = "50";
    $textinput_size2 = "30";
    $textarea_size = "50";
} else {
    $textinput_size = "30";
    $textinput_size2 = "17";
    $textarea_size = "28";
}

if (isset($_GET['logout'])) {
    $site_sess->logout($user_info['user_id']);
    setcookie("adminon", 0, 0, '/');
    redirect("index.php");
}

if (isset($_POST['loginusername']) && isset($_POST['loginpassword'])) {
    $loginusername = trim($_POST['loginusername']);
    $loginpassword = trim($_POST['loginpassword']);
    if ($site_sess->login($loginusername, $loginpassword, 0, 0)) {
        $user_info = $site_sess->return_user_info();
    }
}

if (defined('ADMIN_SAFE_LOGIN') && ADMIN_SAFE_LOGIN == 1) {
    if ($user_info['user_level'] != GUEST && $user_info['user_level'] == ADMIN && isset($_POST['loginusername'])) {
        setcookie("adminon", 1, 0, '/');
        $_COOKIE['adminon'] = 1;
    } else {
        if ($user_info['user_level'] == GUEST || $user_info['user_level'] == USER || $user_info['user_level'] == USER_AWAITING) {
            $_COOKIE['adminon'] = 0;
        }
    }

    if (!isset($_COOKIE['adminon']) || $_COOKIE['adminon'] == 0) {
        $user_info['user_level'] = GUEST;
    } else {
        if ($user_info['user_level'] != GUEST  && $user_info['user_level'] == ADMIN && isset($_POST['loginusername'])) {
            setcookie("adminon", 1, 0, '/');
            $_COOKIE['adminon'] = 1;
        }
    }
}

if (isset($_POST['goback']) || isset($_GET['goback'])) {
    $back_url = $site_sess->get_session_var('back_url');

    if (empty($back_url)) {
        $back_url = "home.php";
    }

    $site_sess->drop_session_var('back_url');
    redirect($back_url);
    exit;
}

if ($user_info['user_level'] != ADMIN) {
    show_admin_header(); ?>
<br /><br /><br />
<table cellpadding="1" cellspacing="0" border="0" align="center" width="500"><tr><td class="tableborder">
<table cellpadding="4" cellspacing="0" border="0" width="100%">
<tr class="tablerow"><td align="center" nowrap><p><?php echo $lang['no_admin']; ?></p>
<form action="<?php echo $site_sess->url(ROOT_PATH."admin/index.php"); ?>" method="post">
<input type="hidden" name="action" value="login">
<input type="hidden" name="redirect" value="<?php echo $site_sess->url(ROOT_PATH."admin/".$self_url); ?>">
<table cellpadding="0" cellspacing="1" border="0">
<tr>
  <td><input type="text" name="loginusername" size="<?php echo $textinput_size2; ?>"></td>
  <td><input type="password" name="loginpassword" size="<?php echo $textinput_size2; ?>"></td>
  <td><input type="submit" value="   <?php echo $lang['admin_login']; ?>   "></td>
</tr>
<tr>
  <td><font size="1" class="smalltext"><?php echo $lang['field_username']; ?></font></td>
  <td colspan="2"><font size="1" class="smalltext"><?php echo $lang['field_password']; ?></font></td>
</tr>
</table>
</form>
</td></tr></table>
</td></tr></table>
<p align="center">4images Administration Control Panel</p>
<?php
  show_admin_footer();
    exit;
}
?>