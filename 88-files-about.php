<div class="wrap" style="max-width:950px !important;">
<h2>Usage Guide</h2>
<div id="poststuff" style="margin-top:10px;">
<div id="mainblock" style="width:710px">
<div class="dbx-content">
<?php
wp_nonce_field('filez-nonce');
$pluginFolder = get_bloginfo('wpurl') . '/wp-content/plugins/' . dirname( plugin_basename( __FILE__ ) ) . '/';

// Variable to see if subscriber folders is turned ON
$enableUserFolders = get_option( LYF_ENABLE_USER_FOLDERS );

// Show simplified help?
$enableSimpleHelp = get_option( LYF_ENABLE_SIMPLE_HELP );

// Get the user's info
global $current_user;
get_currentuserinfo();

?>

<?php
if ( "on" == $enableUserFolders /*&& !current_user_can( 'add_users' )*/ )
{
?>
<div class="postbox">
	<h3 class="hndle"><span>Displaying File Lists:</span></h3>
	<div class="inside">
		<p>You have the following options for displaying your files:</p>

		<fieldset style="margin-left: 20px;">
		<p>1) To display a list of files, add the special <em>showfiles</em> code
		to the page or post where you want to display the files.  Be sure to include
		the folder to display.  For example:
		<small>[showfiles folder='<?php echo $current_user->user_login;?>/SUBFOLDER_NAME']</small>.
		You can customize your list with the following (none are required):</p>
			<fieldset style="margin-left: 20px;">
			1. <em>sort</em> - include one of the following:  "alphabetic", "reverse_alphabetic", "filesize",
			"reverse_filesize", "date", or "reverse_date".  The <em>default</em> is "alphabetic" and
			is used if "sort" isn't specified.  Example usage:
			<small>[showfiles folder='<?php echo $current_user->user_login;?>/SUBFOLDER_NAME' sort='reverse_filesize']</small><br /><br />

			2. <em>filter</em> - include a list of extensions (no period) separated by commas to only display
			matching files.  For example,
			<small>[showfiles folder='<?php echo $current_user->user_login;?>/SUBFOLDER_NAME' filter = 'mp3,wav,aif']</small> will
			only display audio files in your file list.  Not including this option will list all
			files in the specified folder.<br /><br />

			3. <em>options</em> - A list of comma-separated options to further customize your file list.  An example: <small>[showfiles folder='<?php echo $current_user->user_login;?>/SUBFOLDER_NAME' options='table,filesize,icon']</small> Supported options:
			<fieldset style="margin-left: 20px;">
				<br>a. <em>table</em> - Renders your file list as a table (no border).</br>
				<br>b. <em>filesize</em> - Includes the file size in the list.</br>
				<br>c. <em>date</em> - Includes the file modified date in the list.</br>
				<br>d. <em>new_window</em> - Will open links in a new window.</br>
				<br>e. <em>icon</em> - Works only with the <em>table</em> option.  This option displays a file
				icon to the left of the filename.</br>
				<br>f. <em>wpaudio</em> - <em>Requires the WPAudio plugin</em>.  This option transforms the filename from a download link into an mp3 player.</br>
				<br>g. <em>wpaudiodownloadable</em> - <em>Requires the WPAudio plugin</em>.  This option only works if <em>wpaudio</em> is also specified.  It adds a download link for the song.</br>
			</fieldset>
			<p />
			</fieldset>
		</fieldset>

		<fieldset style="margin-left: 20px;">
		<p>2) To display a list of playable mp3s, add the special <em>showmp3s</em> code
		to the page or post where you want to display the files. For example:
		<small>[showmp3s folder='<?php echo $current_user->user_login;?>/SUBFOLDER_NAME']</small>.
		<em>NOTE:</em> You do not need to supply 'options' when you use 'showmp3s'.
		</p>
		</fieldset>

		<p><em>NOTE:</em> Do not add opening or closing slashes ("/") to the "folder" path.</p>
	</div>
</div>

<div class="postbox">
	<h3 class="hndle"><span>Creating Folders and Uploading Files:</span></h3>
	<div class="inside">
		<p>Before you upload any files, you need to create at least one subfolder to upload your files to.
		Follow these steps:</p>
		<fieldset style="margin-left: 20px;">
			1. Type the name of a folder.  Choose a short name that's easy to recognize.  <em>Note</em>: The folder name is never displayed to users.<br />
			<br>2. Click on "Create Folder" to create the folder.</br>
		</fieldset>
		<p>Once you have at least one folder, you can upload files.  Here are the steps:</p>
		<fieldset style="margin-left: 20px;">
			1. Choose a folder from the select box that you want to upload files to<br />
			<br>2. Use the "Browse" button to select files to upload.  You may upload up to ten at once.</br><br />
			<br>3. Click on "Upload Files".</br>
		</fieldset>
	</div>
</div>

<div class="postbox">
	<h3 class="hndle"><span>Deleting Folders and Files:</span></h3>
	<div class="inside">
		<p>To delete a folder, including all of its contents, follow these steps:</p>
		<fieldset style="margin-left: 20px;">
			1. Select the folder you want to delete.<br />
			<br>3. Click on the "Delete Folder" button.</br>
		</fieldset>
		<p />
		<p>To delete individual files, follow these steps:</p>
		<fieldset style="margin-left: 20px;">
			1. Select the folder you want to browse.<br />
			<br>2. Click the "List Files" button.</br><br />
			<br>3. Selectively click on "Delete" links to delete files.</br>
		</fieldset>
	</div>
</div>

<?php
}
else
{
?>

<div class="postbox">
	<h3 class="hndle"><span>Displaying File Lists:</span></h3>
	<div class="inside">
		<p>To display a list of files in your posts, add the special <em>listyofiles</em> code
		to your page or post where you want to display the file list and include the folder
		to list.  For example:
		<small>[listyofiles folder="wp-content/gallery/my-new-gallery"]</small>.
		<strong>NOTE:</strong>  Do not add opening or closing slashes ("/") to the "folder" path.
		There are several options that you can chose as well to customize your file list:</p>

		<fieldset style="margin-left: 20px;">
		1. <em>sort</em> - include one of the following:  "alphabetic", "reverse_alphabetic", "filesize",
		"reverse_filesize", "date", or "reverse_date".  The <em>default</em> is "alphabetic" and
		is used if "sort" isn't specified.  Example usage:
		<small>[listyofiles folder="wp-content/gallery/my-new-gallery" sort="reverse_filesize"]</small><br /><br />

		2. <em>filter</em> - include a list of extensions (no period) separated by commas to only display
		matching files.  For example,
		<small>[listyofiles folder="wp-content/gallery/my-new-gallery" filter = "mp3,wav,aif"]</small> will
		only display audio files in your file list.  Not including this option will list all
		files in the specified folder.<br /><br />

		3. <em>options</em> - A list of comma-separated options to further customize your file list.  An example: <small>[listyofiles folder="wp-content/gallery/my-new-gallery" options="table,filesize,icon"]</small> Supported options:
		<fieldset style="margin-left: 20px;">
			<br>a. <em>table</em> - Renders your file list as a table (no border).</br>
			<br>b. <em>filesize</em> - Includes the file size in the list.</br>
			<br>c. <em>date</em> - Includes the file modified date in the list.</br>
			<br>d. <em>new_window</em> - Will open links in a new window.</br>
			<br>e. <em>icon</em> - Works only with the <em>table</em> option.  This option displays a file
			icon to the left of the filename.  If you want to support
			additional file types, you can upload a 16x16 png file for the file type that you'd like to
			support.  The name of the file needs to match the extension that you want to display.  All letters should be lowercase.  For
			example, if you want to provide an icon for mp3 files, you would need to upload a file called
			"mp3.png" to the plugin's "icons" folder.</br>
			<br>f. <em>wpaudio</em> - <em>Requires the WPAudio plugin</em>.  This option transforms the filename from a download link into an mp3 player.</br>
			<br>g. <em>wpaudiodownloadable</em> - <em>Requires the WPAudio plugin</em>.  This option only works if <em>wpaudio</em> is also specified.  It adds a download link for the song.</br>
		</fieldset>
		<p />
	</div>
</div>

<div class="postbox">
	<h3 class="hndle"><span>Uploading Files:</span></h3>
	<div class="inside">
		<p>FTP is the usual method for uploading files to a specific folder on your website.  But, sometimes it can be inconvenient.
		"List Yo' Files" provides a simple <strong>Upload Files</strong> UI that allows you to avoid using FTP to upload
		your files without having to leave WordPress.  Follow these steps:</p>
		<fieldset style="margin-left: 20px;">
			1. In the Upload Files UI, indicate which folder in your WordPress installation you want to upload to (you can also type in a new folder and the folder will be created for you).  <em>Recommendation</em>:  Place the folder <strong>underneath the "wp-content" folder</strong>.  Make sure the folder is <a href="http://codex.wordpress.org/Changing_File_Permissions">readable and writable</a>.<br />
			<br>2. Generate a list of files by repeatedly browsing to each file you wish to upload to.</br><br />
			<br>3. Click on "Upload".</br>
		</fieldset>
		<p />
	</div>
</div>

<div class="postbox">
	<h3 class="hndle"><span>Deleting Files:</span></h3>
	<div class="inside">
		<p>FTP is also the usual method for deleting files in a specific folder on your website.
		"List Yo' Files" also provides a <strong>Delete Files</strong> UI that allows you to delete files
		without leaving WordPress.  Follow these steps:</p>
		<fieldset style="margin-left: 20px;">
			1. Type in the name of the folder you want to browse, again using your WordPress installation as the root folder.  For example:  "wp-content/gallery/my-new-gallery" will list the files in the "my-new-gallery" subfolder.<br />
			<br>2. Click the "List Files" button.</br><br />
			<br>3. Selectively click on "Delete" buttons to delete files.</br>
		</fieldset>
		<p />
	</div>
</div>

<?php
}
?>

<div class="postbox">
	<h3 class="hndle"><span>Information:</span></h3>
	<div class="inside">
	 	<p>
    	<img src="<?php echo $pluginFolder;?>help.png"><a style="text-decoration:none;" href="http://www.wandererllc.com/company/plugins/listyofiles/"> Support and Help</a><br /><br />
		<a style="text-decoration:none;" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=TC7MECF2DJHHY&lc=US"><img src="<?php echo $pluginFolder;?>paypal.gif"></a><br /><br />
    	<a href="http://member.wishlistproducts.com/wlp.php?af=1080050"><img src="http://www.wishlistproducts.com/affiliatetools/images/WLM_120X60.gif" border="0"></a><br />
    	Want to restrict your files to only registered users of your site? Try <a style="text-decoration:none;" href="http://member.wishlistproducts.com/wlp.php?af=1080050">Wishlist</a>!<br /><br />
    	Contact <a href="http://www.wandererllc.com/company/contact/">Wanderer LLC</a> to sponsor a feature or write a plugin just for you.<br /><br />
    	Leave a good rating or comments for <a href="http://wordpress.org/extend/plugins/list-yo-files/">List Yo' Files</a>.
		</p>
	</div>
</div>

</div>
</div>
</div>
</div>
