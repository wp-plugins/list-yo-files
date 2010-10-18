<?php
/*
Plugin Name: List Yo' Files
Plugin URI: http://www.wandererllc.com/company/plugins/listyofiles/
Description: Adds the ability to list files by file name for a given folder with hyperlinks to each file making it downloadable.  The plugin admin pages also allow you to conveniently upload and delete files.
Version: 1.00
Author: Wanderer LLC Dev Team
*/

require_once "helpers.php";

// Important ID names
define( 'LYF_LIST_YO_FILES', 'List Yo\' Files' );
define( 'LYF_USER_FOLDER', 'wp-content/list_yo_files_user_folders/' );
define( 'LYF_ADMIN', 1 );
define( 'LYF_USER', 2 );
define( 'LYF_USER_MUSIC', 3 );

// Database options
define( 'LYF_MENU_TEXT', 'lyf_menu_text' );
define( 'LYF_ENABLE_USER_FOLDERS', 'lyf_user_folders' );
define( 'LYF_MINIMUM_ROLE', 'lyf_minimum_role' );
define( 'LYF_ENABLE_ALLOWED_FILE_TYPES', 'lyf_enable_allowed_file_types' );
define( 'LYF_ALLOWED_FILE_TYPES', 'lyf_allowed_file_types' );
define( 'LYF_USER_SUBFOLDER_LIMIT', 'lyf_subfolder_limit' );
define( 'LYF_USER_USER_FOLDER_SIZE', 'lyf_user_folder_size' );

// Empty directory message
define( 'EMPTY_FOLDER', 'No files found.' );

// Various hooks and actions for this plug-in
add_shortcode( 'listyofiles', LYFShowAdminFiles );
add_shortcode( 'showmyfiles', LYFShowUserFiles );
add_shortcode( 'showmp3s', LYFShowMP3Files );

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

//
//	LYFShowAdminFiles
//
function LYFShowAdminFiles( $params )
{
	return LYFDisplayFiles( $params, LYF_ADMIN );
}

//
//	LYFShowUserFiles
//
function LYFShowUserFiles( $params )
{
	return LYFDisplayFiles( $params, LYF_USER );
}

//
//	LYFShowMP3Files
//
function LYFShowMP3Files( $params )
{
	return LYFDisplayFiles( $params, LYF_USER_MUSIC );
}

//
//	LYFDisplayFiles()
//
// 	This function reads the shortcode from the blog post or page and displays the
//	list of files for the folder requested.  Several options are allowed, see these
// 	in the $values variable.  This function ultimately generates an HTML table to
// 	display the list of files.
//
function LYFDisplayFiles( $params, $mode )
{
	// Store the various options values in an array.
	$values = shortcode_atts( array( 	'folder' => '',
									 	'link' => '',
										'sort' => '',
										'filter' => '',
										'wpaudio' => '',
										'options' => ''
									), $params );

	// Get the folder and link options.
	// Here's a difference in modes...simply generates a different folder to
	// simplify the shortcode for the user.
	if ( LYF_ADMIN === $mode )
	{
		// Read the folder as if it's constructed off the site's route.
		$folder = $values['folder'];
	}
	else
	{
		// Allow the user to pass only the name of the subfolder they want to list.
		$folder = LYF_USER_FOLDER . $values['folder'];
	}
	$link = $values['link'];
	$sort = $values['sort'];
	$filter = $values['filter'];
	// Special mode for mp3s
	if ( LYF_USER_MUSIC === $mode )
	{
		$options = $values['options'];
		$options .= 'table,wpaudio';
	}
	else
	{
		$options = $values['options'];
	}

	// Warn the user if there is no "folder" argument
	if ( empty( $folder ) )
		return "<p><em>Warning:  There is no 'folder' specified.</em></p>";

	// "link" isn't currently exposed, so this is most likely just blank.  So, set
	// it to $folder.
	if ( '' == $link )
	{
		$link = $folder;
	}

	// The $filelist variable will hold a list of files.
	$filelist = GenerateFileList( $folder, $link, $filter );
	
	print_r( $fileList );

	// if there are no items, this folder is empty.
	if( !count( $filelist ) )
	{
		// Show the user that there are no files.
		return '<p>'.EMPTY_FOLDER.'</p>';
	}
	else
	{
		// Using the list of files, generate an HTML representation of the folder.
		$output = ListFiles( $filelist, $sort, $options );
		return $output;
	}
}

//
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
	if ( ( $p = openDir( $path ) ) !== FALSE )
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

//
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
	$isWPAudio = ( FALSE !== stripos( $options, 'wpaudio' ) );
	$isWPAudioDownloadable = ( FALSE !== stripos( $options, 'wpaudiodownloadable' ) );

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

			// This part is required.  However, it can be altered by the "wpaudio" option
			if ( TRUE === $isWPAudio )
			{
				$onOff = ($isWPAudioDownloadable) ? $link : "0";
				$wpaudioProcessed = do_shortcode( '[' . "wpaudio url=\"$link\" text=\" $itemName\" dl=\"$onOff\"" . ']' );
				$retVal .= '<td>'.$wpaudioProcessed.'</td>'.PHP_EOL;
			}
			else // This is the primary element - the linked file.
			{
				// Show links in a new window or not?
				if ( $isNewWindow )
					$retVal .= '<td><a href="'.$link.'" target="_blank">'.$itemName.'</a></td>'.PHP_EOL;
				else
					$retVal .= '<td><a href="'.$link.'">'.$itemName.'</a></td>'.PHP_EOL;
			}

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

//
// AddSettingsPage()
//
// This function is called by WordPress to add settings menus to the Dashboard.  It adds two menus:
// one for uploading files and one for deleting files.
//
function AddSettingsPage()
{
	// The master menu text is dynamic.
	$menuText = get_option( LYF_MENU_TEXT );
	if ( 0 == strlen( $menuText ) )
		$menuText = LYF_LIST_YO_FILES;

	$pageText = $menuText . ' Options';

	add_menu_page( $pageText, $menuText, 'edit_published_posts', basename(__FILE__), LYFHandleAboutPage );
    add_submenu_page( basename(__FILE__) , 'Usage', 'Usage', 'edit_published_posts', basename(__FILE__), LYFHandleAboutPage );
	add_submenu_page( basename(__FILE__), 'Upload Files', 'Upload Files', 'edit_published_posts', 'Upload', LYFHandleUploadFilesPage );
    add_submenu_page( basename(__FILE__), 'Delete Files', 'Delete Files', 'edit_published_posts', 'Delete', LYFHandleDeleteFilesPage );
    add_submenu_page( basename(__FILE__), 'Administer List Yo\' Files', 'Administer', 'add_users', 'Administer', LYFHandleAdminPage );
}

//
// LYFHandleAdminPage()
//
// This function handles the all-important admin page.
//
function LYFHandleAdminPage()
{
	$menuText = get_option( LYF_MENU_TEXT );
	$restrictTypes = get_option( LYF_ENABLE_ALLOWED_FILE_TYPES );
	$allowedFileTypes = get_option( LYF_ALLOWED_FILE_TYPES );
	$enableUserFolders = get_option( LYF_ENABLE_USER_FOLDERS );
	$minimumRole = get_option( LYF_MINIMUM_ROLE );
	$subfolderCount = get_option( LYF_USER_SUBFOLDER_LIMIT );
	$folderSize = get_option( LYF_USER_USER_FOLDER_SIZE );

	// The user must be an admin to see this page
	if ( !current_user_can( 'add_users' ) )
	{
    	wp_die( __('You do not have sufficient permissions to access this page.') );
  	}

	if ( isset( $_POST['save_admin_settings'] ) )
	{
		// Security check
		check_admin_referer( 'filez-nonce' );

		// Save the menu text
		$menuText = $_POST['menu_name'];
		update_option( LYF_MENU_TEXT, $menuText );

		// File types
		$restrictTypes = $_POST['on_restrict_types'];
		update_option( LYF_ENABLE_ALLOWED_FILE_TYPES, $restrictTypes );

		if ( "on" == $restrictTypes )
		{
			$allowedFileTypes = $_POST['file_types'];
			update_option( LYF_ALLOWED_FILE_TYPES, $allowedFileTypes );
		}

		// Save various user folder options
		$enableUserFolders = $_POST['on_enable_folders'];
		update_option( LYF_ENABLE_USER_FOLDERS, $enableUserFolders );

		if ( "on" == $enableUserFolders )
		{
			$minimumRole = $_POST['minimum_role'];
			update_option( LYF_MINIMUM_ROLE, $minimumRole );

			$subfolderCount = $_POST['num_folders'];
			update_option( LYF_USER_SUBFOLDER_LIMIT, $subfolderCount );

			$folderSize = $_POST['folder_size'];
			update_option( LYF_USER_USER_FOLDER_SIZE, $folderSize );
		}
	}

	// Include the settings page here.
	include('88-files-admin.php');
}

//
// LYFHandleAboutPage()
//
// This function handles the very simple "about" page.
//
function LYFHandleAboutPage()
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

	// Include the settings page here.
	include('88-files-about.php');
}

//
// LYFHandleUploadFilesPage()
//
// This function handles the page that manages uploading files and occasionally
// creating folders.
//
function LYFHandleUploadFilesPage()
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

		$uploadFolder = ABSPATH . $_POST['upload_folder'];	
		UploadFiles( $uploadFolder );
	}
	
	// This is the handler for the users other than admins
	if ( isset($_POST['upload_user_files'] ) )
	{
		// Security check
		check_admin_referer( 'filez-nonce' );
		
		$uploadFolder = LYFGetUserUploadFolder( TRUE );
		$uploadFolder .= $_POST['upload_folder'];	
		UploadFiles( $uploadFolder );
	}

	// If a folder is being created
	if ( isset( $_POST['create_folder'] ) )
	{
		check_admin_referer( 'filez-nonce' );
		$result = LYFCreateUserFolder( $_POST['folder'] );
		$message = '<div id="message" class="updated fade">';
		$message .= LYFConvertError( $result, $_POST['folder'] );
		$message .= '</div>';
		echo $message;
	}

	// The file that will handle uploads is this one (see the "if"s above)
	$action_url = $_SERVER['REQUEST_URI'];

	// Include the settings page here.
	include('88-files-upload.php');
}

//
// LYFHandleDeleteFilesPage()
//
// This functions handles the delete files page.  It manages both displaying the page and deleting the
// files.
//
function LYFHandleDeleteFilesPage()
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
		$filelist = GenerateFileList( $_POST['folder'], $_POST['folder'], '' );

		// if there are no items, this folder is empty.
		if( !count( $filelist ) )
		{
			// Show the user an empty folder message.
			echo '<p>'.EMPTY_FOLDER.'</p>';
		}
		else
		{
			// List files to be deleted.
			echo ListFilesToDelete( $filelist, $_POST['folder'] );
		}
	}
	
	// This if block handles non-admin deletes
	if ( isset( $_POST['list_user_files'] ) )
	{
		// Security check
		check_admin_referer( 'filez-nonce' );
		
		// Generate the folder to list
		$listFolder = LYFGetUserUploadFolder( TRUE );
		$listFolder .= $_POST['folder'];

		// This function will generate an array of any file in the folder to be deleted.
		$filelist = GenerateFileList( $listFolder, $listFolder, '' );

		// if there are no items, this folder is empty.
		if( !count( $filelist ) )
		{
			// Show the user an empty folder message.
			echo '<p>'.EMPTY_FOLDER.'</p>';
		}
		else
		{
			// List files to be deleted.
			echo ListFilesToDelete( $filelist, $listFolder );
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
		echo '<div id="message" class="updated fade"><p>' . $file . ' has been deleted.</p></div>';

		// Regenerate the list of files now that one of them has been deleted.
		$filelist = GenerateFileList( $folder, $folder, "" );

		// if there are no items, this folder is empty.
		if( !count( $filelist ) )
		{
			// Show the empty message.
			echo '<p>'.EMPTY_FOLDER.'</p>';
		}
		else
		{
			// Show the files to delete again.
			echo ListFilesToDelete( $filelist, $folder );
		}
	}
}

?>