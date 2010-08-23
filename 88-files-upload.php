<div class="wrap" style="max-width:950px !important;">
<h2>Upload Files</h2>
<div id="poststuff" style="margin-top:10px;">
<div id="mainblock" style="width:710px">
<div class="dbx-content">

<form enctype="multipart/form-data" action="<?php echo $action_url ?>" method="post">

<input type="hidden" name="MAX_FILE_SIZE" value="3000000" />

<?php wp_nonce_field('filez-nonce');?>

<h4>Upload Files:</h4>
	<p>Type in the name of the folder that you want to upload to.  The folder is a relative path located in your WordPress installation folder.  For example:  "wp-content/gallery/my-new-gallery".  <strong>NOTE:</strong>  Do not add opening or closing slashes ("/") to the path.</p>
	<p>If the folder doesn't exist, then List Yo' Files will attempt to create it for you.</p>
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
