<?php
/**
 * Plugin Name: Disable Privacy Tools
 * Version: 1.1
 * Description: Disables core's privacy tools including tools for exporting/erasing personal data.
 * Author: Dominik Schilling
 * Author URI: https://dominikschilling.de/
 * Plugin URI: https://github.com/ocean90/wp-disable-privacy-tools
 * License: GPLv2 or later
 *
 *    Copyright (C) 2018 Dominik Schilling
 *
 *    This program is free software; you can redistribute it and/or
 *    modify it under the terms of the GNU General Public License
 *    as published by the Free Software Foundation; either version 2
 *    of the License, or (at your option) any later version.
 *
 *    This program is distributed in the hope that it will be useful,
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *    GNU General Public License for more details.
 *
 *    You should have received a copy of the GNU General Public License
 *    along with this program; if not, write to the Free Software
 *    Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * @package disable-privacy-tools
 */

/**
 * Removes required user's capabilities for core privacy tools by adding the
 * `do_not_allow` capability.
 *
 *  - Disables the feature pointer.
 *  - Removes the Privacy and Export/Erase Personal Data admin menu items.
 *  - Disables the privacy policy guide and update bubbles.
 *
 * @param string[] $caps    Array of the user's capabilities.
 * @param string   $cap     Capability name.
 * @return string[] Array of the user's capabilities.
 */
function ds_disable_core_privacy_tools( $caps, $cap ) {
	switch ( $cap ) {
		case 'export_others_personal_data':
		case 'erase_others_personal_data':
		case 'manage_privacy_options':
			$caps[] = 'do_not_allow';
			break;
	}

	return $caps;
}
add_filter( 'map_meta_cap', 'ds_disable_core_privacy_tools', 10, 2 );

/**
 * Short circuits the option for the privacy policy page to always return 0.
 *
 * The option is used by get_privacy_policy_url() among others.
 */
add_filter( 'pre_option_wp_page_for_privacy_policy', '__return_zero' );

/**
 * Removes the default scheduled event used to delete old export files.
 */
remove_action( 'init', 'wp_schedule_delete_old_privacy_export_files' );

/**
 * Removes the hook attached to the default scheduled event for removing
 * old export files.
 */
remove_action( 'wp_privacy_delete_old_export_files', 'wp_privacy_delete_old_export_files' );
