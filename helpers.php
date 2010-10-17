<?php
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
//	GetUserUploadFolder
//
//	This function guarantees a terminating slash.
//
function GetUserUploadFolder()
{
	// Get this user's user name.  User folders are created with that name.
	global $current_user;
	get_currentuserinfo();

	return trailingslashit( ABSPATH . LYF_USER_FOLDER . $current_user->user_login );
}

//
//	CreateUserFolder()
//
//	This function creates the requested user folder.  A user folder is a
//	special folder created in a special place.  Only end users typically
//	use this function.
//
function CreateUserFolder( $folderName )
{
	// Assemble the full path and ensure there is a trailing slash.
	$createFolder = GetUserUploadFolder();
	$createFolder .= $folderName;

	// Using the "updated fade" class to make the resulting message prominent.
	echo '<div id="message" class="updated fade">';

	// Check if the folder exists
	if ( !is_dir( $createFolder ) )
	{
		// If not, create the folder.  Let the user know if something goes wrong.
		// NOTE:  This will fail if you try to recursively create folders!  In
		// other words, this isnt' allowed.
		if ( !mkdir( $createFolder ) )
		{
			echo '<p><strong>Failed</strong> to create the subfolder "' . $folderName . '".  Make sure your server file permissions are correct or contact support.</p>';
		}
		else
		{
		echo '<p>The subfolder ' . $folderName . ' was successfully created..</p>';
		}
	}
	else
	{
		echo '<p>strong>Failed</strong> to create the subfolder ' . $folderName . ' because it already exists.  Choose a different folder name.</p>';
	}
	echo '</div>';
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

//
//	Generate a folder list
//
function GenerateFolderList( $path )
{
	// Store the folders in an array
	$folders = array();

	// Scan the folder
	$contents = scandir( $path );
	foreach ( $contents as $item )
	{
		if ( ( is_dir( $item ) ) && ( substr( $item, 0, 1 ) != '.' ) )
		{
			$folders[] = $item;
		}
	}
	return $folders;
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