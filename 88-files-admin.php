<div class="wrap" style="max-width:950px !important;">
<h2>Administration</h2>
<div id="poststuff" style="margin-top:10px;">
<div id="mainblock" style="width:710px">
<div class="dbx-content">

<form enctype="multipart/form-data" action="<?php echo $action_url ?>" method="POST">

<?php
wp_nonce_field('filez-nonce');
$pluginFolder = get_bloginfo('wpurl') . '/wp-content/plugins/' . dirname( plugin_basename( __FILE__ ) ) . '/';

include_once "information-box.php"
?>

<h4>List Yo' Files Administration:</h4>

<p>Rename the master menu to: <input type="text" name="menu_name" value="<?php echo $menuText;?>"size="25" /></p>

<?php
print '<p><input type=CHECKBOX name="on_restrict_types" ';
if ( "on" === $restrictTypes )
	print 'checked';
print '> Restrict uploads to the following file types (no periods, separated by commas): ';
?>

<input type="text" name="file_types" value="<?php echo $allowedFileTypes;?>"size="25" /> <small>For example: 'mp3,wav,aif,mov'. Leave blank for any file type.</small></p>

<?php
print '<p><input type=CHECKBOX name="on_enable_folders" ';
if ( "on" === $enableUserFolders )
	print 'checked';
print '> Enable user folders</p>';
?>

<fieldset style="margin-left: 20px;">

<?php
print '<p><input type=CHECKBOX name="on_enable_simple_help" ';
if ( "on" === $enableSimpleHelp )
	print 'checked';
print '> Show simple help for users <small>(shows non-admins only the most basic options)</small></p>';
?>

<p>Choose the minimum role that can manage files:  <select name="minimum_role">
<?php
	$roles = array( 'Subscriber', 'Contributor', 'Author', 'Editor', 'Administrator' );
	// Loop through each sub folder
	foreach( $roles as $role )
	{
		$selText = ( $minimumRole == $role ) ? '<option selected>' : '<option>';
		// print an option for each folder
		print $selText . $role . '</option>';
	}
?>
</select>
<br><small>The least powerful role is 'Subscriber', the most powerful is 'Administrator'.</small></br>
</p>

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
