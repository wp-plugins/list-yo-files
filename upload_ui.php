<?php
// This form creates the "create subfolder" UI
function LYFGetCreateSubfolderFormCode()
{
	$subfolderCount = ""; // FILL THIS IN

//	echo '<p>You must create at least one subfolder to upload your files to.  You may create ' . $subfolderCount . ' folders.</p>
//	<p>Folder name: <input type="text" name="folder" size="35" /></p><div class="submit"><input type="submit" name="create_folder" value="Create Folder" /></div>';
	return $output;
}

function LYFGetSelectSubfolderFormCode()
{
	$uploadFolder = LYFGetUserUploadFolder( TRUE );
	//$folders = LYFGenerateFolderList( $uploadFolder );
	
	// This is used to determine if the submit button should be disabled
	$folderCount = count( $folders );
	
	// Loop through each sub folder
	foreach( $folders as $folder )
	{
		// Automatically select the folder that the user may have previously
		// uploaded to.
		$selected = ( 0 === strcmp( $folder, $selectedUploadFolder ) ) ? ' selected>' : '>';
	
		// print an option for each folder
		print '<option' . $selected . $folder . '</option>';
	}
	
	echo '</select></p>';
	return $output;
}

// This file creates the upload UI
function LYFGetUploadFormCode( $options )
{
	$action_url = ""; // NOTE: FILL THIS IN
	$noonce = wp_nonce_field('filez-nonce', "_wpnonce", true, false );

	$createFolder = ( FALSE !== stripos( $options, 'create_folder' ) );
	$showFileSizeLimits = ( FALSE !== stripos( $options, 'show_size_warnings' ) );
	
	$output = "<script>
	// Multiple file selector by Stickman -- http://www.the-stickman.com with thanks to: [for Safari fixes] Luis Torrefranca -- http://www.law.pitt.edu and Shawn Parker & John Pennypacker -- http://www.fuzzycoconut.com [for duplicate name bug] 'neal'
	function MultiSelector( list_target, max ){this.list_target = list_target;this.count = 0;this.id = 0;if( max ){this.max = max;} else {this.max = -1;};this.addElement = function( element ){if( element.tagName == 'INPUT' && element.type == 'file' ){element.name = 'file_' + this.id++;element.multi_selector = this;element.onchange = function(){var new_element = document.createElement( 'input' );new_element.type = 'file';this.parentNode.insertBefore( new_element, this );this.multi_selector.addElement( new_element );this.multi_selector.addListRow( this );this.style.position = 'absolute';this.style.left = '-1000px';};if( this.max != -1 && this.count >= this.max ){element.disabled = true;};this.count++;this.current_element = element;} else {alert( 'Error: not a file input element' );};};this.addListRow = function( element ){var new_row = document.createElement( 'div' );var new_row_button = document.createElement( 'input' );new_row_button.type = 'button';new_row_button.value = 'Remove';new_row.element = element;new_row_button.onclick= function(){this.parentNode.element.parentNode.removeChild( this.parentNode.element );this.parentNode.parentNode.removeChild( this.parentNode );this.parentNode.element.multi_selector.count--;this.parentNode.element.multi_selector.current_element.disabled = false;return false;};new_row.innerHTML = element.value;new_row.appendChild( new_row_button );this.list_target.appendChild( new_row );};};
	</script>";
	
	$output .= '<form enctype="multipart/form-data" action="' . $action_url . '" method="post">
	<input type="hidden" name="MAX_FILE_SIZE" value="8000000" />' . PHP_EOL . $noonce . PHP_EOL;
	
	if ( $showFileSizeLimits )
	{
		$maxFolderSize = get_option( LYF_USER_USER_FOLDER_SIZE );
		$uploadFolder = LYFGetUserUploadFolder( TRUE );
		$filesSize = LYFGetFolderSize( $uploadFolder );
		$sizeMessage = LYFFormatFileSize( $filesSize );
		$output = '<p>You are allowed to upload';
		
		if ( 0 == strlen( $maxFolderSize ) )
			$output .= 'as many files as you want.  ';
		else
			$output .= "up to $maxFolderSize MB in files.  ";
		
		$output .= 'You are currently using ' . $sizeMessage . '.</p>';
	}
	
	$output .= '<input id="my_file_element" type="file" name="file_1" />
	<div id="files_list">
		<h3>Selected Files <small>(You can upload up to 10 files at once)</small>:</h3>
	</div>
	<div><input type="submit"'; 
	
	if ( 0 === $folderCount ) 
		$output .= 'disabled="disabled" ';
	
	$output .= 'name="upload_user_files" value="Upload Files" /></div>
	</form>
	<script>
		<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->
		var multi_selector = new MultiSelector( document.getElementById( \'files_list\' ), 10 );
		<!-- Pass in the file element -->
		multi_selector.addElement( document.getElementById( \'my_file_element\' ) );
	</script>';
	
	return $output;
}
?>