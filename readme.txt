=== List Yo' Files ===
Plugin Name: List Yo' Files
Contributors: Wanderer LLC
Plugin URI: http://www.wandererllc.com/company/plugins/listyofiles/
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=TC7MECF2DJHHY&lc=US
Tags: admin, files, upload, download, FTP, display, list, show, ul, li, table
Requires at least: 2.8
Tested up to: 3.0
Stable tag: 0.83

== Description ==

Adds the ability to list files by file name for the given folder with hyperlinks to each file making it downloadable.  You can include file size, date, and even an icon as part of the file list.  You can sort by filename, size, or date (and reverse).  The plugin admin pages also allow you to conveniently upload and delete files.  This is a convenient way for organizations, groups, and clubs to share files with members.  For example, Home Owner Associations have used this plugin to list their minutes.  Music websites use this plugin to show a list of downloadable sample files to visitors.  

== Screenshots ==

1. The Upload Files UI provides a convenient place to upload files to a specified folder.
2. The Delete Files UI provides you a basic way to delete files in a given folder.  For a more powerful solution, use any popular FTP client.   

== Frequently Asked Questions ==

= What's the secret code for displaying a list of files in my pages or posts? =

To use, add the List Yo' Files shortcode ("listyofiles") enclosed in brackets to the text of your page or post and specify the folder name with the "folder" directive.  For example:  [listyofiles folder="wp-content/gallery/my-new-gallery"]  The plugin will then generate a list of files with a link to each so that every file is downloadable.  See the plugin About page for more instructions.

= The file icon that I want to display isn't available.  What can I do? =

You can upload a 16x16 png file for the file type that you'd like to support.  The name of the file needs to match the extension that you want to display.  All letters should be lowercase.  For example, if you want to provide an icon for mp3 files, you would need to upload a file called "mp3.png" to the plugin's "icons" folder.

If you want to share a single icon across many different file types, then, for now, you need to duplicate each png file for each extension that you want to support.

= How do I make suggestions or report bugs for this plugin? =

Just go to <http://www.wandererllc.com/company/plugins/listyofiles/> and follow the instructions.

== Changelog ==

= 0.83 =

* Fixed the "icon" feature to be turned off by default.
* Icon extensions are now case insensitive.
* Using a method that does not generate a warning when a file cannot be loaded.
* Added icons for mp3, htm, and html.

= 0.82 =

* Added an "icon" feature which adds icons to file lists which are based on the "table" option.  Includes a small set of 16x16 icons.  Users can add their own icon files by uploading to the plugin's 'icon' folder a .png file with the file name matching the extension of the file you want to provide an icon for.  For example:  "mp3.png", "pdf.png", etc.

= 0.81 =

* Bug fix: Upload UI supports uploading 10 files at once.

= 0.8 =

* Added a "sort" option for sorting files.
* Added a "filter" option for filtering files based on file extension.
* Added an "options" option for supporting special options in your list like tables and extra fields.
* Upload feature is available to Authors and up.  Delete feature is available to Editors and up.
* Each file list now has a unique ID.
* Leaving "folder" argument undeclared now produces a text warning instead of listing the WordPress root folder.

== Upgrade Notice ==

= 0.83 =

Recommended for users of 0.82.  Fixes non-critical bugs.

= 0.82 =

Non-critical update for users who want to have a file icon in front of their file.

= 0.81 =

Recommended for all users.  Fixed the Uploads page so that 10 files can be uploaded at once.

= 0.8 =

Recommended for all users.  Several feature updates plus fixed this issue of displaying WordPress's root files if no "folder" argument is specified.

== License ==

This file is part of List Yo' Files.

List Yo' Files is free software:  you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.  

List Yo' Files is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

See the license at <http://www.gnu.org/licenses/>.