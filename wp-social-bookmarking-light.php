<?php
/*
Plugin Name: WP Social Bookmarking Light
Plugin URI: http://www.ninxit.com/blog/2010/06/13/wp-social-bookmarking-light/
Description: This plugin inserts social share links at the top or bottom of each post.
Author: utahta
Author URI: http://www.ninxit.com/blog/
Version: 1.5.0
*/
/*
Copyright 2010 utahta (email : labs.ninxit@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

define( "WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL", WP_PLUGIN_URL."/wp-social-bookmarking-light/images" );
define( "WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN", "wp-social-bookmarking-light" );

load_plugin_textdomain( WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN,
                        "wp-content/plugins/wp-social-bookmarking-light/po" );

class WpSocialBookmarkingLight
{
    var $url;
    var $title;
    var $encode_url;
    var $encode_title;
    var $encode_blogname;
    
    function WpSocialBookmarkingLight( $url, $title, $blogname )
    {
        $title = $this->to_utf8( $title );
        $blogname = $this->to_utf8( $blogname );
        $this->url = $url;
        $this->title = $title;
        $this->encode_url = rawurlencode( $url );
        $this->encode_title = rawurlencode( $title );
        $this->encode_blogname = rawurlencode( $blogname );
    }
    
    function to_utf8( $str )
    {
        $charset = get_settings( 'blog_charset' );
        if( strcasecmp( $charset, 'UTF-8' ) != 0 && function_exists('mb_convert_encoding') ){
            $str = mb_convert_encoding( $str, 'UTF-8', $charset );
        }
        return $str;
    }
    
    function link_raw( $url ){
        return "<li>".$url."</li>";
    }
    function link( $url, $alt, $icon, $width, $height ){
        $width = $width ? "width='$width'" : "";
        $height = $height ? "height='$height'" : "";
    	return $this->link_raw( "<a href='{$url}' title='{$alt}' rel=nofollow class='wp_social_bookmarking_light_a' target=_blank>"
                               ."<img src='{$icon}' alt='{$alt}' title='{$alt}' $width $height class='wp_social_bookmarking_light_img' />"
                               ."</a>" );
    }
    
    /**
     * @brief Hatena Bookmark
     */
    function hatena()
    {
        $url = "http://b.hatena.ne.jp/add?mode=confirm&url={$this->encode_url}&title={$this->encode_title}";
        $alt = __( "Bookmark this on Hatena Bookmark", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/hatena.gif";
        return $this->link( $url, $alt, $icon, 16, 12 );
    }
    function hatena_users()
    {
        $url = "http://b.hatena.ne.jp/entry/{$this->url}";
        $alt = sprintf( __("Hatena Bookmark - %s", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN), $this->title );
        $icon = "http://b.hatena.ne.jp/entry/image/{$this->url}";
        return $this->link( $url, $alt, $icon, null, null );
    }
    
    /**
     * @brief twib
     */
    function twib()
    {
        $url = "http://twib.jp/share?url={$this->encode_url}";
        $alt = __( "Post to Twitter", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/twib.gif";
        return $this->link( $url, $alt, $icon, 18, 18 );
    }
    function twib_users()
    {
        $url = "http://twib.jp/url/{$this->url}";
        $alt = sprintf( __("Tweets - %s", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN), $this->title );
        $icon = "http://image.twib.jp/counter/{$this->url}";
        return $this->link( $url, $alt, $icon, null, null );
    }
    
    /**
     * @brief tweetmeme
     */
    function tweetmeme()
    {
        return $this->link_raw( "<script type='text/javascript'>"
                               ."tweetmeme_style = 'compact';"
                               ."tweetmeme_url='{$this->url}';"
                               ."</script>"
                               ."<script type='text/javascript' src='http://tweetmeme.com/i/scripts/button.js'></script>" );
    }
    
    /**
     * @brief twitter
     */
    function twitter()
    {
        return $this->link_raw( '<a href="http://twitter.com/share" class="twitter-share-button" data-count="horizontal">Tweet</a>'
        						.'<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>' );
    }

    /**
     * @brief Livedoor Clip
     */
    function livedoor()
    {
        $url = "http://clip.livedoor.com/redirect?link={$this->encode_url}&title={$this->encode_blogname}%20-%20{$this->encode_title}&ie=utf-8";
        $alt = __( "Bookmark this on Livedoor Clip", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/livedoor.gif";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    function livedoor_users()
    {
        $url = "http://clip.livedoor.com/page/{$this->url}";
        $alt = sprintf( __("Livedoor Clip - %s", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN), $this->title );
        $icon = "http://image.clip.livedoor.com/counter/{$this->url}";
        return $this->link( $url, $alt, $icon, null, null );
    }
    
    /**
     * @brief Yahoo!JAPAN Bookmark
     */
    function yahoo()
    {
        $url = "http://bookmarks.yahoo.co.jp/bookmarklet/showpopup?t={$this->encode_title}&u={$this->encode_url}&ei=UTF-8";
        $alt = __( "Bookmark this on Yahoo Bookmark", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/yahoo.gif";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    function yahoo_users()
    {
        return $this->link_raw( "<script src='http://num.bookmarks.yahoo.co.jp/numimage.js?disptype=small'></script>" );
    }
    
    /**
     * @brief Yahoo Buzz
     */
    function yahoo_buzz()
    {
        $url = "http://buzz.yahoo.com/buzz?targetUrl={$this->encode_url}&headline={$this->encode_title}";
        $alt = __( "Buzz This", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/yahoo_buzz.png";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
    /**
     * @brief BuzzURL
     */
    function buzzurl()
    {
        $url = "http://buzzurl.jp/entry/{$this->url}";
        $alt = __( "Bookmark this on BuzzURL", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/buzzurl.gif";
        return $this->link( $url, $alt, $icon, 21, 15 );
    }
    function buzzurl_users()
    {
        $url = "http://buzzurl.jp/entry/{$this->url}";
        $alt = sprintf( __("BuzzURL - %s", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN), $this->title );
        $icon = "http://api.buzzurl.jp/api/counter/v1/image?url={$this->encode_url}";
        return $this->link( $url, $alt, $icon, null, null );
    }
    
    /**
     * @brief nifty clip
     */
    function nifty()
    {
        $url = "http://clip.nifty.com/create?url={$this->encode_url}&title={$this->encode_title}";
        $alt = __( "Bookmark this on @nifty clip", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/nifty.gif";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    function nifty_users()
    {
    	$url = '#';
        $alt = sprintf( __("@nifty clip - %s", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN), $this->title );
        $icon = "http://api.clip.nifty.com/api/v1/image/counter/{$this->url}";
        return $this->link( $url, $alt, $icon, null, null );
    }
    
    /**
     * @brief Tumblr
     */
    function tumblr()
    {
        $url = "http://www.tumblr.com/share?v=3&u={$this->encode_url}&t={$this->encode_title}";
        $alt = __( "Share on Tumblr", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/tumblr.png";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
    /**
     * @brief FC2 Bookmark
     */
    function fc2()
    {
        $url = "http://bookmark.fc2.com/user/post?url={$this->encode_url}&title={$this->encode_title}";
        $alt = __( "Bookmark this on FC2 Bookmark", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/fc2.gif";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    function fc2_users()
    {
        $url = "http://bookmark.fc2.com/search/detail?url={$this->encode_url}";
        $alt = sprintf( __("FC2 Bookmark - %s", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN), $this->title );
        $icon = "http://bookmark.fc2.com/image/users/{$this->url}";
        return $this->link( $url, $alt, $icon, null, null );
    }
    
    /**
     * @brief newsing
     */
    function newsing()
    {
        $url = "http://newsing.jp/nbutton?url={$this->encode_url}&title={$this->encode_title}";
        $alt = __( "Newsing it!", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/newsing.gif";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
    /**
     * @brief Choix
     */
    function choix()
    {
        $url = "http://www.choix.jp/bloglink/{$this->url}";
        $alt = __( "Choix it!", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/choix.gif";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
    /**
     * @brief Google Bookmarks
     */
    function google()
    {
        $url = "http://www.google.com/bookmarks/mark?op=add&bkmk={$this->encode_url}&title={$this->encode_title}";
        $alt = __( "Bookmark this on Google Bookmarks", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/google.png";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
    /**
     * @brief Google Buzz
     */
    function google_buzz()
    {
    	$url = "http://www.google.com/buzz/post?url={$this->encode_url}&message={$this->encode_title}";
    	$alt = __( "Post to Google Buzz", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/google-buzz.png";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
    /**
     * @brief Delicious
     */
    function delicious()
    {
        $url = "http://delicious.com/save?url={$this->encode_url}&title={$this->encode_title}";
        $alt = __( "Bookmark this on Delicious", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/delicious.png";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
    /**
     * @brief Digg
     */
    function digg()
    {
        $url = "http://digg.com/submit?url={$this->encode_url}&title={$this->encode_title}";
        $alt = __( "Bookmark this on Digg", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/digg.png";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
    /**
     * @brief Friend feed
     */
    function friendfeed()
    {
        $url = "http://friendfeed.com/?url={$this->encode_url}&title={$this->encode_title}";
        $alt = __( "Share on FriendFeed", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/friendfeed.png";
        return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
    /**
     * @brief Facebook
     */
    function facebook()
    {
        $url = "http://www.facebook.com/share.php?u={$this->encode_url}&t={$this->encode_title}";
        $alt = __( "Share on Facebook", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/facebook.png";
    	return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
   	/**
   	 * @brief reddit
   	 */
    function reddit()
    {
        $url = "http://www.reddit.com/submit?url={$this->encode_url}&title={$this->encode_title}";
        $alt = __( "Share on reddit", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/reddit.png";
    	return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
    /**
     * @brief LinkedIn
     */
    function linkedin()
    {
        $url = "http://www.linkedin.com/shareArticle?mini=true&url={$this->encode_url}&title={$this->encode_title}";
        $alt = __( "Share on LinkedIn", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/linkedin.png";
    	return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
    /**
     * @brief Evernote
     */
    function evernote()
    {
	  	$icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/evernote.png";
	  	$script = "(function(){EN_CLIP_HOST='http://www.evernote.com';try{var x=document.createElement('SCRIPT');x.type='text/javascript';x.src=EN_CLIP_HOST+'/public/bookmarkClipper.js?'+(new Date().getTime()/100000);document.getElementsByTagName('head')[0].appendChild(x);}catch(e){location.href=EN_CLIP_HOST+'/clip.action?url='+encodeURIComponent(location.href)+'&title='+encodeURIComponent(document.title);}})();";
	  	$img = "<img src='${icon}' width='16' height='16' />";
		return $this->link_raw( "<a href='#' title='Clip to Evernote' onclick=\"${script} return false;\">${img}</a>" );
	}
    
    /**
     * @brief Instapaper
     */
    function instapaper()
    {
	  	$href = "javascript:function iprl5(){var d=document,z=d.createElement(&#039;scr&#039;+&#039;ipt&#039;),b=d.body,l=d.location;try{if(!b)throw(0);d.title=&#039;(Saving...) &#039;+d.title;z.setAttribute(&#039;src&#039;,l.protocol+&#039;//www.instapaper.com/j/GKo8MDzHWjRx?u=&#039;+encodeURIComponent(l.href)+&#039;&amp;t=&#039;+(new Date().getTime()));b.appendChild(z);}catch(e){alert(&#039;Please wait until the page has loaded.&#039;);}}iprl5();void(0)";
		return $this->link_raw( '<a href="'.$href.'" class="wp_social_bookmarking_light_instapaper" title="Read Later">Read Later</a>' );
    }
    
    /**
     * @brief StumbleUpon
     */
    function stumbleupon()
    {
        $url = "http://www.stumbleupon.com/submit?url={$this->encode_url}&title={$this->encode_title}";
        $alt = __( "Share on StumbleUpon", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/stumbleupon.png";
    	return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
    /**
     * @brief mixi Check
     */
    function mixi()
    {
    	$options = wp_social_bookmarking_light_options();
    	$data_key = $options['mixi_check_key'];
    	
    	return $this->link_raw( '<a href="http://mixi.jp/share.pl" class="mixi-check-button" data-button="button-3" data-key="'.$data_key.'">Check</a>'
    						   .'<script type="text/javascript" src="http://static.mixi.jp/js/share.js"></script>' );
    }
    
    /**
     * @brief GREE Social Feedback
     */
    function gree()
    {
        $url = "http://gree.jp/?mode=share&act=write&url={$this->encode_url}&title={$this->encode_title}&site_type=website";
        $alt = __( "Share on GREE", WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN );
        $icon = WP_SOCIAL_BOOKMARKING_LIGHT_IMAGES_URL."/gree.png";
    	return $this->link( $url, $alt, $icon, 16, 16 );
    }
    
}

function wp_social_bookmarking_light_default_options()
{
    return array( "services" => "hatena, hatena_users, facebook, google_buzz, yahoo, livedoor, friendfeed, tweetmeme",
                  "position" => "top",
                  "single_page" => true,
                  "is_page" => true,
                  "mixi_check_key" => "",
       			  "mixi_check_robots" => "noimage" );
}

function wp_social_bookmarking_light_options()
{
    return array_merge( wp_social_bookmarking_light_default_options(), get_option("wp_social_bookmarking_light_options", array()) );
}

function wp_social_bookmarking_light_wp_head()
{
	// for mixi Check
	$options = wp_social_bookmarking_light_options();
	foreach( explode(",", $options['services']) as $service ){
		$service = trim($service);
		if( "mixi" == $service ){
?>
<meta name="mixi-check-robots" content="<?php echo $options['mixi_check_robots'] ?>" />
<?php
			break;
		}
	}
	
?>
<style>
ul.wp_social_bookmarking_light{list-style:none !important;border:0 !important;padding:0;margin:0;}
ul.wp_social_bookmarking_light li{float:left !important;border:0 !important;padding:0 4px 0 0 !important;margin:0 !important;height:17px !important;text-indent:0 !important;}
ul.wp_social_bookmarking_light li:before{content:"" !important;}
ul.wp_social_bookmarking_light img{border:0 !important;padding:0;margin:0;vertical-align:baseline !important;}
.wp_social_bookmarking_light_clear{clear:both !important;}
a.wp_social_bookmarking_light_instapaper {display: inline-block;font-family: 'Lucida Grande', Verdana, sans-serif;font-weight: bold;font-size: 11px;-webkit-border-radius: 8px;-moz-border-radius: 8px;color: #fff;background-color: #626262;border: 1px solid #626262;padding: 0px 3px 0px;text-shadow: #3b3b3b 1px 1px 0px;min-width: 62px;text-align: center;vertical-align: top;line-height:17px;}
a.wp_social_bookmarking_light_instapaper, a.wp_social_bookmarking_light_instapaper:hover, a.wp_social_bookmarking_light_instapaper:active, a.wp_social_bookmarking_light_instapaper:visited {color: #fff; text-decoration: none; outline: none;}
.wp_social_bookmarking_light_instapaper:focus {outline: none;}
</style>
<?php
}

function wp_social_bookmarking_light_admin_menu()
{
    if( function_exists('add_options_page') ){
        add_options_page( 'WP Social Bookmarking Light', 
                          'WP Social Bookmarking Light', 
                          'manage_options', 
                          __FILE__, 
                          'wp_social_bookmarking_light_options_page' );
    }
}

function wp_social_bookmarking_light_output( $services )
{
    $wp = new WpSocialBookmarkingLight( get_permalink(), get_the_title(), get_bloginfo('name') );

    $out = '';
    foreach( explode(",", $services) as $service ){
        $service = trim($service);
        $out .= call_user_func( array( $wp, $service ) ); // call WpSocialBookmarkingLight method
    }
    if( $out == '' ){
        return $out;
    }
    return "<ul class='wp_social_bookmarking_light'>{$out}</ul><br class='wp_social_bookmarking_light_clear' />";
}

function wp_social_bookmarking_light_output_e( $services )
{
	echo wp_social_bookmarking_light_output( $services );
}

function wp_social_bookmarking_light_the_content( $content )
{
    if( is_feed() || is_404() || is_robots() || is_comments_popup() || (function_exists( 'is_ktai' ) && is_ktai()) ){
       return $content;
    }
    
    $options = wp_social_bookmarking_light_options();
    if( $options['single_page'] && !is_singular() ){
        return $content;
    }
    if( !$options['is_page'] && is_page() ){
    	return $content;
    }

    $out = wp_social_bookmarking_light_output( $options['services'] );
    if( $out == '' ){
       return $content;
    }
    if( $options['position'] == 'top' ){
        return "{$out}{$content}";
    }
    else if( $options['position'] == 'bottom' ){
        return "{$content}{$out}";
    }
    return $content;
}

function wp_social_bookmarking_light_init()
{
    add_action( 'wp_head', 'wp_social_bookmarking_light_wp_head' );
    add_action( 'admin_menu', 'wp_social_bookmarking_light_admin_menu' );
    add_filter( 'the_content', 'wp_social_bookmarking_light_the_content' );
}
add_action( 'init', 'wp_social_bookmarking_light_init' );

// options page
function wp_social_bookmarking_light_options_page()
{
    if( isset( $_POST['save'] ) ){
        $options = array( "services" => $_POST["services"],
                          "position" => $_POST["position"],
                          "single_page" => $_POST["single_page"] == 'true',
                          "is_page" => $_POST["is_page"] == 'true',
        				  "mixi_check_key" => $_POST["mixi_check_key"],
        				  "mixi_check_robots" => $_POST["mixi_check_robots"] );
        update_option( 'wp_social_bookmarking_light_options', $options );
        echo '<div class="updated"><p><strong>'.__( 'Options saved.', WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN ).'</strong></p></div>';
    }
    else if( isset( $_POST['reset'] ) ){
    	$options = wp_social_bookmarking_light_default_options();
        update_option( 'wp_social_bookmarking_light_options', $options );
        echo '<div class="updated"><p><strong>'.__( 'Reset options.', WP_SOCIAL_BOOKMARKING_LIGHT_DOMAIN ).'</strong></p></div>';
    }
    else{
        $options = wp_social_bookmarking_light_options();
    }
?>
<style>
.wp_social_bookmarking_light_options{
    border: 1px solid #CCCCCC;
    background-color: #F8F8EB;
    vertical-align: top;
    margin: 0px 10px 10px 0px;
    padding: 0px;
}
.wp_social_bookmarking_light_options th{
    background-color: #E8E8DB;
    text-align: center;
    margin: 0px;
    padding: 3px;
}
.wp_social_bookmarking_light_options td{
    text-align: left;
    margin: 0px;
    padding: 3px;
}
</style>
<?php 
wp_enqueue_script('jquery');
?>
<script type="text/javascript" charset="utf-8">
//<![CDATA[
// for mixi
function wsbl_options_mixi_toggle( first )
{
	var val = jQuery("#services_id").val();
	var vals = val.split(",");
	var has_mixi = false;
	for( var i = 0; i < vals.length; i++ ){
		val = vals[i].replace(/(^\s+)|(\s+$)/g, "");
		if( val == "mixi" ){
			has_mixi = true;
		}
	}

	if( first ){
		has_mixi ? jQuery("#mixi_settings").show() : jQuery("#mixi_settings").hide();
	}
	else{
		has_mixi ? jQuery("#mixi_settings").slideDown() : jQuery("#mixi_settings").slideUp();
	}
}

// read onece
jQuery(document).ready(function(){
	jQuery("#services_id").keyup(function(){
		wsbl_options_mixi_toggle();
	});
	wsbl_options_mixi_toggle( true );
});
//]]>
</script>
<div class="wrap">
    <h2>WP Social Bookmarking Light</h2>
    
    <form method='POST' action="<?php echo $_SERVER['REQUEST_URI'] ?>">
    <h3><?php _e("General Settings") ?></h3>
    <table class='form-table'>
    <tr>
    <th scope="row">Position:</th>
    <td>
    <select name='position'>
    <option value='top' <?php if( $options['position'] == 'top' ) echo 'selected'; ?>>Top</option>
    <option value='bottom' <?php if( $options['position'] == 'bottom' ) echo 'selected'; ?>>Bottom</option>
    <option value='none' <?php if( $options['position'] == 'none' ) echo 'selected'; ?>>None</option>
    </select>
    </td>
    </tr>
    <tr>
    <th scope="row">Is Singular:</th>
    <td>
    <select name='single_page'>
    <option value='true' <?php if( $options['single_page'] == true ) echo 'selected'; ?>>Enabled</option>
    <option value='false' <?php if( $options['single_page'] == false ) echo 'selected'; ?>>Disabled</option>
    </select>
    </td>
    </tr>
    <tr>
    <th scope="row">Is Page:</th>
    <td>
    <select name='is_page'>
    <option value='true' <?php if( $options['is_page'] == true ) echo 'selected'; ?>>Enabled</option>
    <option value='false' <?php if( $options['is_page'] == false ) echo 'selected'; ?>>Disabled</option>
    </select>
    </td>
    </tr>
    <tr>
    <th scope="row">Services: <br/> <span style="font-size:10px">(comma-separated)</span></th>
    <td><input type="text" id='services_id' name='services' value="<?php echo $options['services'] ?>"size=80 /></td>
    </tr>
    </table>

	<div id='mixi_settings'>
    <h3><?php _e("mixi Settings") ?></h3>
    <table class='form-table'>
    <tr>
    <th scope="row">mixi check key:</th>
    <td>
    <input type="text" name='mixi_check_key' value="<?php echo $options['mixi_check_key'] ?>" size=50 />
    </td>
    </tr>
    <tr>
    <th scope="row">mixi check robots:</th>
    <td>
    <input type="text" name='mixi_check_robots' value="<?php echo $options['mixi_check_robots'] ?>" size=50 />
    </td>
    </tr>
    </table>
	</div>

    <p class="submit">
    <input class="button-primary" type="submit" name='save' value='<?php _e('Save Changes') ?>' />
    <input type="submit" name='reset' value='<?php _e('Reset') ?>' />
    </p>
    </form>
    
    <table class='wp_social_bookmarking_light_options'>
    <tr><th><?php _e("Service Code") ?></th><th><?php _e("Explain") ?></th></tr>
    <tr><td>hatena</td><td>Hatena Bookmark</td></tr>
    <tr><td>hatena_users</td><td>Hatena Bookmark Users</td></tr>
    <tr><td>twib</td><td>Twib - Twitter</td></tr>
    <tr><td>twib_users</td><td>Twib Users - Twitter</td></tr>
    <tr><td>tweetmeme</td><td>TweetMeme - Twitter</td></tr>
    <tr><td>twitter</td><td>Tweet Button - Twitter</td></tr>
    <tr><td>livedoor</td><td>Livedoor Clip</td></tr>
    <tr><td>livedoor_users</td><td>Livedoor Clip Users</td></tr>
    <tr><td>yahoo</td><td>Yahoo!JAPAN Bookmark</td></tr>
    <tr><td>yahoo_users</td><td>Yahoo!JAPAN Bookmark Users</td></tr>
    <tr><td>yahoo_buzz</td><td>Yahoo!Buzz</td></tr>
    <tr><td>buzzurl</td><td>BuzzURL</td></tr>
    <tr><td>buzzurl_users</td><td>BuzzURL Users</td></tr>
    <tr><td>nifty</td><td>@nifty Clip</td></tr>
    <tr><td>nifty_users</td><td>@nifty Clip Users</td></tr>
    <tr><td>tumblr</td><td>Tumblr</td></tr>
    <tr><td>fc2</td><td>FC2 Bookmark</td></tr>
    <tr><td>fc2_users</td><td>FC2 Bookmark Users</td></tr>
    <tr><td>newsing</td><td>newsing</td></tr>
    <tr><td>choix</td><td>Choix</td></tr>
    <tr><td>google</td><td>Google Bookmarks</td></tr>
    <tr><td>google_buzz</td><td>Google Buzz</td></tr>
    <tr><td>delicious</td><td>Delicious</td></tr>
    <tr><td>digg</td><td>Digg</td></tr>
    <tr><td>friendfeed</td><td>FriendFeed</td></tr>
    <tr><td>facebook</td><td>Facebook Share</td></tr>
    <tr><td>reddit</td><td>reddit</td></tr>
    <tr><td>linkedin</td><td>LinkedIn</td></tr>
    <tr><td>evernote</td><td>Evernote</td></tr>
    <tr><td>instapaper</td><td>Instapaper</td></tr>
    <tr><td>stumbleupon</td><td>StumbleUpon</td></tr>
    <tr><td>mixi</td><td>mixi Check (require <a href="http://developer.mixi.co.jp/connect/mixi_plugin/mixi_check/mixicheck" onclick="window.open('http://developer.mixi.co.jp/connect/mixi_plugin/mixi_check/mixicheck'); return false;" >mixi check key</a>)</td></tr>
    <tr><td>gree</td><td>GREE Social Feedback</td></tr>
    </table>
</div>
<?php
}



?>
