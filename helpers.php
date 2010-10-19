<?php

// Define error and success codes
define( 'LYF_ERROR_CREATE_FOLDER_PERMISSIONS', -10 );
define( 'LYF_ERROR_CREATE_FOLDER_EXISTS', -11 );

define( 'LYF_SUCCESS_CREATE_FOLDER', 10 );

function FormatFileSize( $size )
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
	// Assemble the full path and ensure there is a trailing slash.
	$createFolder = LYFGetUserUploadFolder();
	$createFolder .= $folderName;

	// Check if the folder exists
	if ( !is_dir( $createFolder ) )
	{
		// If not, create the folder.  Let the user know if something goes wrong.
		// NOTE:  This will fail if you try to recursively create folders!  In
		// other words, this isnt' allowed.
		if ( !mkdir( $createFolder, 0777 ) )
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
//	LYFConvertError()
//
function LYFConvertError( $error, $userMessage )
{
	$message = '';
	switch( $error )
	{
		case LYF_ERROR_CREATE_FOLDER_PERMISSIONS:
			$message = '<strong>Failed</strong> to create the subfolder "' . $userMessage . '".  Make sure your server file permissions are correct or contact support.';
			break;
		case LYF_ERROR_CREATE_FOLDER_EXISTS:
			$message = 'strong>Failed</strong> to create the subfolder ' . $userMessage . ' because it already exists.  Choose a different folder name.';
			break;
		case LYF_SUCCESS_CREATE_FOLDER:
			$message = 'The subfolder ' . $userMessage . ' was successfully created.';
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
			$message ='the file exceeded the maximum upload size allowed';
			break;
		case 2:
			$message ='the file exceeded the form\'s maximum upload size';
			break;
		case 3:
			$message ='the file was only partially uploaded';
			break;
		case 4:
			$message ='no file was uploaded';
			break;
		case 6:
			$message ='no temporary directory exists';
			break;
		case 7:
			$message ='the file failed to write to disk';
			break;
		case 8:
			$message ='the upload wsa prevented by an extension';
			break;
		default:
			break;
	}
	return $message;
}

//
//	LYFUploadFiles()
//
//	This function uploads a list of files into a folder.
//
function LYFUploadFiles( $folder )
{
	// Using the "updated fade" class to make the resulting message prominent.
	echo '<div id="message" class="updated fade">';

	// Count the number of legit files found (for error reporting)
	$count = 0;

	// Check if the folder exists
	$res = opendir( $folder );
	if ( FALSE === $res )
	{
		// If not, create the folder.  Let the user know if something goes wrong.
		if ( !mkdir( $folder ) )
		{
			echo '<p><strong>Failed</strong> to create the folder.  Make sure your server file permissions are correct.</p>';
		}
		// Reset the upload data.  No upload will happen until a folder can be created.
		$_FILES = array();
	}
	else
	{
		closedir( $res );
	}

	// There are up to 10 files that can be uploaded yet.
	foreach ( $_FILES as $file )
	{
		// I don't know why there's an extra blank file in here.  Sorry about this hack.
		if ( '' == $file['tmp_name'] )
			continue;

		// At least one file was found
		$count++;

		if ( UPLOAD_ERR_OK != $file['error'] )
		{
			$errorString = LYFConvertUploadError( $file['error'] );
			echo '<p><strong>Failed</strong> to upload ' .$file['name']. ' because ' . $errorString . '.</p>';
			continue;
		}

		// $final_name holds the full path to the file.
		$final_name = $folder . '/' . $file['name'];

		// Copy the file over...
		$success = copy( $file['tmp_name'], $final_name );

		// ...and report the results of the upload.
		if ( $success )
		{
			echo '<p><strong>Successfully</strong> uploaded ' .$file['name']. '.</p>';
		}
		else
		{
			echo '<p><strong>Failed</strong> to copy over the file ' .$file['name']. '. Check your folder permissions.</p>';
		}
	}

	// Show an error on an empty list of files
	if ( 0 == $count )
	{
		echo '<p>There are no files to upload.  Browse for files to upload first.</p>';
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
	$contents = scandir( $path );
	foreach ( $contents as $item )
	{
		// Ignore all files starting with a .
		if ( substr( $item, 0, 1 ) != '.' )
		{
			// Only add folders - is_dir() is problematic for me, using opendir() and
			// closedir() instead.  is_dir() works fine on OS X, but seems to behave
			// incorrectly on Ubuntu with absolute paths.
			$dir = opendir( $path . '/' . $item );
			if ( FALSE != $dir )
			{
				$folders[] = $item;
				closedir( $dir );
			}
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
		$fileSize = FormatFileSize( $item['size'] );
		$files .= '<tr class="alternate"><td>' . $itemName . '</td><td>' . $fileSize . '</td><td><a href="admin.php?page=Delete&amp;tab=del&amp;id=' . $itemName . '&amp;folder=' . $folder . '" class="delete">Delete</a></td></tr>';
	}

	// Set the output
	$output = $files;

	// Encase the ouput in class and ID
	$retval = '';
	$retVal .= '<div id=\'filelist\'>';
	$retVal .= '<table class="widefat" style="width:710px">
			<thead>
			<tr>
				<th scope="col">Name</th>
				<th scope="col">Size</th>
				<th scope="col">Delete</th>
			</tr>
			</thead>';
	$retVal .= $output . PHP_EOL;
	$retVal .= '</table>' . PHP_EOL . '</div>' . PHP_EOL;

	// return the list
	return $retVal;
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