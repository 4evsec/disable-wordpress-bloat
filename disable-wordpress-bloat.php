<?php
/**
 * Plugin Name: Disable WordPress Bloat
 * Description: to disable if display issues are encountered (this plugin 
 * deregisters jquery)
 * Version: 0.1.1
 * Author: 4evsec
 */

if (!defined('ABSPATH')) {
  exit;
}

function _disable_emojis_tinymce($plugins)
{
  if (is_array($plugins)) {
    return array_diff($plugins, ['wpemoji']);
  } else {
    return [];
  }
}

function disable_emojis()
{
  remove_action('wp_head', 'print_emoji_detection_script', 7);
  remove_action('admin_print_scripts', 'print_emoji_detection_script');
  remove_action('wp_print_styles', 'print_emoji_styles');
  remove_action('admin_print_styles', 'print_emoji_styles');
  remove_filter('the_content_feed', 'wp_staticize_emoji');
  remove_filter('comment_text_rss', 'wp_staticize_emoji');
  remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

  add_filter('tiny_mce_plugins', '_disable_emojis_tinymce');
}

function hide_xmlrpc()
{
  add_filter('xmlrpc_enabled', '__return_false');
  remove_action('wp_head', 'rsd_link');
}

function hide_wp_version()
{
  add_filter('the_generator', '__return_empty_string');
}

function deregister_jquery()
{
  if (!is_admin()) {
    wp_deregister_script('jquery');
  }
}

add_action('init', 'disable_emojis');
add_action('init', 'hide_xmlrpc');
add_action('init', 'hide_wp_version');
add_action('init', 'deregister_jquery');
