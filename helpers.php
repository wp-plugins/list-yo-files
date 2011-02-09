<?php

// Define error and success codes
define( 'LYF_ERROR_CREATE_FOLDER_PERMISSIONS', -10 );
define( 'LYF_ERROR_CREATE_FOLDER_EXISTS', -11 );
define( 'LYF_ERROR_ILLEGAL_CHARACTERS', -12 );
define( 'LYF_ERROR_NO_FOLDER_NAME', -13 );

define( 'LYF_SUCCESS_CREATE_FOLDER', 10 );

function LYFFormatFileSize( $size )
{
	if ( strlen($size) <= 9 && strlen($size) >= 7 )
	{
		$size = number_format( $size / 1048576, 1 );
		return "$size MB";
	}
	elseif ( strlen( $size ) >= 10 )
	{
		$size = number_format( $size / 1073741824, 1 );
		return "$size GB";
	}
	else
	{
		$size = number_format( $size / 1024, 1 );
		return "$size KB";
	}
}

//
//	LYFGetFolderSize
//
function LYFGetFolderSize( $directory )
{
	$size = 0;

	// if the path has a slash at the end, remove it here
	if( substr( $directory,-1 ) == '/' )
	{
		$directory = substr($directory,0,-1);
	}

	// if the path is not valid or is not a directory ...
	if( !file_exists( $directory ) || !is_dir( $directory ) || !is_readable( $directory ) )
	{
		// ...return -1 and exit the function
		return -1;
	}

	// open the directory
	if( $handle = opendir( $directory ) )
	{
		// and scan through the items inside
		while ( ( $file = readdir( $handle ) ) !== false )
		{
			// build the new path
			$path = $directory.'/'.$file;

			// if the filepointer is not the current directory
			// or the parent directory
			if( $file != '.' && $file != '..' )
			{
				// if the new path is a file
				if( is_file( $path ) )
				{
					// add the filesize to the total size
					$size += filesize( $path );

					// if the new path is a directory
				}
				elseif ( is_dir( $path ) )
				{
					// Call this function with the new path
					$handlesize = LYFGetFolderSize($path);

					// if the function returns more than zero
					if( $handlesize >= 0 )
					{
						// add the result to the total size
						$size += $handlesize;

					// else return -1 and exit the function
					}
					else
					{
						closedir( $handle );
						return -1;
					}
				}
			}
		}
		// close the directory
		closedir( $handle );
	}
	return $size;
}

//
//	LYFRemoveDirectory
//
//	Recursively deletes a folder.  Can you tell I lifted this code?
//
function LYFRemoveDirectory( $directory )
{
	// if the path has a slash at the end we remove it here
	if ( substr( $directory, -1 ) == '/' )
	{
		$directory = substr($directory,0,-1);
	}

	// if the path is not valid or is not a directory ...
	if ( !file_exists( $directory ) || !is_dir( $directory ) )
	{
		// ... we return false and exit the function
		return FALSE;

	// ... if the path is not readable
	}
	elseif( !is_readable( $directory ) )
	{
		// ... we return false and exit the function
		return FALSE;

	// ... else if the path is readable
	}
	else
	{
		// we open the directory
		$handle = opendir($directory);

		// and scan through the items inside
		while ( FALSE !== ( $item = readdir( $handle ) ) )
		{
			// if the filepointer is not the current directory
			// or the parent directory
			if( $item != '.' && $item != '..' )
			{
				// we build the new path to delete
				$path = $directory.'/'.$item;

				// if the new path is a directory
				if( is_dir( $path ) )
				{
					// we call this function with the new path
					recursive_remove_directory( $path );

					// if the new path is a file
				}
				else
				{
					// we remove the file
					unlink( $path );
				}
			}
		}
		// close the directory
		closedir( $handle );

		// try to delete the now empty directory
		if( !rmdir( $directory ) )
		{
			// return false if not possible
			return FALSE;
		}

		// return success
		return TRUE;
	}
}


//
//	LYFGetUserUploadFolder
//
//	This function guarantees a terminating slash.
//
function LYFGetUserUploadFolder( $isAbsolute )
{
	// Get this user's user name.  User folders are created with that name.
	global $current_user;
	get_currentuserinfo();

	if ( $isAbsolute )
		return trailingslashit( ABSPATH . LYF_USER_FOLDER . $current_user->user_login );
	else
		return trailingslashit( LYF_USER_FOLDER . $current_user->user_login );
}

//
//	LYFCreateUserFolder()
//
//	This function creates the requested user folder.  A user folder is a
//	special folder created in a special place.  Only end users typically
//	use this function.
//
function LYFCreateUserFolder( $folderName )
{
	// Check if the folder exists
	if ( !is_dir( $folderName ) )
	{
		// If not, create the folder.  Let the user know if something goes wrong.
		// NOTE:  The recursive creation argument exists because of the very first
		// time these folders are created OR for when an admin is using the plugin.
		// Suppression of creating multiple folders should be suppressed at a higher
		// level.
		if ( !mkdir( $folderName, 0777, TRUE ) )
		{
			return LYF_ERROR_CREATE_FOLDER_PERMISSIONS;
		}
	}
	else
	{
		return LYF_ERROR_CREATE_FOLDER_EXISTS;
	}

	// Arriving here means success
	return LYF_SUCCESS_CREATE_FOLDER;
}

//
//	LYFGetRolesAndCapabilities
//
//	All of the strings you see in this array are WordPress standards.
//	See http://codex.wordpress.org/Roles_and_Capabilities for more.
//
function LYFGetRolesAndCapabilities()
{
	$rolesArray = array(ADMINISTRATOR => 'delete_users',
						'Editor' => 'publish_pages',
						'Author' => 'upload_files',
						'Contributor' => 'edit_posts',
						'Subscriber' => 'read' );
	return $rolesArray;
}

//
//	LYFConvertError()
//
function LYFConvertError( $error, $userMessage )
{
	$message = '';
	switch( $error )
	{
		case LYF_ERROR_CREATE_FOLDER_PERMISSIONS:
			$message = sprintf( __('<strong>Failed</strong> to create the subfolder "%s".  Make sure your server file permissions are correct or contact support.'), $userMessage );
			break;
		case LYF_ERROR_CREATE_FOLDER_EXISTS:
			$message = sprintf( __('<strong>Failed</strong> to create the subfolder "%s" because it already exists.  Choose a different folder name.'), $userMessage );
			break;
		case LYF_ERROR_ILLEGAL_CHARACTERS:
			$message = __('<strong>Failed</strong> to create the subfolder because it contains some illegal characters.');
			break;
		case LYF_ERROR_NO_FOLDER_NAME:
			$message = __('<strong>Failed</strong> to create the subfolder because it has no name.');
			break;
		case LYF_SUCCESS_CREATE_FOLDER:
			$message = sprintf( __('The subfolder "%s" was successfully created.'), $userMessage );
			break;
		default:
			break;
	}
	return $message;
}

//
//	LYFConvertUploadError
//
function LYFConvertUploadError( $error )
{
	$message = '';
	switch( $error )
	{
		case 1:
			$message = __('the file exceeded the maximum upload size allowed');
			break;
		case 2:
			$message = __('the file exceeded the form\'s maximum upload size');
			break;
		case 3:
			$message = __('the file was only partially uploaded');
			break;
		case 4:
			$message = __('no file was uploaded');
			break;
		case 6:
			$message = __('no temporary directory exists');
			break;
		case 7:
			$message = __('the file failed to write to disk');
			break;
		case 8:
			$message = __('the upload was prevented by an extension');
			break;
		default:
			break;
	}
	return $message;
}

//
//	LYFIsValidFolderName()
//
//	This function just checks if some bad foldername characters exist.
//
function LYFIsValidFolderName( $folderName )
{
	if ( 0 === strlen( $folderName ) )
		return LYF_ERROR_NO_FOLDER_NAME;
	else if ( FALSE === stripos( $folderName, '/' ) )
		return 1;
	else
		return LYF_ERROR_ILLEGAL_CHARACTERS;
}

//
//	LYFUploadFiles()
//
//	This function uploads a list of files into a folder.
//
function LYFUploadFiles( $folder )
{
	// Get these variables.  Needed to determine if there are restrictions on
	// extensions and if there is still room to upload.
	$restrictTypes = get_option( LYF_ENABLE_ALLOWED_FILE_TYPES );
	$allowedFileTypes = get_option( LYF_ALLOWED_FILE_TYPES );
	$maxFolderSize = get_option( LYF_USER_USER_FOLDER_SIZE );

	// Using the "updated fade" class to make the resulting message prominent.
	echo '<div id="message" class="updated fade">';

	// Count the number of legit files found (for error reporting)
	$count = 0;

	// Check if the folder exists
	$res = is_dir( $folder );
	// If you use this, warnings will occur.  You must also close the directory.
	// But is_dir() sometimes behaves oddly with absolute paths.
//	$res = opendir( $folder );
	if ( FALSE === $res )
	{
		$roles = LYFGetRolesAndCapabilities();
		if ( !current_user_can( $roles[ADMINISTRATOR] ) )
		{
			$accessMessage = sprintf( __('There was a problem accessing the folder: "%s".'), $folder );
			echo '<p>' . $accessMessage . '</p></div>';
			return;
		}

		// If not, create the folder.  Let the user know if something goes wrong.
		if ( !mkdir( $folder ) )
		{
			echo '<p>' . __('<strong>Failed</strong> to create the folder.  Make sure your server file permissions are correct.') . '</p></div>';
			return;
		}
	}

	// There are up to 10 files that can be uploaded yet.
	foreach ( $_FILES as $file )
	{
		// I don't know why there's an extra blank file in here.  Sorry about this hack.
		if ( '' == $file['name'] )
			continue;

		// At least one file was found
		$count++;

		if ( 'on' == $restrictTypes )
		{
			$ext = substr( strrchr( $file['name'], '.' ), 1 );
			if ( FALSE === stristr( $allowedFileTypes, $ext ) )
			{
				$failedMessage = sprintf( __("<strong>Failed</strong> to upload %1$s because '%2$s' files are not allowed."), $file['name'], $ext );
				echo '<p>' . $failedMessage . '</p>';
				continue;
			}
		}

		if ( 0 != $file['error'] )
		{
			$errorString = LYFConvertUploadError( $file['error'] );
			$failedMessage = sprintf( __("<strong>Failed</strong> to upload %1$s because %2$s."), $file['name'], $errorString );
			echo '<p>' . $failedMessage . '</p>';
			continue;
		}

		// $final_name holds the full path to the file.
		$final_name = $folder . '/' . $file['name'];

		// Copy the file over...
		$success = copy( $file['tmp_name'], $final_name );

		// ...and report the results of the upload.
		if ( $success )
		{
			$successMessage = sprintf( __("<strong>Successfully</strong> uploaded %s."), $file['name'] );
			echo "<p>$successMessage</p>";
		}
		else
		{
			$failedMessage = sprintf( __("<strong>Failed</strong> to copy over the file %s. Check your folder permissions."), $file['name'] );
			echo "<p>$failedMessage</p>";
		}
	}

	// Show an error on an empty list of files
	if ( 0 == $count )
	{
		echo '<p>' . __('There are no files to upload.  Browse for files to upload first.') . '</p>';
	}

	echo '</div>';
}

//
//	Generate a folder list
//
function LYFGenerateFolderList( $path )
{
	// Store the folders in an array
	$folders = array();

	// Scan the folder
//	$contents = scandir( $path );
//	foreach ( $contents as $item )
	foreach ( glob( $path . '*', GLOB_ONLYDIR ) as $item )
	{
		// Ignore all files starting with a .
		if ( substr( $item, 0, 1 ) != '.' )
		{
				$folders[] = basename( $item );
		}
	}
	return $folders;
}

// LYFListFilesToDelete()
//
// This function is very similar to the "LYFListFiles()" function.  The only difference is that this function
// generates a list of files to be deleted in the Settings page.  So, the admin will only see the results
// of this function, not your average site user.
//
function LYFListFilesToDelete( $filelist, $folder )
{
	// sort the items
	uksort( $filelist, 'strnatcasecmp' );

	$files = '';

	// Generate a table entry for each file, showing the file name, the folder, and a "Delete" link.
	foreach( $filelist as $itemName => $item )
	{
		$fileSize = LYFFormatFileSize( $item['size'] );
		$link = wp_nonce_url( "admin.php?page=Delete&amp;tab=del&amp;id=$itemName", 'filez-nonce' );
		$files .= '<tr class="alternate"><td>' . $itemName . '</td><td>' . $fileSize . '</td><td><a href="' . $link . '&amp;folder=' . $folder . '" class="delete">' . __('Delete') . '</a></td></tr>';
	}

	// Set the output
	$output = $files;

	// Encase the ouput in class and ID
	$retval = '';
	$retVal .= '<div id=\'filelist\'>';
	$retVal .= '<table class="widefat" style="width:710px">
			<thead>
			<tr>
				<th scope="col">' . __('Name') . '</th>
				<th scope="col">' . __('Size') . '</th>
				<th scope="col">' . __('Delete') . '</th>
			</tr>
			</thead>';
	$retVal .= $output . PHP_EOL;
	$retVal .= '</table>' . PHP_EOL . '</div>' . PHP_EOL;

	// return the list
	return $retVal;
}

function LYFGetMP3Code( $userFolder, $folder )
{
	return '[showmp3s folder="'.$userFolder.'/'.$folder.'"]';
}

function LYFShowFilesCode( $userFolder, $folder )
{
	return '[showfiles folder="'.$userFolder.'/'.$folder.'" options="table,date,filesize,icon"]';
}

//
// Sort functions for List Yo' Files associative arrays
//
function ReverseFileSizeSort( $x, $y )
{
	return ( $x['size'] > $y['size'] );
}

function FileSizeSort( $x, $y )
{
	return ( $y['size'] > $x['size'] );
}

function ReverseDateSort( $x, $y )
{
	return ( $x['date'] > $y['date'] );
}

function DateSort( $x, $y )
{
	return ( $y['date'] > $x['date'] );
}

?>