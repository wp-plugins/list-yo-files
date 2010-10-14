<div class="wrap" style="max-width:950px !important;">
<h2>Administration</h2>
<div id="poststuff" style="margin-top:10px;">
<div id="mainblock" style="width:710px">
<div class="dbx-content">

<form enctype="multipart/form-data" action="<?php echo $action_url ?>" method="POST">

<?php
wp_nonce_field('filez-nonce');
$pluginFolder = get_bloginfo('wpurl') . '/wp-content/plugins/' . dirname( plugin_basename( __FILE__ ) ) . '/';
?>

<div style="float:right;width:220px;margin-left:10px;border: 1px solid #ddd;background: #fdffee; padding: 10px 0 10px 10px;">
 	<h2 style="margin: 0 0 5px 0 !important;">Information</h2>
 	<ul id="dbx-content" style="text-decoration:none;">
    	<li><img src="<?php echo $pluginFolder;?>help.png"><a style="text-decoration:none;" href="http://www.wandererllc.com/company/plugins/listyofiles/"> Support and Help</a></li>
		<li><a style="text-decoration:none;" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=TC7MECF2DJHHY&lc=US"><img src="<?php echo $pluginFolder;?>paypal.gif"></a></li>
    	<li><table border="0">
    		<tr>
    			<td><a href="http://member.wishlistproducts.com/wlp.php?af=1080050"><img src="http://www.wishlistproducts.com/affiliatetools/images/WLM_120X60.gif" border="0"></a></td>
    			<td>Restrict files to registered users? Try <a style="text-decoration:none;" href="http://member.wishlistproducts.com/wlp.php?af=1080050">Wishlist</a></td>
    		</tr>
    	</table></li>
    	<li>Contact <a href="http://www.wandererllc.com/company/contact/">Wanderer LLC</a> to sponsor a feature or write a plugin just for you.</li>
    	<li>Leave a good rating or comments for <a href="http://wordpress.org/extend/plugins/list-yo-files/">List Yo' Files</a>.</li>
	</ul>
</div>
<h4>List Yo' Files Administration:</h4>

<p>Rename the master menu to: <input type="text" name="menu_name" value="<?php echo $menuText;?>"size="25" /></p>

<?php
print '<p><input type=CHECKBOX name="on_restrict_types" ';
if ( "0" === $restrictTypes || empty( $restrictTypes ) ){} else
	print 'checked';
print '> Restrict uploads to the following file types (no periods, separated by commas): ';
?>

<input type="text" name="file_types" value="<?php echo $allowedFileTypes;?>"size="25" /> <small>For example: mp3,wav,aif,mov</small></p>

<?php
print '<p><input type=CHECKBOX name="on_enable_folders" ';
if ( "0" === $enableUserFolders || empty( $enableUserFolders ) ){} else
	print 'checked';
print '> Enable Subscriber folders</p>';
?>

<fieldset style="margin-left: 20px;">

<p>Limit the number of user folders to: <input type="text" name="num_folders" value="<?php echo $subfolderCount;?>"size="10" />
<small>Leave empty for unlimited</small>
</p>

<p>Upload space per user (in MB): <input type="text" name="folder_size" value="<?php echo $folderSize;?>"size="10" />
<small>Leave empty for unlimited</small>
</p>
</fieldset>

<div class="primary_button"><input type="submit" name="save_admin_settings" value="Save Settings" /></div>
</form>

</div>
</div>
</div>
</div>
