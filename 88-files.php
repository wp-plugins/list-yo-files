<?php
/*
Plugin Name: List Yo' Files
Plugin URI: http://www.wandererllc.com/company/plugins/listyofiles/
Description: Adds the ability to list files by file name for a given folder with hyperlinks to each file making it downloadable.  The plugin admin pages also allow you to conveniently upload and delete files.
Version: 0.83
Author: Wanderer LLC Dev Team
*/

require_once "helpers.php";

// Empty directory message
$EMPTY_FOLDER = 'No files found.';

// Various hooks and actions for this plug-in
add_shortcode( 'listyofiles', DisplayFiles );
add_action( 'admin_menu', AddSettingsPage );
add_filter( 'plugin_row_meta', 'AddListYoFilesPluginLinks', 10, 2 ); // Expand the links on the plugins page

// Inspired by NextGen Gallery by Alex Rabe
function AddListYoFilesPluginLinks($links, $file)
{
	if ( $file == plugin_basename(__FILE__) )
	{
		$links[] = '<a href="http://wordpress.org/extend/plugins/list-yo-files/">' . __('Overview', 'list-yo-files') . '</a>';
		$links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=TC7MECF2DJHHY&lc=US">' . __('Donate', 'list-yo-files') . '</a>';
	}
	return $links;
}

// Global counter for distinguishing multiple lists
$fileListCounter = 1;

// DisplayFiles()
//
// This function reads the shortcode from the blog post or page and displays the
// list of files for the folder requested.  Several options are allowed, see these
// in the $values variable.  This function ultimately generates an HTML table to
// display the list of files.
function DisplayFiles( $params )
{
	// Store the various options values in an array.
	$values = shortcode_atts( array
		( 'folder' => '',
		  'link' => '',
		  'sort' => '',
		  'filter' => '',
		  'options' => ''
		), $params );

	// Get the folder and link options.
	$folder = $values['folder'];
	$link = $values['link'];
	$sort = $values['sort'];
	$filter = $values['filter'];
	$options = $values['options'];

	// Warn the user if there is no "folder" argument
	if ( empty( $folder ) )
		return "<p><em>List Yo' Files Warning:  There is no 'folder' argument specified.</em></p>";

	// "link" isn't currently exposed, so this is most likely just blank.  So, set
	// it to $folder.
	if ( '' == $link )
	{
		$link = $folder;
	}

	// The $filelist variable will hold a list of files.
	$filelist = GenerateFileList( $folder, $link, $filter );

	// if there are no items, this folder is empty.
	if( !count( $filelist ) )
	{
		// Show the user that there are no files.
		return '<p>'.EMPTY_FOLDER.'</p>';
	}
	else
	{
		// Using the list of files, generate an HTML representation of the folder.
		return ListFiles( $filelist, $sort, $options );
	}
}

// GenerateFileList()
//
// @param $path - the folder to list, relative the the WordPress installation.
// @param $linkTarget - currently unused and requested to be the exact same
//	value as $path.  With this relative path info, the function will loop
//	through each file matching the criteria and add resulting files to a list
//	which are returned.
// @param $filter - Pass a filter ('*.txt', for example) to apply to the list
//	of files to display.
//
function GenerateFileList( $path, $linkTarget, $filter )
{
	// array to build the list in
	$filelist = array();

	// Convert to the absolute path
	$path = ABSPATH . $path;

	// Attempt to open the folder
	if ( ( $p = openDir( $path ) ) !== false )
	{
		// Read the directory for items inside it.
		while ( ( $item = readDir( $p ) ) !== false )
		{
			// Find the file's extension then determine if a filter is turned
			// on and this file type fits the filter.
			$ext = substr( strrchr( $item, '.' ), 1 );
			$canView = true;
			if ( !empty( $filter ) )
			{
				if ( FALSE === stripos( $filter, $ext ) )
					$canView = false;
			}

			// Exclude dotfiles, current, and parent dirs.  Also skip if the
			// filter doesn't yield a match.
			if ( $item[0] != '.' && $canView )
			{
				// Set up the relative path to the item.
				$newPath = $path.'/'.$item;
				$newTarget = $linkTarget.'/'.$item;

				// If current item is a file, do more stuff.  Otherwise, just skip it.
				if ( is_file( $newPath ) )
				{
					// Special processing for links.  Read the path to the link and store it.
					if ( function_exists( 'is_link' ) && is_link( $newPath ) )
						$filelist[$item]['slTarget'] = readlink( $newPath );

					// Save the paths.
					$filelist[$item]['path'] = $newPath;
					$filelist[$item]['link'] = $newTarget;
					$filelist[$item]['size'] = filesize( $newPath );
					$filelist[$item]['date'] = filemtime( $newPath );
				}
			}
		}
		closeDir($p);
	}
	return $filelist;
}

// ListFiles()
//
// This function takes a list of files and generates an HTML table to show them inside.
//
function ListFiles( $filelist, $sort, $options )
{
	// Use this as a static variable
	global $fileListCounter;

	// Sort the items
	if ( 'reverse_alphabetic' == $sort )
	{
		// Reverse alphabetically sort
		krsort( $filelist );
	}
	elseif ( 'reverse_filesize' == $sort )
	{
		uasort( $filelist, 'ReverseFileSizeSort' );
	}
	elseif ( 'filesize' == $sort )
	{
		uasort( $filelist, 'FileSizeSort' );
	}
	elseif ( 'reverse_date' == $sort )
	{
		uasort( $filelist, 'ReverseDateSort' );
	}
	elseif ( 'date' == $sort )
	{
		uasort( $filelist, 'DateSort' );
	}
	else
	{
		// By default, alphabetically sort
		ksort( $filelist );
	}

	// Convert options into booleans

	$files = '';

	// Get the URL to the blog.  The path to the files will be added to this.
	$wpurl = get_bloginfo( "wpurl" );
	
	// Get the various options
	$isTable = ( FALSE !== stripos( $options, 'table' ) );
	$isNewWindow = ( FALSE !== stripos( $options, 'new_window' ) );
	$isFilesize = ( FALSE !== stripos( $options, 'filesize' ) );
	$isDate = ( FALSE !== stripos( $options, 'date' ) );
	$isIcon = ( FALSE !== stripos( $options, 'icon' ) );

	// Start generating the HTML
	$retVal = "<div id='filelist$fileListCounter'>";

	// Generate either a table or a list based on the user's options
	if ( $isTable )
	{
		$retVal .= '<table width="100%" border="0" cellpadding="7">'.PHP_EOL;
		foreach( $filelist as $itemName => $item )
		{
			// Get file variables
			$size = FormatFileSize( $item['size'] );
			$date = date( "F j, Y", $item['date'] );
			$link = $wpurl.'/'.$item['link'];

			// Generate list elements

			// Generate a column for icons
			if ( $isIcon )
			{
				$ext = substr( strrchr( $item['link'], '.' ), 1 );
				$ext = strtolower( $ext );
				$pluginFolder = $wpurl . '/wp-content/plugins/' . dirname( plugin_basename( __FILE__ ) ) . '/';
				$extensionFile = $pluginFolder . "icons/$ext.png";
				$theFile = @file( $extensionFile );
				// If a file for this extension doesn't exist, then load the generic icon
				if ( FALSE === $theFile )
					$extensionFile = $pluginFolder . "icons/generic.png";
				$retVal .= '<td><img src="'.$extensionFile.'"></td>'.PHP_EOL;
			}

			// This part is required.  But show links in a new window or not?
			if ( $isNewWindow )
				$retVal .= '<td><a href="'.$link.'" target="_blank">'.$itemName.'</a></td>'.PHP_EOL;
			else
				$retVal .= '<td><a href="'.$link.'">'.$itemName.'</a></td>'.PHP_EOL;

			// Show the file size
			if ( $isFilesize )
				$retVal .= '<td>'.$size.'</td>'.PHP_EOL;

			// Show the date
			if ( $isDate )
				$retVal .= '<td>'.$date.'</td>'.PHP_EOL;

			$retVal .= '</tr>';
		}
		$retVal .= '</table>'.PHP_EOL;
	}
	else
	{
		foreach( $filelist as $itemName => $item )
		{
			// Get file variables
			$size = FormatFileSize( $item['size'] );
			$date = date( "F j, Y", $item['date'] );
			$link = $wpurl.'/'.$item['link'];
			// Generate list elements
			if ( $isNewWindow )
				$files .= '<li><a href="'.$link.'" target="_blank">'.$itemName.'</a>';
			else
				$files .= '<li><a href="'.$link.'">'.$itemName.'</a>';
			if ( $isFilesize )
				$files .= ' Size: ' . $size . PHP_EOL;
			if ( $isDate )
				$files .= ' Date: ' . $date . PHP_EOL;
			$files .='</li>'.PHP_EOL;
		}

		// Encase the ouput in class and ID
		$fileListCounter++;
		$retVal .= '<ul>'.PHP_EOL.$files.'</ul>'.PHP_EOL;
	}

	// Close out the div
	$retVal .= '</div>'.PHP_EOL;

	// return the HTML
	return $retVal;
}

// ListFilesToDelete()
//
// This function is very similar to the "ListFiles()" function.  The only difference is that this function
// generates a list of files to be deleted in the Settings page.  So, the admin will only see the results
// of this function, not your average site user.
//
function ListFilesToDelete( $filelist, $folder )
{
	// sort the items
	uksort( $filelist, 'strnatcasecmp' );

	$files = '';

	// Generate a table entry for each file, showing the file name, the folder, and a "Delete" link.
	foreach( $filelist as $itemName => $item )
	{
		$files .= '<tr class="alternate"><td>' . $itemName . '</td><td><a href="admin.php?page=Delete&amp;tab=del&amp;id=' . $itemName . '&amp;folder=' . $folder . '" class="delete">Delete</a></td></tr>';
	}

	// Set the output
	$output = $files;

	// Encase the ouput in class and ID
	$retval = '';
	$retVal .= '<div id=\'filelist\'>';
	$retVal .= '<table class="widefat">
			<thead>
			<tr>
				<th scope="col">Name</th>
				<th scope="col">Delete</th>
			</tr>
			</thead>';
	$retVal .= $output . PHP_EOL;
	$retVal .= '</table>' . PHP_EOL . '</div>' . PHP_EOL;

	// return the list
	return $retVal;
}

// AddSettingsPage()
//
// This function is called by WordPress to add settings menus to the Dashboard.  It adds two menus:
// one for uploading files and one for deleting files.
//
function AddSettingsPage()
{
	add_menu_page( 'List Yo\' Files Options', 'List Yo\' Files', 'edit_published_posts', basename(__FILE__), HandleAboutPage );
    add_submenu_page( basename(__FILE__), 'Upload Files', 'Upload Files', 'edit_published_posts', 'Upload', HandleUploadFilesPage );
    add_submenu_page( basename(__FILE__), 'Delete Files', 'Delete Files', 'delete_published_pages', 'Delete', HandleDeleteFilesPage );
}

// HandleAboutPage()
//
// This function handles the very simple "about" page.
//
function HandleAboutPage()
{
	// Stop the user if they don't have permission
	if ( !current_user_can( 'edit_published_posts' ) )
	{
    	wp_die( __('You do not have sufficient permissions to access this page.') );
  	}

	// Include the settings page here.
	include('88-files-about.php');
}

// HandleUploadFilesPage()
//
// This function handles the page that manages uploading files and occasionally
// creating folders.
//
function HandleUploadFilesPage()
{
	// Stop the user if they don't have permission
	if ( !current_user_can( 'edit_published_posts' ) )
	{
    	wp_die( __('You do not have sufficient permissions to access this page.') );
  	}

  	// If the upload_files POST option is set, then files are being uploaded
	if ( isset( $_POST['upload_files'] ) )
	{
		// Security check
		check_admin_referer( 'filez-nonce' );
		UploadFiles( $_POST['folder'] );
	}

	// If a folder is being created
	if ( isset( $_POST['create_folder'] ) )
	{
		check_admin_referer( 'filez-nonce' );
		CreateFolder( $_POST['new_folder'] );
	}

	// The file that will handle uploads is this one (see the "if"s above)
	$action_url = $_SERVER['REQUEST_URI'];

	// Include the settings page here.
	include('88-files-upload.php');
}

// HandleDeleteFilesPage()
//
// This functions handles the delete files page.  It manages both displaying the page and deleting the
// files.
//
function HandleDeleteFilesPage()
{
	// Stop the user if they don't have permission
	if ( !current_user_can( 'delete_published_pages' ) )
	{
    	wp_die( __('You do not have sufficient permissions to access this page.') );
  	}

  	// This file will handle the deleting when "Delete" is pressed.
	$action_url = $_SERVER['REQUEST_URI'];

	// Here's the source file which displays the page.  This is shown first because delete options are
	// shown at the bottom.
	include( '88-files-delete.php' );

	// If the "list_files" POST option is set, then the user has requested to see the files in a folder.
	if ( isset( $_POST['list_files'] ) )
	{
		// Security check
		check_admin_referer( 'filez-nonce' );

		// This function will generate an array of any file in the folder to be deleted.
		$filelist = GenerateFileList( $_POST['folder'], $_POST['folder'], "" );

		// if there are no items, this folder is empty.
		if( !count( $filelist ) )
		{
			// Show the user an empty folder message.
			echo '<p>'.EMPTY_DIR.'</p>';
		}
		else
		{
			// List files to be deleted.
			echo ListFilesToDelete( $filelist, $_POST['folder'] );
		}
	}

	// If a GET value was passed, then the user wants to delete a file.
	if ( isset( $_GET['id'] ) )
	{
		// Both the file and folder were passed, so save these off.
		$file = $_GET['id'];
		$folder = $_GET['folder'];

		// This is the PHP DeleteFile() function...nice name, huh?  This function is assumed to work.
		// Probably should add some error handling here.
		unlink( ABSPATH . $folder . '/' . $file );

		// The "updated fade" class is that cool faded text area.
		echo '<div id="message" class="updated fade"><p>' . $file . ' from ' . $folder . ' has been deleted.</p></div>';

		// Regenerate the list of files now that one of them has been deleted.
		$filelist = GenerateFileList( $folder, $folder, "" );

		// if there are no items, this folder is empty.
		if( !count( $filelist ) )
		{
			// Show the empty message.
			echo '<p>'.EMPTY_DIR.'</p>';
		}
		else
		{
			// Show the files to delete again.
			echo ListFilesToDelete( $filelist, $folder );
		}
	}
}

// UploadFiles()
//
// This function uploads a list of files into a folder.
//
function UploadFiles( $folder )
{
	// Assemble the full path and ensure there is a trailing slash.
	$upload_folder = trailingslashit( ABSPATH . $folder );

	// Using the "updated fade" class to make the resulting message prominent.
	echo '<div id="message" class="updated fade">';

	// Check if the folder exists
	if ( !is_dir( $upload_folder ) )
	{
		// If not, create the folder.  Let the user know if something goes wrong.
		if ( !mkdir( $upload_folder ) )
		{
			echo '<p><strong>Failed</strong> to create the folder ' . $upload_folder . '.  Make sure your server file permissions are correct.</p>';
			// Reset the upload data.  No upload will happen until a folder can be created.
			$_FILES = array();
		}
	}

	// There are up to 10 files that can be uploaded yet.
	foreach ( $_FILES as $file )
	{
		// I don't know why there's an extra blank file in here.  Not much of a PHP dork yet.
		// Sorry for this hack.
		if ( '' == $file['tmp_name'] )
			continue;

		// $final_name holds the full path to the file.
		$final_name = $upload_folder . $file['name'];

		// Copy the file over...
		$success = copy( $file['tmp_name'], $final_name );

		// ...and report the results of the upload.
		if ( $success )
		{
			echo '<p><strong>Successfully uploaded ' .$file['name']. '.</strong></p>';
		}
		else
		{
			echo '<p>Error occurred on ' .$file['name']. ' with code ' . $file['error'] . '</p>';
		}
	}
	echo '</div>';
}

/*function ApplyFilter( $files )
{
	$filter = $shortcode[ 'filter' ];
	if ( '' == $filter )
		return;

	for each( $file in $files )
	{
		$found = strstr( $file->extension, $filter );
		if ( !$found )
		{
			$file->show = false;
		}
	}

	return $files;
}
*/

function SetOptions()
{
	if ( '' == $shortcode[ 'filter' ] )
	{
		$filterOption = true;
	}

	$options = $shortcode[ 'options' ];
	$found = strstr( "icon", $options );
	{
		$iconOption = true;
	}

	// Repeat same for "time" and "size".  Although, "time" is only legit if "mp3" is a filter.
	// Use a class to keep track of the options and the filter.
}

?>