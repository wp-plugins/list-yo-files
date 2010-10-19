<div class="wrap" style="max-width:950px !important;">
<h2>Deleting Files</h2>
<div id="poststuff" style="margin-top:10px;">
<div id="mainblock" style="width:710px">
<div class="dbx-content">

<form action="<?php echo $action_url ?>" method="post">

<?php
wp_nonce_field('filez-nonce');
$pluginFolder = get_bloginfo('wpurl') . '/wp-content/plugins/' . dirname( plugin_basename( __FILE__ ) ) . '/';

// Variable to see if subscriber folders is turned ON
$enableUserFolders = get_option( LYF_ENABLE_USER_FOLDERS );

include_once "information-box.php"
?>

<?php
if ( "on" == $enableUserFolders /*&& !current_user_can( 'add_users' ) */)
{
	// Generate the list of user folders
	$userFolder = LYFGetUserUploadFolder( TRUE );
	$folders = LYFGenerateFolderList( $userFolder );
?>

<h4>Delete Folders</h4>

<p>Select a folder you want to delete.  Then, click on the "Delete Folder" button.  <em>Be careful!</em> This will delete all files in the folder.</p>

<p>Select a folder to delete:  <select name="folder_to_delete">
<?php
	// Loop through each sub folder
	foreach( $folders as $folder )
	{
		// print an option for each folder
		print '<option>' . $folder . '</option>';
	}
?>
</select>
<input type="submit" name="delete_folder" value="Delete Folder" /></div>
</p>

<h4>Delete Files</h4>

<p>Select a folder whose files you want to display.  Then, click on the "List Files" button to show each file.  Each file will then be listed and you can selectively delete your files.</p>

<p>Select a folder to display its files:  <select name="folder">
<?php
	// Loop through each sub folder
	foreach( $folders as $folder )
	{
		// print an option for each folder
		print '<option>' . $folder . '</option>';
	}
?>
</select>
<input type="submit" name="list_user_files" value="List Files" /></div>
</p>

<?php
}
else
{
?>

<h4>Usage</h4>

<p>Using this "Delete Files" settings panel will conveniently allow you to avoid using FTP to delete your files.  Follow these steps:</p>
<br>1. Type in the name of the folder you want to browse, again using your WordPress installation as the root folder.  For example:  "wp-content/gallery/my-new-gallery" will list the files in the "my-new-gallery" subfolder.</br>
<br>2. Click the "List Files" button.</br>
<br>3. Selectively click on "Delete" buttons to delete files.</br>
<p />
<p>As always, <em>be careful</em> when deleting files!</p>

<p><div>Folder to list: <input type="text" name="folder" size="55" /><input type="submit" name="list_files" value="List Files" /></div></p>

<?php
}
?>
</form>

<?php
// NOTE:	Four "</div>" statements used to be here.  They have been moved back into
// 			the calling function in order to improve formatting.
?>