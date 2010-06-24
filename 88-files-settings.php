<div class="wrap" style="max-width:950px !important;">
<h2>List Yo' Files</h2>
<div id="poststuff" style="margin-top:10px;">
<div id="mainblock" style="width:710px">
<div class="dbx-content">

<form enctype="multipart/form-data" action="<?php echo $action_url ?>" method="post">

<input type="hidden" name="MAX_FILE_SIZE" value="3000000" />

<?php wp_nonce_field('filez-nonce');

$pluginFolder = get_bloginfo('wpurl') . '/wp-content/plugins/' . dirname( plugin_basename( __FILE__ ) ) . '/';

?>

<div style="float:right;width:220px;margin-left:10px;border: 1px solid #ddd;background: #fdffee; padding: 10px 0 10px 10px;">
 	<h2 style="margin: 0 0 5px 0 !important;">Information</h2>
 	<ul id="dbx-content" style="text-decoration:none;">
    	<li><img src="<?php echo $pluginFolder;?>help.png"><a style="text-decoration:none;" href="http://www.wandererllc.com/company/plugins/listyofiles/"> Support and Help</a></li>
		<li><a style="text-decoration:none;" href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=TC7MECF2DJHHY&lc=US"><img src="<?php echo $pluginFolder;?>paypal.gif"></a></li>
	</ul>
</div>
<h4>How to Use "List Yo' Files":</h4>
<p>Using this "List Yo' Files" settings panel will conveniently allow you to avoid using FTP to upload your files.  Follow these steps:</p>
<br>1. Indicate which folder in your WordPress installation you want to upload to.  Recommendation:  Place the folder underneath the "wp-content" folder.  Make sure the folder is <a href="http://codex.wordpress.org">readable and writable</a>.</br>
<br>2. Generate a list of files by repeatedly browsing to each file you wish to upload to.</br>
<br>3. Click on "Upload".</br>
<br>4. To display files, add the <em>listyofiles</em> code to your page or post where you want to display the file list.  For example: <em>[listyofiles folder="wp-content/gallery/my-new-gallery"]</em>.  <strong>NOTE:</strong>  Do not add opening or closing slashes ("/") to the path.</br>
<p />
<h4>Upload Files:</h4>
	<p>Type in the name of the folder that you want to upload to.  The folder is a relative path located in your WordPress installation folder.  For example:  "wp-content/gallery/my-new-gallery".  <strong>NOTE:</strong>  Do not add opening or closing slashes ("/") to the path.</p>
	<p>Folder name: <input type="text" name="folder" size="55" /></p>

	<input id="my_file_element" type="file" name="file_1" />

			<div id="files_list">
				<h3>Selected Files: <small>You can upload up to 10 files at once</small></h3>
			</div>

<div class="submit"><input type="submit" name="upload_files" value="Upload Files" /></div>

</form>

<script type="text/javascript">
	<!-- Create an instance of the multiSelector class, pass it the output target and the max number of files -->
	var multi_selector = new MultiSelector( document.getElementById( 'files_list' ), 10 );
	<!-- Pass in the file element -->
	multi_selector.addElement( document.getElementById( 'my_file_element' ) );
</script>

</div>
</div>
</div>
</div>
