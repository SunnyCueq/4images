<?php
/**************************************************************************
 *                                                                        *
 *    4images - A Web Based Image Gallery Management System               *
 *    ----------------------------------------------------------------    *
 *                                                                        *
 *             File: details.php                                          *
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

$main_template = 'details';

define('GET_CACHES', 1);
define('ROOT_PATH', './');
define('MAIN_SCRIPT', __FILE__);
include(ROOT_PATH.'global.php');
require(ROOT_PATH.'includes/sessions.php');
$user_access = get_permission();
include(ROOT_PATH.'includes/page_header.php');

if (!$image_id) {
    redirect($url);
}

$additional_sql = "";
if (!empty($additional_image_fields)) {
  foreach ($additional_image_fields as $key => $val) {
    $additional_sql .= ", i.".$key;
  }
}

$sql = "SELECT i.image_id, i.cat_id, i.user_id, i.image_name, i.image_description, i.image_keywords, i.image_date, i.image_active, i.image_media_file, i.image_thumb_file, i.image_download_url, i.image_allow_comments, i.image_comments, i.image_downloads, i.image_votes, i.image_rating, i.image_hits".$additional_sql.", c.cat_name".get_user_table_field(", u.", "user_name").get_user_table_field(", u.", "user_email")."
        FROM (".IMAGES_TABLE." i,  ".CATEGORIES_TABLE." c)
        LEFT JOIN ".USERS_TABLE." u ON (".get_user_table_field("u.", "user_id")." = i.user_id)
        WHERE i.image_id = $image_id AND i.image_active = 1 AND c.cat_id = i.cat_id";
$image_row = $site_db->query_firstrow($sql);
$cat_id = (isset($image_row['cat_id'])) ? $image_row['cat_id'] : 0;
$is_image_owner = ($image_row['user_id'] > USER_AWAITING && $user_info['user_id'] == $image_row['user_id']) ? 1 : 0;

if (!check_permission("auth_viewcat", $cat_id) || !check_permission("auth_viewimage", $cat_id) || !$image_row) {
  redirect($url);
}

$random_cat_image = (defined("SHOW_RANDOM_IMAGE") && SHOW_RANDOM_IMAGE == 0) ? "" : get_random_image($cat_id);
$site_template->register_vars("random_cat_image", $random_cat_image);
unset($random_cat_image);

//-----------------------------------------------------
//--- Show Image --------------------------------------
//-----------------------------------------------------
$image_allow_comments = (check_permission("auth_readcomment", $cat_id)) ? $image_row['image_allow_comments'] : 0;
$image_name = format_text($image_row['image_name'], 2);
show_image($image_row, $mode, 0, 1);


    //--- SEO variables -------------------------------

    $meta_keywords  = !empty($image_row['image_keywords']) ? strip_tags(implode(", ", explode(",", $image_row['image_keywords']))) : "";
    $meta_description = !empty($image_row['image_description']) ? strip_tags($image_row['image_description']) . ". " : "";

    $site_template->register_vars(array(
            "detail_meta_description"   => str_replace('"', "&quot;", $meta_description),
            "detail_meta_keywords"      => str_replace('"', "&quot;", $meta_keywords),
            "prepend_head_title"        => $image_name . " - ",
            ));


$in_mode = 0;

$sql = "";
if ($mode == "lightbox") {
  if (!empty($user_info['lightbox_image_ids'])) {
    // SECURITY FIX: Sanitize IDs for IN() query
    $image_id_sql = $site_db->sanitize_ids(str_replace(" ", ",", trim($user_info['lightbox_image_ids'])));
    $sql = "SELECT image_id, cat_id, image_name, image_media_file, image_thumb_file
            FROM ".IMAGES_TABLE."
            WHERE image_active = 1 AND image_id IN ($image_id_sql) AND (cat_id NOT IN (".get_auth_cat_sql("auth_viewimage", "NOTIN").", ".get_auth_cat_sql("auth_viewcat", "NOTIN")."))
            ORDER BY ".$config['image_order']." ".$config['image_sort'].", image_id ".$config['image_sort'];
    $in_mode = 1;
  }
}
elseif ($mode == "search") {
  if (!isset($session_info['searchid']) || empty($session_info['searchid'])) {
    $session_info['search_id'] = $site_sess->get_session_var("search_id");
  }

  if (!empty($session_info['search_id'])) {
    // SECURITY FIX: Use unserialize with allowed_classes option (PHP 8.4+)
    $search_id = unserialize($session_info['search_id'], ['allowed_classes' => false]);
  }

  $sql_where_query = "";

  if (!empty($search_id['image_ids'])) {
    $sql_where_query .= "AND image_id IN (".$search_id['image_ids'].") ";
  }

  if (!empty($search_id['user_ids'])) {
    $sql_where_query .= "AND user_id IN (".$search_id['user_ids'].") ";
  }

  if (!empty($search_id['search_new_images']) && $search_id['search_new_images'] == 1) {
    $new_cutoff = time() - 60 * 60 * 24 * $config['new_cutoff'];
    $sql_where_query .= "AND image_date >= $new_cutoff ";
  }

  if (!empty($search_id['search_cat']) && $search_id['search_cat'] != 0) {
    $cat_id_sql = 0;
    if (check_permission("auth_viewcat", $search_id['search_cat'])) {
      $sub_cat_ids = get_subcat_ids($search_id['search_cat'], $search_id['search_cat'], $cat_parent_cache);
      $cat_id_sql .= ", ".$search_id['search_cat'];
      if (!empty($sub_cat_ids[$search_id['search_cat']])) {
        foreach ($sub_cat_ids[$search_id['search_cat']] as $val) {
          if (check_permission("auth_viewcat", $val)) {
            $cat_id_sql .= ", ".$val;
          }
        }
      }
    }
    $cat_id_sql = $cat_id_sql !== 0 ? "AND cat_id IN ($cat_id_sql)" : "";
  }
  else {
    $cat_id_sql = get_auth_cat_sql("auth_viewcat", "NOTIN");
    $cat_id_sql = $cat_id_sql !== 0 ? "AND cat_id NOT IN (".$cat_id_sql.")" : "";
  }

  if (!empty($sql_where_query)) {
    $sql = "SELECT image_id, cat_id, image_name, image_media_file, image_thumb_file
            FROM ".IMAGES_TABLE."
            WHERE image_active = 1
            $sql_where_query
            $cat_id_sql
            ORDER BY ".$config['image_order']." ".$config['image_sort'].", image_id ".$config['image_sort'];
    $in_mode = 1;
  }
}
if (!$in_mode || empty($sql)) {
  $sql = "SELECT image_id, cat_id, image_name, image_media_file, image_thumb_file
          FROM ".IMAGES_TABLE."
          WHERE image_active = 1 AND cat_id = $cat_id
          ORDER BY ".$config['image_order']." ".$config['image_sort'].", image_id ".$config['image_sort'];
}
$result = $site_db->query($sql);

$image_id_cache = array();
$next_prev_cache = array();
$break = 0;
$prev_id = 0;
while($row = $site_db->fetch_array($result)) {
  $image_id_cache[] = $row['image_id'];
  $next_prev_cache[$row['image_id']] = $row;
  if ($break) {
    break;
  }
  if ($prev_id == $image_id) {
    $break = 1;
  }
  $prev_id = $row['image_id'];
}
$site_db->free_result();

if (!function_exists("array_search")) {
  function array_search($needle, $haystack) {
    $match = false;
    foreach ($haystack as $key => $value) {
      if ($value == $needle) {
        $match = $key;
      }
    }
    return $match;
  }
}

$act_key = array_search($image_id, $image_id_cache);
$next_image_id = (isset($image_id_cache[$act_key + 1])) ? $image_id_cache[$act_key + 1] : 0;
$prev_image_id = (isset($image_id_cache[$act_key - 1])) ? $image_id_cache[$act_key - 1] : 0;
unset($image_id_cache);

// Get next and previous image
if (!empty($next_prev_cache[$next_image_id])) {
  $next_image_name = format_text($next_prev_cache[$next_image_id]['image_name'], 2);
  $next_image_url = $site_sess->url(ROOT_PATH."details.php?".URL_IMAGE_ID."=".$next_image_id.((!empty($mode)) ? "&amp;mode=".$mode : ""));
  if (!get_file_path($next_prev_cache[$next_image_id]['image_media_file'], "media", $next_prev_cache[$next_image_id]['cat_id'], 0, 0)) {
    $next_image_file = "data:image/svg+xml;base64,".base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/></svg>');
  }
  else {
    $next_image_file = get_file_path($next_prev_cache[$next_image_id]['image_media_file'], "media", $next_prev_cache[$next_image_id]['cat_id'], 0, 1);
  }
  if (!get_file_path($next_prev_cache[$next_image_id]['image_thumb_file'], "thumb", $next_prev_cache[$next_image_id]['cat_id'], 0, 0)) {
    $next_thumb_file = "data:image/svg+xml;base64,".base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/></svg>');
  }
  else {
    $next_thumb_file = get_file_path($next_prev_cache[$next_image_id]['image_thumb_file'], "thumb", $next_prev_cache[$next_image_id]['cat_id'], 0, 1);
  }
}
else {
  $next_image_name = REPLACE_EMPTY;
  $next_image_url = REPLACE_EMPTY;
  $next_image_file = REPLACE_EMPTY;
  $next_thumb_file = REPLACE_EMPTY;
}

if (!empty($next_prev_cache[$prev_image_id])) {
  $prev_image_name = format_text($next_prev_cache[$prev_image_id]['image_name'], 2);
  $prev_image_url = $site_sess->url(ROOT_PATH."details.php?".URL_IMAGE_ID."=".$prev_image_id.((!empty($mode)) ? "&amp;mode=".$mode : ""));
  if (!get_file_path($next_prev_cache[$prev_image_id]['image_media_file'], "media", $next_prev_cache[$prev_image_id]['cat_id'], 0, 0)) {
    $prev_image_file = "data:image/svg+xml;base64,".base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/></svg>');
  }
  else {
    $prev_image_file = get_file_path($next_prev_cache[$prev_image_id]['image_media_file'], "media", $next_prev_cache[$prev_image_id]['cat_id'], 0, 1);
  }
  if (!get_file_path($next_prev_cache[$prev_image_id]['image_thumb_file'], "thumb", $next_prev_cache[$prev_image_id]['cat_id'], 0, 0)) {
    $prev_thumb_file = "data:image/svg+xml;base64,".base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/></svg>');
  }
  else {
    $prev_thumb_file = get_file_path($next_prev_cache[$prev_image_id]['image_thumb_file'], "thumb", $next_prev_cache[$prev_image_id]['cat_id'], 0, 1);
  }
}
else {
  $prev_image_name = REPLACE_EMPTY;
  $prev_image_url = REPLACE_EMPTY;
  $prev_image_file = REPLACE_EMPTY;
  $prev_thumb_file = REPLACE_EMPTY;
}

$site_template->register_vars(array(
  "next_image_id" => $next_image_id,
  "next_image_name" => $next_image_name,
  "next_image_url" => $next_image_url,
  "next_image_file" => $next_image_file,
  "next_thumb_file" => $next_thumb_file,
  "prev_image_id" => $prev_image_id,
  "prev_image_name" => $prev_image_name,
  "prev_image_url" => $prev_image_url,
  "prev_image_file" => $prev_image_file,
  "prev_thumb_file" => $prev_thumb_file
));
unset($next_prev_cache);

//-----------------------------------------------------
//--- Get Similar Images (Steam-Style Sidebar) --------
//-----------------------------------------------------
$similar_images_html = "";
$sql = "SELECT image_id, cat_id, image_name, image_thumb_file
        FROM ".IMAGES_TABLE."
        WHERE image_active = 1
        AND cat_id = $cat_id
        AND image_id != $image_id
        ORDER BY RAND()
        LIMIT 6";
$result = $site_db->query($sql);
$similar_count = 0;

while ($similar_row = $site_db->fetch_array($result)) {
  $similar_count++;
  $thumb_file = get_file_path($similar_row['image_thumb_file'], "thumb", $similar_row['cat_id'], 0, 1);
  $image_url = $site_sess->url(ROOT_PATH."details.php?".URL_IMAGE_ID."=".$similar_row['image_id']);
  $image_name = format_text($similar_row['image_name'], 2);

  if (!$thumb_file) {
    $thumb_file = "data:image/svg+xml;base64,".base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" fill="currentColor" viewBox="0 0 16 16"><path d="M14 14V4.5L9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2zM9.5 3A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1h5.5v2z"/></svg>');
  }

  $similar_images_html .= '<a href="'.$image_url.'" class="similar-image-item" title="'.$image_name.'">';
  $similar_images_html .= '<img src="'.$thumb_file.'" alt="'.$image_name.'" class="img-fluid rounded shadow-sm mb-2">';
  $similar_images_html .= '</a>';
}
$site_db->free_result($result);

// If no similar images found, show placeholder
if ($similar_count == 0) {
  $similar_images_html = '<p class="text-muted small"><i class="fa-solid fa-images me-2"></i>No similar images in this category</p>';
}

$site_template->register_vars(array(
  "similar_images_list" => $similar_images_html,
  "similar_images_count" => $similar_count
));

//-----------------------------------------------------
//--- Save Comment ------------------------------------
//-----------------------------------------------------
$error = 0;
if ($action == "postcomment" && isset($_POST[URL_ID])) {
  $id = intval($_POST[URL_ID]);
  $sql = "SELECT cat_id, image_allow_comments
          FROM ".IMAGES_TABLE."
          WHERE image_id = $id";
  $row = $site_db->query_firstrow($sql);

  if ($row['image_allow_comments'] == 0 || !check_permission("auth_postcomment", $row['cat_id']) || !$row) {
    $msg = $lang['comments_deactivated'];
  }
  else {
    $user_name = un_htmlspecialchars(trim($_POST['user_name']));
    $comment_headline = un_htmlspecialchars(trim($_POST['comment_headline']));
    $comment_text = un_htmlspecialchars(trim($_POST['comment_text']));

    $captcha = (isset($_POST['captcha'])) ? un_htmlspecialchars(trim($_POST['captcha'])) : "";

    // Flood Check
    $sql = "SELECT comment_ip, comment_date
            FROM ".COMMENTS_TABLE."
            WHERE image_id = $id
            ORDER BY comment_date DESC
            LIMIT 1";
    $spam_row = $site_db->query_firstrow($sql);
    $spamtime = $spam_row['comment_date'] + 180;

    if ($session_info['session_ip'] == $spam_row['comment_ip'] && time() <= $spamtime && $user_info['user_level'] != ADMIN)  {
      $msg .= (($msg != "") ? "<br />" : "").$lang['spamming'];
      $error = 1;
    }

    $user_name_field = get_user_table_field("", "user_name");
    if (!empty($user_name_field)) {
      if ($site_db->not_empty("SELECT $user_name_field FROM ".USERS_TABLE." WHERE $user_name_field = '".strtolower($user_name)."' AND ".get_user_table_field("", "user_id")." <> '".$user_info['user_id']."'")) {
        $msg .= (($msg != "") ? "<br />" : "").$lang['username_exists'];
        $error = 1;
      }
    }
    if ($user_name == "")  {
      $msg .= (($msg != "") ? "<br />" : "").$lang['name_required'];
      $error = 1;
    }
    if ($comment_headline == "")  {
      $msg .= (($msg != "") ? "<br />" : "").$lang['headline_required'];
      $error = 1;
    }
    if ($comment_text == "")  {
      $msg .= (($msg != "") ? "<br />" : "").$lang['comment_required'];
      $error = 1;
    }

    if ($captcha_enable_comments && !captcha_validate($captcha)) {
      $msg .= (($msg != "") ? "<br />" : "").$lang['captcha_required'];
      $error = 1;
    }

    if (!$error)  {
      $sql = "INSERT INTO ".COMMENTS_TABLE."
              (image_id, user_id, user_name, comment_headline, comment_text, comment_ip, comment_date)
              VALUES
              ($id, ".$user_info['user_id'].", '$user_name', '$comment_headline', '$comment_text', '".$session_info['session_ip']."', ".time().")";
      $site_db->query($sql);
      $commentid = $site_db->get_insert_id();
      update_comment_count($id, $user_info['user_id']);
      $msg = $lang['comment_success'];
      $site_sess->set_session_var("msgdetails", $msg);
      redirect(ROOT_PATH."details.php?".URL_IMAGE_ID."=".$image_id.((!empty($mode)) ? "&mode=".$mode : "").(($page > 1) ? "&page=".$page : ""));
    }
  }
  unset($row);
  unset($spam_row);
}

//-----------------------------------------------------
//--- Show Comments -----------------------------------
//-----------------------------------------------------
if ($msgdetails = $site_sess->get_session_var("msgdetails"))
{
  $msg .= ($msg !== "" ? "<br />" : "").$msgdetails;
  unset($msgdetails);
  $site_sess->drop_session_var("msgdetails");
}

if ($image_allow_comments == 1) {
  $site_template->register_vars(array(
      "has_rss"   => true,
      "rss_title" => "RSS Feed: ".$image_name." (".str_replace(':', '', $lang['comments']).")",
      "rss_url"   => $script_url."/rss.php?action=comments&amp;".URL_IMAGE_ID."=".$image_id
  ));

  $sql = "SELECT c.comment_id, c.image_id, c.user_id, c.user_name AS comment_user_name, c.comment_headline, c.comment_text, c.comment_ip, c.comment_date".get_user_table_field(", u.", "user_level").get_user_table_field(", u.", "user_name").get_user_table_field(", u.", "user_email").get_user_table_field(", u.", "user_showemail").get_user_table_field(", u.", "user_invisible").get_user_table_field(", u.", "user_joindate").get_user_table_field(", u.", "user_lastaction").get_user_table_field(", u.", "user_comments").get_user_table_field(", u.", "user_homepage")."
          FROM ".COMMENTS_TABLE." c
          LEFT JOIN ".USERS_TABLE." u ON (".get_user_table_field("u.", "user_id")." = c.user_id)
          WHERE c.image_id = $image_id
          ORDER BY c.comment_date ASC";
  $result = $site_db->query($sql);

  $comment_row = array();
  while ($row = $site_db->fetch_array($result)) {
    $comment_row[] = $row;
  }
  $site_db->free_result($result);
  $num_comments = sizeof($comment_row);

  if (!$num_comments) {
    $comments = "<div class=\"alert alert-info text-center\">".$lang['no_comments']."</div>";
  }
  else {
    $comments = "";
    $bgcounter = 0;
    for ($i = 0; $i < $num_comments; $i++) {
      $row_bg_number = ($bgcounter++ % 2 == 0) ? 1 : 2;

      $comment_user_email = "";
      $comment_user_email_save = "";
      $comment_user_mailform_link = "";
      $comment_user_email_button = "";
      $comment_user_homepage_button = "";
      // ICQ support removed
      $comment_user_profile_button = "";
      $comment_user_status_img = REPLACE_EMPTY;
      $comment_user_name = format_text($comment_row[$i]['comment_user_name'], 2);
      $comment_user_info = $lang['userlevel_guest'];

      $comment_user_id = $comment_row[$i]['user_id'];

      if (isset($comment_row[$i][$user_table_fields['user_name']]) && $comment_user_id != GUEST) {
        $comment_user_name = format_text($comment_row[$i][$user_table_fields['user_name']], 2);

        $comment_user_profile_link = !empty($url_show_profile) ? $site_sess->url(preg_replace("/{user_id}/", $comment_user_id, $url_show_profile)) : $site_sess->url(ROOT_PATH."member.php?action=showprofile&amp;".URL_USER_ID."=".$comment_user_id);
        $comment_user_profile_button = "<a href=\"".$comment_user_profile_link."\" class=\"text-decoration-none me-2\" title=\"".$comment_user_name."\"><i class=\"fa-solid fa-user\"></i></a>";

        $comment_user_status_img = ($comment_row[$i][$user_table_fields['user_lastaction']] >= (time() - 300) && ((isset($comment_row[$i][$user_table_fields['user_invisible']]) && $comment_row[$i][$user_table_fields['user_invisible']] == 0) || $user_info['user_level'] == ADMIN)) ? "<i class=\"fa-solid fa-circle text-success\" title=\"Online\"></i>" : "<i class=\"fa-solid fa-circle text-muted\" title=\"Offline\"></i>";

        $comment_user_homepage = (isset($comment_row[$i][$user_table_fields['user_homepage']])) ? format_url($comment_row[$i][$user_table_fields['user_homepage']]) : "";
        if (!empty($comment_user_homepage)) {
          $comment_user_homepage_button = "<a href=\"".$comment_user_homepage."\" target=\"_blank\" class=\"text-decoration-none me-2\" title=\"".$comment_user_homepage."\"><i class=\"fa-solid fa-globe\"></i></a>";
        }

        // ICQ support removed

        if (!empty($comment_row[$i][$user_table_fields['user_email']]) && (!isset($comment_row[$i][$user_table_fields['user_showemail']]) || (isset($comment_row[$i][$user_table_fields['user_showemail']]) && $comment_row[$i][$user_table_fields['user_showemail']] == 1))) {
          $comment_user_email = format_text($comment_row[$i][$user_table_fields['user_email']]);
          $comment_user_email_save = format_text(str_replace("@", " at ", $comment_row[$i][$user_table_fields['user_email']]));
          if (!empty($url_mailform)) {
            $comment_user_mailform_link = $site_sess->url(preg_replace("/{user_id}/", $comment_user_id, $url_mailform));
          }
          else {
            $comment_user_mailform_link = $site_sess->url(ROOT_PATH."member.php?action=mailform&amp;".URL_USER_ID."=".$comment_user_id);
          }
          $comment_user_email_button = "<a href=\"".$comment_user_mailform_link."\" class=\"text-decoration-none me-2\" title=\"".$comment_user_email_save."\"><i class=\"fa-solid fa-envelope\"></i></a>";
        }

        if (!isset($comment_row[$i][$user_table_fields['user_level']]) || (isset($comment_row[$i][$user_table_fields['user_level']]) && $comment_row[$i][$user_table_fields['user_level']] == USER)) {
          $comment_user_info = $lang['userlevel_user'];
        }
        elseif ($comment_row[$i][$user_table_fields['user_level']] == ADMIN) {
          $comment_user_info = $lang['userlevel_admin'];
        }

        $comment_user_info .= "<br />";
        $comment_user_info .= (isset($comment_row[$i][$user_table_fields['user_joindate']])) ? "<br />".$lang['join_date']." ".format_date($config['date_format'], $comment_row[$i][$user_table_fields['user_joindate']]) : "";
        $comment_user_info .= (isset($comment_row[$i][$user_table_fields['user_comments']])) ? "<br />".$lang['comments']." ".$comment_row[$i][$user_table_fields['user_comments']] : "";
      }

      $comment_user_ip = ($user_info['user_level'] == ADMIN) ? $comment_row[$i]['comment_ip'] : "";

      $admin_links = "";
      if ($user_info['user_level'] == ADMIN) {
        $admin_links .= "<a href=\"".$site_sess->url(ROOT_PATH."admin/index.php?goto=".urlencode("comments.php?action=editcomment&amp;comment_id=".$comment_row[$i]['comment_id']))."\" target=\"_blank\" class=\"btn btn-outline-warning btn-sm me-1\" title=\"".$lang['edit']."\"><i class=\"fa-solid fa-edit\"></i></a>";
        $admin_links .= "<a href=\"".$site_sess->url(ROOT_PATH."admin/index.php?goto=".urlencode("comments.php?action=removecomment&amp;comment_id=".$comment_row[$i]['comment_id']))."\" target=\"_blank\" class=\"btn btn-outline-danger btn-sm\" title=\"".$lang['delete']."\"><i class=\"fa-solid fa-trash\"></i></a>";
      }
      elseif ($is_image_owner) {
        $admin_links .= ($config['user_edit_comments'] != 1) ? "" : "<a href=\"".$site_sess->url(ROOT_PATH."member.php?action=editcomment&amp;".URL_COMMENT_ID."=".$comment_row[$i]['comment_id'])."\" class=\"btn btn-outline-warning btn-sm me-1\" title=\"".$lang['edit']."\"><i class=\"fa-solid fa-edit\"></i></a>";
        $admin_links .= ($config['user_delete_comments'] != 1) ? "" : "<a href=\"".$site_sess->url(ROOT_PATH."member.php?action=removecomment&amp;".URL_COMMENT_ID."=".$comment_row[$i]['comment_id'])."\" class=\"btn btn-outline-danger btn-sm\" title=\"".$lang['delete']."\"><i class=\"fa-solid fa-trash\"></i></a>";
      }

      $site_template->register_vars(array(
        "comment_id" => $comment_row[$i]['comment_id'],
        "comment_user_id" => $comment_user_id,
        "comment_user_status_img" => $comment_user_status_img,
        "comment_user_name" => $comment_user_name,
        "comment_user_info" => $comment_user_info,
        "comment_user_profile_button" => $comment_user_profile_button,
        "comment_user_email" => $comment_user_email,
        "comment_user_email_save" => $comment_user_email_save,
        "comment_user_mailform_link" => $comment_user_mailform_link,
        "comment_user_email_button" => $comment_user_email_button,
        "comment_user_homepage_button" => $comment_user_homepage_button,
        // ICQ support removed
        "comment_user_ip" => $comment_user_ip,
        "comment_headline" => format_text($comment_row[$i]['comment_headline'], 0, $config['wordwrap_comments'], 0, 0),
        "comment_text" => format_text($comment_row[$i]['comment_text'], $config['html_comments'], $config['wordwrap_comments'], $config['bb_comments'], $config['bb_img_comments']),
        "comment_date" => format_date($config['date_format']." ".$config['time_format'], $comment_row[$i]['comment_date']),
        "row_bg_number" => $row_bg_number,
        "admin_links" => $admin_links
      ));
      $comments .= $site_template->parse_template("comment_bit");
    } // end while
  } //end else
  $site_template->register_vars("comments", $comments);
  unset($comments);

  //-----------------------------------------------------
  //--- BBCode & Form -----------------------------------
  //-----------------------------------------------------
  $allow_posting = check_permission("auth_postcomment", $cat_id);
  $bbcode = "";
  if ($config['bb_comments'] == 1 && $allow_posting) {
    $site_template->register_vars(array(
      "lang_bbcode" => $lang['bbcode'],
      "lang_tag_prompt" => $lang['tag_prompt'],
      "lang_link_text_prompt" => $lang['link_text_prompt'],
      "lang_link_url_prompt" => $lang['link_url_prompt'],
      "lang_link_email_prompt" => $lang['link_email_prompt'],
      "lang_list_type_prompt" => $lang['list_type_prompt'],
      "lang_list_item_prompt" => $lang['list_item_prompt']
    ));
    $bbcode = $site_template->parse_template("bbcode");
  }

  if (!$allow_posting) {
    $comment_form = "";
  }
  else {
    $user_name = (isset($_POST['user_name']) && $error) ? format_text(trim(stripslashes($_POST['user_name'])), 2) : (($user_info['user_level'] != GUEST) ? format_text($user_info['user_name'], 2) : "");
    $comment_headline = (isset($_POST['comment_headline']) && $error) ? format_text(trim(stripslashes($_POST['comment_headline'])), 2) : "";
    $comment_text = (isset($_POST['comment_text']) && $error) ? format_text(trim(stripslashes($_POST['comment_text'])), 2) : "";

    $site_template->register_vars(array(
      "bbcode" => $bbcode,
      "user_name" => $user_name,
      "comment_headline" => $comment_headline,
      "comment_text" => $comment_text,
      "lang_post_comment" => $lang['post_comment'],
      "lang_name" => $lang['name'],
      "lang_headline" => $lang['headline'],
      "lang_comment" => $lang['comment'],
      "lang_captcha" => $lang['captcha'],
      "lang_captcha_desc" => $lang['captcha_desc'],
      "captcha_comments" => (bool)$captcha_enable_comments
    ));
    $comment_form = $site_template->parse_template("comment_form");
  }
  $site_template->register_vars("comment_form", $comment_form);
  unset($comment_form);
} // end if allow_comments

// Admin Links
$admin_links = "";
if ($user_info['user_level'] == ADMIN) {
  $admin_links .= "<a href=\"".$site_sess->url(ROOT_PATH."admin/index.php?goto=".urlencode("images.php?action=editimage&amp;image_id=".$image_id))."\" target=\"_blank\" class=\"btn btn-outline-warning btn-sm me-1\" title=\"".$lang['edit']."\"><i class=\"fa-solid fa-edit\"></i></a>";
  $admin_links .= "<a href=\"".$site_sess->url(ROOT_PATH."admin/index.php?goto=".urlencode("images.php?action=removeimage&amp;image_id=".$image_id))."\" target=\"_blank\" class=\"btn btn-outline-danger btn-sm\" title=\"".$lang['delete']."\"><i class=\"fa-solid fa-trash\"></i></a>";
}
elseif ($is_image_owner) {
  $admin_links .= ($config['user_edit_image'] != 1) ? "" : "<a href=\"".$site_sess->url(ROOT_PATH."member.php?action=editimage&amp;".URL_IMAGE_ID."=".$image_id)."\" class=\"btn btn-outline-warning btn-sm me-1\" title=\"".$lang['edit']."\"><i class=\"fa-solid fa-edit\"></i></a>";
  $admin_links .= ($config['user_delete_image'] != 1) ? "" : "<a href=\"".$site_sess->url(ROOT_PATH."member.php?action=removeimage&amp;".URL_IMAGE_ID."=".$image_id)."\" class=\"btn btn-outline-danger btn-sm\" title=\"".$lang['delete']."\"><i class=\"fa-solid fa-trash\"></i></a>";
}
$site_template->register_vars("admin_links", $admin_links);

// Update Hits
if ($user_info['user_level'] != ADMIN) {
  $sql = "UPDATE ".IMAGES_TABLE."
          SET image_hits = image_hits + 1
          WHERE image_id = $image_id";
  $site_db->query($sql);
}

//-----------------------------------------------------
//---Clickstream---------------------------------------
//-----------------------------------------------------
$clickstream = "<span class=\"clickstream\"><a href=\"".$site_sess->url(ROOT_PATH."index.php")."\" class=\"clickstream\">".$lang['home']."</a>".$config['category_separator'];

if ($mode == "lightbox" && $in_mode) {
  $page_url = "";
 if (preg_match("/".URL_PAGE."=([0-9]+)/", $url, $regs)) {
    if (!empty($regs[1]) && $regs[1] != 1) {
      $page_url = "?".URL_PAGE."=".$regs[1];
    }
  }
  $clickstream .= "<a href=\"".$site_sess->url(ROOT_PATH."lightbox.php".$page_url)."\" class=\"clickstream\">".$lang['lightbox']."</a>".$config['category_separator'];
}
elseif ($mode == "search" && $in_mode) {
  $page_url = "";
  if (preg_match("/".URL_PAGE."=([0-9]+)/", $url, $regs)) {
    if (!empty($regs[1]) && $regs[1] != 1) {
      $page_url = "&amp;".URL_PAGE."=".$regs[1];
    }
  }
  $clickstream .= "<a href=\"".$site_sess->url(ROOT_PATH."search.php?show_result=1".$page_url)."\" class=\"clickstream\">".$lang['search']."</a>".$config['category_separator'];
}
else {
  $clickstream .= get_category_path($cat_id, 1).$config['category_separator'];
}
$clickstream .= $image_name."</span>";

//-----------------------------------------------------
//--- Print Out ---------------------------------------
//-----------------------------------------------------
$site_template->register_vars(array(
  "msg" => $msg,
  "clickstream" => $clickstream,
  "lang_category" => $lang['category'],
  "lang_added_by" => $lang['added_by'],
  "lang_description" => $lang['description'],
  "lang_keywords" => $lang['keywords'],
  "lang_date" => $lang['date'],
  "lang_hits" => $lang['hits'],
  "lang_downloads" => $lang['downloads'],
  "lang_rating" => $lang['rating'],
  "lang_votes" => $lang['votes'],
  "lang_author" => $lang['author'],
  "lang_comment" => $lang['comment'],
  "lang_prev_image" => $lang['prev_image'],
  "lang_next_image" => $lang['next_image'],
  "lang_file_size" => $lang['file_size']
));

$site_template->print_template($site_template->parse_template($main_template));
include(ROOT_PATH.'includes/page_footer.php');
?>
