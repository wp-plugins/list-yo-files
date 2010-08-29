<div class="wrap" style="max-width:950px !important;">
<h2>How to Use</h2>
<div id="poststuff" style="margin-top:10px;">
<div id="mainblock" style="width:710px">
<div class="dbx-content">
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
<h4>Displaying File Lists:</h4>
<p>To display a list of files in your posts, add the special <em>listyofiles</em> code
to your page or post where you want to display the file list and include the folder
to list.  For example:
<small>[listyofiles folder="wp-content/gallery/my-new-gallery"]</small>.
<strong>NOTE:</strong>  Do not add opening or closing slashes ("/") to the "folder" path.
There are several options that you can chose as well to customize your file list:</p>
1. <em>sort</em> - include one of the following:  "alphabetic", "reverse_alphabetic", "filesize",
"reverse_filesize", "date", or "reverse_date".  The <em>default</em> is "alphabetic" and
is used if "sort" isn't specified.  Example usage:
<small>[listyofiles folder="wp-content/gallery/my-new-gallery" sort="reverse_filesize"]</small>
<p>2. <em>filter</em> - include a list of extensions (no period) separated by commas to only display
matching files.  For example,
<small>[listyofiles folder="wp-content/gallery/my-new-gallery" filter = "mp3,wav,aif"]</small> will
only display audio files in your file list.  Not including this option will list all
files in the specified folder.</p>
3. <em>options</em> - A list of comma-separated options to further customize your file list.  An example: <small>[listyofiles folder="wp-content/gallery/my-new-gallery" options="table,filesize,icon"]</small> Supported options:
<br>a. <em>table</em> - Renders your file list as a table (no border).</br>
<br>b. <em>filesize</em> - Includes the file size in the list.</br>
<br>c. <em>date</em> - Includes the file modified date in the list.</br>
<br>d. <em>new_window</em> - Will open links in a new window.</br>
<br>e. <em>icon</em> - Works only with the <em>table</em> option.  This option displays a file
icon to the left of the filename.  If you want to support
additional file types, you can upload a 16x16 png file for the file type that you'd like to
support.  The name of the file needs to match the extension that you want to display.  For
example, if you want to provide an icon for mp3 files, you would need to upload a file called
"mp3.png" to the plugin's "icons" folder.</br>
<p />

<h4>Uploading Files:</h4>
<p>FTP is the usual method for uploading files to a specific folder on your website.  But, sometimes it can be inconvenient.
"List Yo' Files" provides a simple <strong>Upload Files</strong> UI that allows you to avoid using FTP to upload
your files without having to leave WordPress.  Follow these steps:</p>
1. In the Upload Files UI, indicate which folder in your WordPress installation you want to upload to (you can also type in a new folder and the folder will be created for you).  <em>Recommendation</em>:  Place the folder <strong>underneath the "wp-content" folder</strong>.  Make sure the folder is <a href="http://codex.wordpress.org/Changing_File_Permissions">readable and writable</a>.
<br>2. Generate a list of files by repeatedly browsing to each file you wish to upload to.</br>
<br>3. Click on "Upload".</br>
<p />

<h4>Deleting Files:</h4>
<p>FTP is also the usual method for deleting files in a specific folder on your website.
"List Yo' Files" also provides a <strong>Delete Files</strong> UI that allows you to delete files
without leaving WordPress.  Follow these steps:</p>
1. Type in the name of the folder you want to browse, again using your WordPress installation as the root folder.  For example:  "wp-content/gallery/my-new-gallery" will list the files in the "my-new-gallery" subfolder.
<br>2. Click the "List Files" button.</br>
<br>3. Selectively click on "Delete" buttons to delete files.</br>
<p />

</div>
</div>
</div>
</div>
