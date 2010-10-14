<div class="wrap" style="max-width:950px !important;">
<div id="poststuff" style="margin-top:10px;">
<div id="mainblock" style="width:710px">
<div class="dbx-content">

<form action="<?php echo $action_url ?>" method="post">

<?php
wp_nonce_field('filez-nonce');
$pluginFolder = get_bloginfo('wpurl') . '/wp-content/plugins/' . dirname( plugin_basename( __FILE__ ) ) . '/';

// Variable to see if subscriber folders is turned ON
$enableUserFolders = get_option( LYF_ENABLE_USER_FOLDERS );

// Test folders
$folders = array( 'Relic', 'The Four Heads of Frozen Taco', 'Primordial Nostalgia', 'What Win Won Der Wizard' );
?>
<div style="float:right;width:220px;margin-left:10px;border: 1px solid #ddd;background: #fdffee; padding: 10px 0 10px 10px;">
 	<h2 style="margin: 0 0 5px 0 !important;">Information</h2>
 	<ul id="dbx-content" style="text-decoration:none;">
    	<li><img src="<?php echo $pluginFolder;?>help.png"><a style="text-decoration:none;" href="http://www.wandererllc.com/company/plugins/listyofiles/"> Support and Help</a></li>
		<li><a style="text-decoration:none;" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=TC7MECF2DJHHY&lc=US"><img src="<?php echo $pluginFolder;?>paypal.gif"></a></li>
<?php
if ( "on" == $enableUserFolders /*&& !current_user_can( 'add_users' ) */)
{
?>
    	<li><table border="0">
    		<tr>
    			<td><a href="http://secure.hostgator.com/~affiliat/cgi-bin/affiliates/clickthru.cgi?id=wanderer"><img src="https://tracking.hostgator.com/img/Shared/120x90.gif" border="0"></a></td>
    			<td>Want to have your own site? Try <a style="text-decoration:none;" href="http://secure.hostgator.com/~affiliat/cgi-bin/affiliates/clickthru.cgi?id=wanderer">HostGator</a></td>
    		</tr>
    	</table></li>
<?php
}
else
{
?>
    	<li><table border="0">
    		<tr>
    			<td><a href="http://member.wishlistproducts.com/wlp.php?af=1080050"><img src="http://www.wishlistproducts.com/affiliatetools/images/WLM_120X60.gif" border="0"></a></td>
    			<td>Restrict files to registered users? Try <a style="text-decoration:none;" href="http://member.wishlistproducts.com/wlp.php?af=1080050">Wishlist</a></td>
    		</tr>
    	</table></li>
<?php
}
?>
    	<li>Contact <a href="http://www.wandererllc.com/company/contact/">Wanderer LLC</a> to sponsor a feature or write a plugin just for you.</li>
    	<li>Leave a good rating or comments for <a href="http://wordpress.org/extend/plugins/list-yo-files/">this plugin</a>.</li>
	</ul>
</div>

<?php
if ( "on" == $enableUserFolders /*&& !current_user_can( 'add_users' ) */)
{
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
<input type="submit" name="list_files" value="List Files" /></div>
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

</div>
</div>
</div>
</div>
