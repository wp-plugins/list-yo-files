<div class="wrap" style="max-width:950px !important;">
<h2>List Yo' Files - Delete Files</h2>
<div id="poststuff" style="margin-top:10px;">
<div id="mainblock" style="width:710px">
<div class="dbx-content">

<form action="<?php echo $action_url ?>" method="post">

<?php wp_nonce_field('filez-nonce'); ?>

<h4>Usage</h4>

<p>Using this "Delete Files" settings panel will conveniently allow you to avoid using FTP to delete your files.  Follow these steps:</p>
<br>1. Type in the name of the folder you want to browse, again using your WordPress installation as the root folder.  For example:  "wp-content/gallery/my-new-gallery" will list the files in the "my-new-gallery" subfolder.</br>
<br>2. Click the "List Files" button.</br>
<br>3. Selectively click on "Delete" buttons to delete files.</br>
<p />
<p>Be careful when deleting files!</p>

<p><div>Folder to list: <input type="text" name="folder" size="55" /><input type="submit" name="list_files" value="List Files" /></div></p>

</form>

</div>
</div>
</div>
</div>
