<?php
/*
Plugin Name: Contus Video Comments
Plugin URI: http://webflvrecorder.net/video-comments-wordpress
Description: Video comments integrated with the standard comment system of wordpress.
Version: 1.0
Author: Contus Support.
Author URI: http://www.contussupport.com/
*/

function hdvideo_header() {
    $site_url = get_option('siteurl');
   
    $optionvalue = get_option('webflvOptions');
    $serverpath = $optionvalue[0];
 $cvc = dirname( plugin_basename(__FILE__));
    ?><script type="text/javascript" >
    var serverpath ="<?php echo $serverpath ?>";
    var site_url ="<?php echo $site_url ?>";
    var pwidth ="<?php echo $optionvalue[1] ?>";
    var pheight ="<?php echo $optionvalue[2] ?>";
    var rwidth ="<?php echo $optionvalue[3] ?>";
    var rheight ="<?php echo $optionvalue[4] ?>";
    var maxduration ="<?php echo $optionvalue[5] ?>";
    var license ="<?php echo $optionvalue[6] ?>";
       var cvc ="<?php echo $cvc ?>";
</script><?
    echo '
<script type="text/javascript" src="'.$site_url.'/wp-content/plugins/'.$cvc.'/videoblog.js"></script>
<link rel="stylesheet" href="'.$site_url.'/wp-content/plugins/'.$cvc.'/css/hdvideo.css" type="text/css" media="all" />';
}

/* 
* Convert legacy tags into modern hdvideo links
*/

function hdvideo_comment_text($comment = '') {

    if ($comment != '') {
        $pattern = '/\[hdvideo_video\](.*)\[\/hdvideo_video\]/';
        preg_match_all($pattern, $comment, $matches);

        foreach ($matches[1] as $hdvideo_id) {
            $pattern = '/\[hdvideo_video\]' . $hdvideo_id . '\[\/hdvideo_video\]/';
            $replacement = sprintf('<a href="http://hdvideo.com/v/%s#video">Play Video Comment</a>', $hdvideo_id);
            $comment = preg_replace($pattern, $replacement, $comment);
        }

        $pattern = '/\[hdvideo_audio\](.*)\[\/hdvideo_audio\]/';
        preg_match_all($pattern, $comment, $matches);

        foreach ($matches[1] as $hdvideo_id) {
            $pattern = '/\[hdvideo_audio\]' . $hdvideo_id . '\[\/hdvideo_audio\]/';
            $replacement = sprintf('<a href="http://hdvideo.com/v/%s#audio">Play Audio Comment</a>', $hdvideo_id);
            $comment = preg_replace($pattern, $replacement, $comment);
        }
    }

    return $comment;
}



function hdvideo_footer() {
    echo '<center><a href="http://webflvrecorder.net/">Video Comments are powered by Contus Support</a></center>';
}

function web_deinstall() {
    delete_option('webflvOptions');
}
function webflvAddPage() {
    add_options_page('Contus Video Comments Option', 'Contus Video Comments', '8', 'wp-webflv.php', 'webflvOptions');
}
function webflvOptions() {
    $option = get_option('webflvOptions');
    $options[0] = $option[0];
    $options[1] = $option[1];
    $options[2] = $option[2];
    $options[3] = $option[3];
    $options[4] = $option[4];
    $options[5] = $option[5];
    $options[6] = $option[6];
    if ($_POST) {
        $options[0]= $_POST['path'];
        $options[1]= $_POST['pwidth'];
        $options[2]= $_POST['pheight'];
        $options[3]= $_POST['rwidth'];
        $options[4]= $_POST['rheight'];
        $options[5]= $_POST['maxduration'];
        $options[6]= $_POST['license'];
        update_option('webflvOptions', $options);

    }
    if($options =='') $options[0]="Enter the Path";
    echo '<div class="wrap">';
    echo '<h2>Contus Video Comments Options</h2>';
    echo '<form method="post" action="options-general.php?page=wp-webflv.php"><table cellspacing="15">';
    echo '<tr><td>Server path to store the Video comments:</td><td ><input type="text" name="path" value="'.$options[0].'" size=45  /></td></tr>';
    echo '<tr><td>Player Scale:</td><td><table cellspacing="5"><tr><td><input type="text" name="pwidth" value="'.$options[1].'" size=5  /></td><td>X</td><td><input type="text" name="pheight" value="'.$options[2].'" size=5  /></td></tr></table></td></tr>';
    echo '<tr><td>Recorder Scale:</td><td><table cellspacing="5"><tr><td><input type="text" name="rwidth" value="'.$options[3].'" size=5  /></td><td>X</td><td><input type="text" name="rheight" value="'.$options[4].'" size=5  /></td></tr></table></td></tr>';
    echo '<tr><td>Max duration:</td><td ><input type="text" name="maxduration" value="'.$options[5].'" size=10  /></td></tr>';
    echo '<tr><td>License Key:</td><td ><input type="text" name="license" value="'.$options[6].'" size=45  /></td></tr></table>';
    echo '<p class="submit"><input class="button-primary" type="submit" method="post" value="Update Options"></p>';
    echo '</form>';

}

function webflv_load() {
    $options[0]="Enter the path here";
    $options[1]="320";
    $options[2]="260";
    $options[3]="320";
    $options[4]="290";
    $options[5]="200";
    $options[6]="Enter the License Key here";

    return $options;

}

function webflv_activate() {
    update_option('webflvOptions', webflv_load());
}

register_activation_hook(__FILE__,'webflv_activate');
register_uninstall_hook(__FILE__, 'webflv_deinstall');


add_filter('admin_head', 'hdvideo_header');
add_filter('wp_head', 'hdvideo_header');

add_filter('wp_footer', 'hdvideo_footer');

add_filter('comment_text', 'hdvideo_comment_text'); // Convert legacy tags into modern hdvideo links
add_action('admin_menu', 'webflvAddPage');
?>
