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
    			<td>Want to have your own site? Try <a style="text-decoration:none;" href="http://secure.hostgator.com/~affiliat/cgi-bin/affiliates/clickthru.cgi?id=wanderer">HostGator</a>!</td>
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
    	<li>Contact <a href="http://www.wandererllc.com/company/contact/">Wanderer LLC</a> to sponsor a feature or write a plugin just for you.</li>
    	<li>Leave a good rating or comments for <a href="http://wordpress.org/extend/plugins/list-yo-files/">this plugin</a>.</li>
<?php
}
?>
	</ul>
</div>
