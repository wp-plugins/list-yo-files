<div class="wrap" style="max-width:950px !important;">
<h2>Uploading Files</h2>
<div id="poststuff" style="margin-top:10px;">
<div id="mainblock" style="width:710px">
<div class="dbx-content">

<?php
$pluginFolder = get_bloginfo('wpurl') . '/wp-content/plugins/' . dirname( plugin_basename( __FILE__ ) ) . '/';

// Variable to see if subscriber folders is turned ON
$enableUserFolders = get_option( LYF_ENABLE_USER_FOLDERS );

// How many folders can the user upload?
$subfolderCount = get_option( LYF_USER_SUBFOLDER_LIMIT );
if ( empty( $subfolderCount ) )
{
	$subfolderCount = "unlimited";
}

// What's the size quota?
$maxFolderSize = get_option( LYF_USER_USER_FOLDER_SIZE );

include_once "information-box.php"
?>

<script>
// Multiple file selector by Stickman -- http://www.the-stickman.com with thanks to: [for Safari fixes] Luis Torrefranca -- http://www.law.pitt.edu and Shawn Parker & John Pennypacker -- http://www.fuzzycoconut.com [for duplicate name bug] 'neal'
function MultiSelector( list_target, max ){this.list_target = list_target;this.count = 0;this.id = 0;if( max ){this.max = max;} else {this.max = -1;};this.addElement = function( element ){if( element.tagName == 'INPUT' && element.type == 'file' ){element.name = 'file_' + this.id++;element.multi_selector = this;element.onchange = function(){var new_element = document.createElement( 'input' );new_element.type = 'file';this.parentNode.insertBefore( new_element, this );this.multi_selector.addElement( new_element );this.multi_selector.addListRow( this );this.style.position = 'absolute';this.style.left = '-1000px';};if( this.max != -1 && this.count >= this.max ){element.disabled = true;};this.count++;this.current_element = element;} else {alert( 'Error: not a file input element' );};};this.addListRow = function( element ){var new_row = document.createElement( 'div' );var new_row_button = document.createElement( 'input' );new_row_button.type = 'button';new_row_button.value = 'Remove';new_row.element = element;new_row_button.onclick= function(){this.parentNode.element.parentNode.removeChild( this.parentNode.element );this.parentNode.parentNode.removeChild( this.parentNode );this.parentNode.element.multi_selector.count--;this.parentNode.element.multi_selector.current_element.disabled = false;return false;};new_row.innerHTML = element.value;new_row.appendChild( new_row_button );this.list_target.appendChild( new_row );};};
</script>

<form enctype="multipart/form-data" action="<?php echo $action_url ?>" method="post">

<input type="hidden" name="MAX_FILE_SIZE" value="8000000" />

<?php wp_nonce_field('filez-nonce');?>

<?php

if ( "on" == $enableUserFolders && !current_user_can( 'delete_users' ) )
{
	//
	//	START:  UI for non-admins
	//

	// First, make sure their user folder exists.  If it doesn't then create it
	$userFolder = LYFGetUserUploadFolder( TRUE );

	if ( !is_dir( $userFolder ) )
	{
		$result = LYFCreateUserFolder( $userFolder );

		if ( $result < 0 )
		{
			global $current_user;
			get_currentuserinfo();

			$message = '<div id="message" class="updated fade"><p>Problem creating your user folder! ';
			$message .= LYFConvertError( $result, $current_user->user_login );
			$message .= '</p></div>';
			echo $message;
		}
	}
?>

<h4>Create a subfolder:</h4>
<p>You must create at least one subfolder to upload your files to.  You may create <?php echo $subfolderCount;?> folders.</p>
<p>Folder name: <input type="text" name="folder" size="35" /></p><div class="submit"><input type="submit" name="create_folder" value="Create Folder" /></div>

<h4>Upload Files:</h4>
<p>You are allowed to upload 
<?php 
if ( 0 == strlen( $maxFolderSize ) )
	echo "as many files as you want.  "; 
else
	echo "up to $maxFolderSize MB in files.  ";
?>
You are currently using
<?php
$uploadFolder = LYFGetUserUploadFolder( TRUE );
$filesSize = LYFGetFolderSize( $uploadFolder );
$sizeMessage = LYFFormatFileSize( $filesSize );
echo $sizeMessage;
?> 
.</p>

<p>Select a folder to upload files to:  <select name="upload_folder">

<?php
	$folders = LYFGenerateFolderList( $userFolder );

	// Loop through each sub folder
	foreach( $folders as $folder )
	{
		// print an option for each folder
		print '<option>' . $folder . '</option>';
	}
?>

</select>
</p>

<input id="my_file_element" type="file" name="file_1" />
<div id="files_list">
	<h3>Selected Files <small>(You can upload up to 10 files at once)</small>:</h3>
</div>

<div class="submit"><input type="submit" name="upload_user_files" value="Upload Files" /></div>

</form>

<script>
	<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->
	var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 10 );
	<!-- Pass in the file element -->
	multi_selector.addElement( document.getElementById( 'my_file_element' ) );
</script>

<?php
}
else
{

//
//	START:  UI for admins
//

?>

	<p>Type in the name of the folder that you want to upload to.  The folder is a relative path located in your WordPress installation folder.  For example:  "wp-content/gallery/my-new-gallery".  <strong>NOTE:</strong>  Do not add opening or closing slashes ("/") to the path.</p>
	<p>If the folder doesn't exist, then List Yo' Files will attempt to create it for you.</p>
	<p>Folder name: <input type="text" name="upload_folder" size="55" /></p>

	<input id="my_file_element" type="file" name="file_1" />
	<div id="files_list">
		<h3>Selected Files <small>(You can upload up to 10 files at once)</small>:</h3>
	</div>

<div class="submit"><input type="submit" name="upload_files" value="Upload Files" /></div>

</form>

<script>
	<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->
	var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 10 );
	<!-- Pass in the file element -->
	multi_selector.addElement( document.getElementById( 'my_file_element' ) );
</script>

<p><strong>Note:</strong>  If you want to upload additional icon files for your file lists, you can do that here.  Just use input folder name: 'wp-content/plugins/list-yo-files/icons' and then upload 16x16 .png files.  See the main admin page for more details.</p>

<?php
}
?>

</div>
</div>
</div>
</div>
