<?php
/* ======================================================================

  Plugin Name: Widgetize Navigation Menu
  Plugin URI: http://www.lendingapaw.org/plugins/widgetize-navigation-menu/
  Description: Allows you to add any widget to your navigation’s drop-down menus with an easy to use interface. Comes with four custom widgets.
  Version: 1.03
  Author: Stephanie Chow
  Author URI: http://www.lendingapaw.org
  License: GPLv2 or later
*/
/*
Copyright 2014 Stephanie Chow (email : stephanie@lendingapaw.com)

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

*/

 /* ======================================================================
    LOAD SCRIPTS
  ====================================================================== */
//ADMIN STYLES & SCRIPTS
add_action( 'admin_enqueue_scripts', 'widgetize_menu_enqueue_admin' );
function widgetize_menu_enqueue_admin( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_script('jquery');
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'widgetize_menu_script_backend', plugins_url('/js/widgetize_menu_backend_script.js', __FILE__ ), array( 'jquery','wp-color-picker' ));
    wp_register_style( 'widgetize_menu_style_backend', plugins_url('/css/widgetize_menu_backend_style.css', __FILE__ ));
    wp_enqueue_style('widgetize_menu_style_backend');
    wp_enqueue_media();
    wp_enqueue_script( 'buttoncreator_widget', plugins_url('js/buttoncreator_widget.js', __FILE__), array( 'jquery', 'media-upload'));
    wp_localize_script( 'buttoncreator_widget', 'ButtonCreatorWidget', array(
      'frame_title' => __( 'Select an Image', 'image_widget' ),
      'button_title' => __( 'Insert Into Widget', 'image_widget' ),
    ) );
}
//FRONTEND STYLES & SCRIPTS
add_action( 'wp_enqueue_scripts', 'widgetize_menu_enqueue_public');
function widgetize_menu_enqueue_public() {
  wp_enqueue_script('jquery');
  wp_register_script( 'widgetize_menu_script_frontend', plugins_url('/js/widgetize_menu_frontend_script.js', __FILE__ ), array( 'jquery'));
  wp_enqueue_script('widgetize_menu_script_frontend');
  wp_register_style( 'widgetize_menu_style_frontend', plugins_url('/css/widgetize_menu_frontend_style.css', __FILE__ ));
  wp_enqueue_style('widgetize_menu_style_frontend');
   wp_register_style( 'advwidgets_styles', plugins_url('/css/advwidgets_styles.css', __FILE__ ));
  wp_enqueue_style('advwidgets_styles');
}
/* =============================================================
  INCLUDE WIDGETS
 * ============================================================= */
include( dirname(__FILE__) . '/widgets/advanced-pages-widget.php' );
include( dirname(__FILE__) . '/widgets/advanced-categories-widget.php' );
include( dirname(__FILE__) . '/widgets/advanced-recent-posts-widget.php' );
include( dirname(__FILE__) . '/widgets/button-creator-widget.php' );
/* =============================================================
	FUNCTION TO ADD CUSTOM DROPDOWN MENUS
	//user replaces
	//wp_nav_menu($menu_name)
	//with
	//menu_with_custom_dropdown_menus()
 * ============================================================= */
function widgetize_my_dropdown_menus($menu_name){
  global $custom_dropdown_options;
	$locations = get_nav_menu_locations();
	$menu = wp_get_nav_menu_object( $locations[ $menu_name ] );
	$menuitems = wp_get_nav_menu_items( $menu->term_id, array( 'order' => 'DESC' ) );
  //GET DEFAULT COLORS
  $dd_color_navbar= ($custom_dropdown_options['dd_color_navbar']=='' ? '#555' : $custom_dropdown_options['dd_color_navbar']);
  $dd_color_navbar_mobile= ($custom_dropdown_options['dd_color_navbar']=='' ? '#555' : $custom_dropdown_options['dd_color_navbar']);
  $dd_color_main_link= ($custom_dropdown_options['dd_color_main_link']=='' ? '#ddd' : $custom_dropdown_options['dd_color_main_link']);
  $dd_color_main_link_hovered= ($custom_dropdown_options['dd_color_main_link_hovered']=='' ? '#fff' : $custom_dropdown_options['dd_color_main_link_hovered']);
  $dd_color_main_bg_hovered= ($custom_dropdown_options['dd_color_main_bg_hovered']=='' ? '' : $custom_dropdown_options['dd_color_main_bg_hovered']);
  $dd_color_main_link_hovered_hassubmenu= ($custom_dropdown_options['dd_color_main_link_hovered_hassubmenu']=='' ? '#555' : $custom_dropdown_options['dd_color_main_link_hovered_hassubmenu']);
  $dd_color_main_bg_hovered_hassubmenu= ($custom_dropdown_options['dd_color_main_bg_hovered_hassubmenu']=='' ? '#fff' : $custom_dropdown_options['dd_color_main_bg_hovered_hassubmenu']);
  $dd_color_main_link_active= ($custom_dropdown_options['dd_color_main_link_active']=='' ? '#fff' : $custom_dropdown_options['dd_color_main_link_active']);
  $dd_color_main_bg_active= ($custom_dropdown_options['dd_color_main_bg_active']=='' ? '' : $custom_dropdown_options['dd_color_main_bg_active']);
  $dd_color_submenu_link= ($custom_dropdown_options['dd_color_submenu_link']=='' ? '#777' : $custom_dropdown_options['dd_color_submenu_link']);
  $dd_color_submenu_bg= ($custom_dropdown_options['dd_color_submenu_bg']=='' ? '#fff' : $custom_dropdown_options['dd_color_submenu_bg']);
  $dd_color_submenu_link_hovered= ($custom_dropdown_options['dd_color_submenu_link_hovered']=='' ? '#111' : $custom_dropdown_options['dd_color_submenu_link_hovered']);
  $dd_color_submenu_bg_hovered= ($custom_dropdown_options['dd_color_submenu_bg_hovered']=='' ? '#eee' : $custom_dropdown_options['dd_color_submenu_bg_hovered']);
  $dd_color_submenu_link_active= ($custom_dropdown_options['dd_color_submenu_link_active']=='' ? '#fff' : $custom_dropdown_options['dd_color_submenu_link_active']);
  $dd_color_submenu_bg_active= ($custom_dropdown_options['dd_color_submenu_bg_active']=='' ? '#666' : $custom_dropdown_options['dd_color_submenu_bg_active']);


  //*--------------SHOW STYLE SHEET WITH CUSTOM COLORS----------------*//
  if($custom_dropdown_options['dd_import-nav-colors']!='on'){
  //COLOR STYLES//////////////////
  echo "<style type='text/css'>
  /*NAVBAR*/
  nav.widgetized-nav{background: ".$dd_color_navbar."!important;}
  /*MAIN MENU LINK*/
  nav.widgetized-nav .main-nav.first-level-menu > li > a{color:".$dd_color_main_link."!important;}
  /*MAIN MENU LINK (ACTIVE)*/
  nav.widgetized-nav .main-nav.first-level-menu > li.current-menu-item > a, nav.widgetized-nav .main-nav.first-level-menu > li.current-menu-item:hover > a{color:".$dd_color_main_link_active."!important;}
  /*MAIN MENU BG (ACTIVE)*/
    nav.widgetized-nav .main-nav.first-level-menu > li.current-menu-item, nav.widgetized-nav .main-nav.first-level-menu > li.current-menu-item:hover {background:".$dd_color_main_bg_active."!important;}
  /*SUBMENU LINK (in non-wgt menu only)*/
  nav.widgetized-nav .main-nav .sub-menu.non-wgt-menu li > a{color:".$custom_dropdown_options['dd_color_submenu_link']."!important;}
  /*SUBMENU BG (all submenus)*/
   nav.widgetized-nav .main-nav .sub-menu{background:".$dd_color_submenu_bg."!important;}
   /*SEARCH BAR ICON IN SUBMENU (matches submenu style)*/
   nav.widgetized-nav .main-nav li.item.menu-item-searchItem .sub-menu.second-level-menu #searchsubmit{
    color:".$custom_dropdown_options['dd_color_submenu_link'].";
    background:".$dd_color_submenu_bg.";
   }
   /*SUBMENU LINK (ACTIVE) (in non-wgt menu only)*/
    nav.widgetized-nav .main-nav .sub-menu.non-wgt-menu li.current-menu-sub-item > a,
    nav.widgetized-nav .main-nav .sub-menu.non-wgt-menu li.current-menu-sub-item:hover > a{
      color:".$dd_color_submenu_link_active."!important;}
  /*SUBMENU BG (ACTIVE) (in non-wgt menu only)*/
   nav.widgetized-nav .main-nav .sub-menu.non-wgt-menu li.current-menu-sub-item,
   nav.widgetized-nav .main-nav .sub-menu.non-wgt-menu li.current-menu-sub-item:hover {
    background:".$dd_color_submenu_bg_active."!important;}
  /*SPECIAL BORDER ON ALL DROPDOWNS*/
    nav.widgetized-nav .main-nav .sub-menu, nav.widgetized-nav .main-nav .sub-menu {border:none!important;}
   nav.widgetized-nav .main-nav .sub-menu, nav.widgetized-nav .main-nav .sub-menu > li{border:none!important;}
  /*LINKS IN WIDGETS*/
    nav.widgetized-nav .sub-menu.widgetized-menu li.menu-widget-column a{color:".$dd_color_submenu_link.";} /*(links in widgets same as submenu)*/
  ".$custom_dropdown_options['dd_customcss-option']."
    @media screen and (min-width: 900px) {
      /*SUBMENU LINK (HOVER)*/
      nav.widgetized-nav .main-nav .sub-menu.non-wgt-menu li:hover > a{color:".$dd_color_submenu_link_hovered."!important;}
      /*SUBMENU BG (HOVER)*/
      nav.widgetized-nav .main-nav .sub-menu.non-wgt-menu li:hover {background:".$dd_color_submenu_bg_hovered."!important;}
      /*MAIN MENU LINK (HOVER & NO SUBMENU)*/
      nav.widgetized-nav .main-nav.first-level-menu > li:hover:not(.menu-item-socialMediaIcons) > a,
      nav.widgetized-nav .main-nav.first-level-menu > li.menu-item-socialMediaIcons > a:hover{color:".$dd_color_main_link_hovered."!important;}
      /*MAIN MENU BG (HOVER & NO SUBMENU)*/
      nav.widgetized-nav .main-nav.first-level-menu > li:hover{background:".$dd_color_main_bg_hovered."!important;}
      /*MAIN MENU LINK (HOVER & HAS SUBMENU)*/
      nav.widgetized-nav .main-nav.first-level-menu > li.hasChildren:hover > a{color:".$dd_color_main_link_hovered_hassubmenu."!important;}
      /*MAIN MENU BG (HOVER & HAS SUBMENU)*/
      nav.widgetized-nav .main-nav.first-level-menu > li.hasChildren:hover{background:".$dd_color_main_bg_hovered_hassubmenu."!important;}
    }
    @media screen and (max-width: 899px) {
    /*MAIN MENU LINKS BG SAME COLOR AS NAVBAR*/
    nav.widgetized-nav .main-nav.first-level-menu, nav.widgetized-nav .main-nav.first-level-menu > li{background: ".$dd_color_navbar."!important;}
    nav.widgetized-nav .main-nav .sub-menu.non-wgt-menu li{border-bottom: 1px solid rgba(0, 0, 0, 0.2)!important;}
    nav.widgetized-nav .main-nav .sub-menu.non-wgt-menu li:last-child{border:none!important;}
    }
  </style>";
  }
  //*--------------SHOW STYLE SHEET WITH IMPORTED COLORS----------------*//
  else{
    echo $custom_dropdown_options['dd_customcss-option'];
  }
  //END COLOR STYLES////////////////////
  echo '<div class="widgetized-menu-button"><span class="menu-item-list"><span class="menu-item-title">MENU</span><i class="icowdgt-menu"></i></span></div>';
	if ($custom_dropdown_options['widthWidgetsMenu']!='full-width'){
  //give nav of position:relative if dropdown constrained to width of navbar
  echo '<nav class="widgetized-nav navWidth-navBar">';
  }
  else{
  echo '<nav class="widgetized-nav">';
  }
	echo '<ul class="main-nav first-level-menu">';
    $count = 0;
    $display_custom=true;
    $maincategory = get_category( get_query_var( 'cat' ) );
    $maincatid = $maincategory->cat_ID;
    $mainpageid=get_the_ID();
    $submenu = false;
    $subsubmenu = false;
    foreach( $menuitems as $item ):
        // get page id from using menu item object id
        $id = get_post_meta( $item->ID, '_menu_item_object_id', true );
        // set up a page object to retrieve page data
        $page = get_page( $id );
        $link = $item->url;
        $title = $item->title;
        $title_condensed = preg_replace("/[^A-Za-z0-9]/", "", $title);//remove spacing/weird characters
        $title_condensed = strtolower($title_condensed);//lowercase all

        // item does not have a parent so menu_item_parent equals 0 (false)
        if ( !$item->menu_item_parent ):
        // save this id for later comparison with sub-menu items
            $parent_id = $item->ID;
            if ($maincatid==$item->object_id || $mainpageid==$item->object_id){//if this is current menu item add class
            echo '<li class="item menu-item-'.$title_condensed.' current-menu-item" id="menu-item-'.$item->ID.'">';}
            else{
            echo '<li class="item menu-item-'.$title_condensed.'" id="menu-item-'.$item->ID.'">';
            }
            echo '<a href="'.$link.'" class="title">'.$title.'</a>';
    	endif;

    /* =============================================================
          CUSTOM DROPDOWN SUBMENU
      * ============================================================= */
    if (isset($custom_dropdown_options['menutitlearray']) && $custom_dropdown_options['menutitlearray']!=""){
      $oldmenutitle='';
      foreach ($custom_dropdown_options['menutitlearray'] as $menutitle) {
        $menutitlesingle = substr($menutitle, 0, -4);//remove dash number at endhttp://localhost/locustec_local/wp-content/themes/minamaze/images/slideshow/featured1.png
        if ($title==$menutitlesingle){//if menu name is equal to array item, it is a widgetized menu
          if ( !$item->menu_item_parent ){//to ensure only widgetizing 1st level menu items, make sure no parent
            $menucolumnnum = substr($menutitle, -1); // get last char in menutitle to get column number
            $menucolumnclass = 'col-xs-'.(12 / $custom_dropdown_options['numWidgetsMenu_'.$id]);//get bootstrap column class

            if ($menucolumnnum==1){//if has number 1 at end (meaning 1 column in dropdown)
              echo '<ul class="sub-menu row second-level-menu widgetized-menu">';
            }
              echo '<li class="menu-widget-column widget-column-'.$menucolumnnum.' '.$menucolumnclass.'">';
                    dynamic_sidebar( $menutitle );
              echo '</li>';
            if ($menucolumnnum==$custom_dropdown_options['numWidgetsMenu_'.$id]){//if has last number at end
              echo '</ul>';
            }
          }//end check if no parent
        }//END if menu name equal to array item
        $oldmenutitle=$menutitlesingle;
      }//end for each
    }
      /* =============================================================
          NORMAL SUBMENU
      * ============================================================= */
      if ( $parent_id == $item->menu_item_parent):
       	  if ( !$submenu ): $submenu = true;
            echo '<ul class="sub-menu second-level-menu non-wgt-menu">';
       	  endif;
          if ($maincatid==$item->object_id || $mainpageid==$item->object_id){//if this is current menu item add class
            echo '<li class="sub-item current-menu-sub-item">';
          }
          else{
            echo '<li class="sub-item">';
          }
            echo '<a href="'.$link.'" class="title">'.$title.'</a>';
            //echo '<a href="'.$link.'" class="desc">'.$page->post_excerpt.'</a>';
      endif;
      //SEE IF THERE IS A SUB-SUB MENU
      if ( $parent_id != $item->menu_item_parent && $item->menu_item_parent)://if item in a sub-sub-menu
            if ( !$subsubmenu ): $subsubmenu = true;
              echo '<ul class="sub-menu third-level-menu">';
            endif;
           if ($maincatid==$item->object_id || $mainpageid==$item->object_id){//if this is current menu item add class
                echo '<li class="sub-item current-menu-sub-item">';
             }
             else{
                echo '<li class="sub-item">';
             }
             echo '<a href="'.$link.'" class="title">'.$title.'</a>';
            //echo '<a href="'.$link.'" class="desc">'.$page->post_excerpt.'</a>';
            echo '</li>';
            if ( $menuitems[ $count + 1 ]->menu_item_parent != $item->menu_item_parent && $subsubmenu):
              $subsubmenu=false;
            echo '</ul>';
            endif;
        endif;

        if ( $menuitems[ $count + 1 ]->menu_item_parent != $parent_id && $submenu && $subsubmenu==false && $menuitems[ $count + 1 ]->menu_item_parent != $item->ID):
              echo '</li>';
            	echo '</ul>';
				$submenu = false;
        $widgetized_submenu=false;

			endif;
	    if ( $menuitems[ $count + 1 ]->menu_item_parent != $parent_id && $subsubmenu==false && $menuitems[ $count + 1 ]->menu_item_parent != $item->ID ):
		  echo '</li>';
		  $submenu = false;

	     endif;
	$count++;
endforeach;
//ADD SOCIAL MEDIA BTNS?
 if ($custom_dropdown_options['contactOptionMenu']!='' || $custom_dropdown_options['facebookOptionMenu']!='' || $custom_dropdown_options['twitterOptionMenu']!='' ){?>
<li class="item menu-item-socialMediaIcons" id="menu-item-socialMediaIcons">
    <?php if ($custom_dropdown_options['contactOptionMenu']!=''){?>
      <!--ADD CONTACT BTN?-->
      <a class="title" title="Contact" href="<?php echo $custom_dropdown_options['contactOptionMenu'];?>">
          <i class="icowdgt-contact"></i>
          <span class="wgt-menu-icon-title">Contact</span>
      </a>
    <?php }?>
    <?php if ($custom_dropdown_options['facebookOptionMenu']!=''){?>
      <!--ADD FACEBOOK BTN?-->
      <a class="title" title="Facebook" target="_blank" href="<?php echo $custom_dropdown_options['facebookOptionMenu'];?>">
          <i class="icowdgt-facebook"></i>
          <span class="wgt-menu-icon-title">Facebook</span>
      </a>
    <?php }?>
    <?php if ($custom_dropdown_options['twitterOptionMenu']!=''){?>
      <!--ADD TWITTER BTN?-->
      <a class="title" title="Twitter" target="_blank" href="<?php echo $custom_dropdown_options['twitterOptionMenu'];?>">
        <i class="icowdgt-twitter"></i>
        <span class="wgt-menu-icon-title">Twitter</span>
      </a>
    <?php }?>
</li>
<?php }//end if add social media button(s)
//ADD SEARCH BAR?
if ($custom_dropdown_options['searchOptionMenu']=='on'){?>
  <li class="item menu-item-searchItem" id="menu-item-searchItem">
    <a class="title">
        <i class="icowdgt-search"></i>
        <span class="wgt-menu-icon-title">Search</span>
    </a>
     <ul class="sub-menu second-level-menu non-wgt-menu">
     <form method="get" id="searchform" action="<?php bloginfo('home'); ?>/">
        <div>
          <input type="text" size="35" name="s" id="s" value="Search..." onfocus="if(this.value==this.defaultValue)this.value='';" onblur="if(this.value=='')this.value=this.defaultValue;"/>
          <button type="submit" id="searchsubmit">
            <i class="icowdgt-search"></i>
          </button>
        </div>
      </form>
    </ul>
  </li>
<?php }
	echo '</ul>';
	echo '</nav>';

}//end custom menu function
/*FOR AUTO REPLACEMENT OF SELECTED MENU*/
add_filter( 'wp_nav_menu_args' , 'auto_activate_wdgt_menu' , 100 , 2 );

function auto_activate_wdgt_menu($args){
  global $custom_dropdown_options;

  if( $args['theme_location']==$custom_dropdown_options['select_reg_menu'] && $custom_dropdown_options['dd_activate_wdgtnav']=='on' ){
    if (function_exists('widgetize_my_dropdown_menus')) {
          widgetize_my_dropdown_menus($custom_dropdown_options['select_reg_menu']);
      }
  }
  else{
      return $args;
  }
}
/* =============================================================
  REGISTERED NEW SIDEBARS
* ============================================================= */
add_action( 'widgets_init', 'customregistersidebars');
function customregistersidebars(){
  global $custom_dropdown_options;
  if (isset($custom_dropdown_options['menutitlearray'])){
    foreach ($custom_dropdown_options['menutitlearray'] as $menutitle) {
      $menutitle_condensed = preg_replace("/[^A-Za-z0-9]/", "", $menutitle);//remove spacing/weird characters
      $menutitle_condensed = strtolower($menutitle_condensed);//lowercase all
      //if condensed equal just column number, must be foreign or accented, only lowercase, replace spacing
      if ($menutitle_condensed==1 || $menutitle_condensed==2 || $menutitle_condensed==3 || $menutitle_condensed==4){
        $menutitle_condensed = strtolower($menutitle);
        $menutitle_condensed = str_replace(' ', '', $menutitle_condensed);
      }//end if defaulted to 1 due to invalid id
          register_sidebar( array(
            'name' => $menutitle,
            'id' => $menutitle_condensed,
            'before_widget' => '<aside class="widget %2$s">',
            'after_widget' => '</aside>',
            'before_title' => '<h2 class="widget-title widgettitle-in-submenu">',
            'after_title' => '</h2>',
          ) );//end array

    }//end for each
  }//if array for sidebar exists
}//end function customregistersidebars
/* =============================================================
	SETUP PLUGIN SETTINGS PAGE
 * ============================================================= */
add_option("custom-dropdown-menus", $custom_dropdown_options);
$custom_dropdown_options = get_option('custom-dropdown-menus');
add_action('admin_menu', 'custom_dropdown_admin_page');
/* =============================================================
	ADD SETTINGS PAGE TO ADMIN MENU
 * ============================================================= */
function custom_dropdown_admin_page() {
add_theme_page('Add Custom Dropdown Menus', 'Dropdown Menus', 'manage_options', 'menu_dd', 'custom_dropdown_options_page');
}
/* =============================================================
	  WRITE & SAVE SETTINGS PAGE
 * ============================================================= */
function custom_dropdown_options_page() {
   global $custom_dropdown_options;

//IF CHOSE SAVE CHANGES, STORE NEW VALUES
    if(isset($_POST['save_changes'])) {
        check_admin_referer('custom-dropdown-update_settings');

        $custom_dropdown_options['select_reg_menu'] = trim($_POST['select_reg_menu']);
        $select_reg_menu=$custom_dropdown_options['select_reg_menu'];
        $select_locations = get_nav_menu_locations();
        $select_menu = wp_get_nav_menu_object( $select_locations[ $select_reg_menu ] );
        $select_menuitems = wp_get_nav_menu_items( $select_menu->term_id, array( 'order' => 'DESC' ) );
        $mainMenuCount=0;
        $registersidebarlist='';
        $menutitlearray=array();
  if(!empty($select_menuitems)){
		foreach( $select_menuitems as $select_item ):
        // item does not have a parent so menu_item_parent equals 0 (false)
        if ( !$select_item->menu_item_parent ):
          $select_id = get_post_meta( $select_item->ID, '_menu_item_object_id', true );
          $select_page = get_page( $select_id );
          $select_title = $select_item->title;
		      $custom_dropdown_options['menu_dd_addMenu_'.$select_id]  = trim($_POST['menu_dd_addMenu_'.$select_id]);
		      $custom_dropdown_options['numWidgetsMenu_'.$select_id]  = trim($_POST['numWidgetsMenu_'.$select_id]);
          //*--ADD MENU NAMES TO $menutitlearray WITH COLUMN NUMBER AT END--*//
          if($custom_dropdown_options['menu_dd_addMenu_'.$select_id]=='on'){
              $x=1;
              while($x<=$custom_dropdown_options['numWidgetsMenu_'.$select_id]){
                $select_title_with_num = $select_title.' - '.$x;
                array_push($menutitlearray, $select_title_with_num);//add menu title with number to array
                $x++;
              }
            }
         endif;

        endforeach;
      }//end if isset
        //*--SAVE ARRAY--*//
        $custom_dropdown_options['menutitlearray']  = $menutitlearray;
        //*--SAVE ACTIVATE OPTION--*//
        $custom_dropdown_options['dd_activate_wdgtnav'] = trim($_POST['dd_activate_wdgtnav']);
        //*--SAVE WIDTH SELECTION--*//
        $custom_dropdown_options['widthWidgetsMenu'] = trim($_POST['widthWidgetsMenu']);
        //*--SAVE SEARCH OPTION--*//
        $custom_dropdown_options['searchOptionMenu'] = trim($_POST['searchOptionMenu']);
        //*--SAVE SOCIAL MEDIA OPTIONS--*//
        $custom_dropdown_options['contactOptionMenu'] = trim($_POST['contactOptionMenu']);
        $custom_dropdown_options['facebookOptionMenu'] = trim($_POST['facebookOptionMenu']);
        $custom_dropdown_options['twitterOptionMenu'] = trim($_POST['twitterOptionMenu']);
        //*SAVE ALL COLORS*//
        $custom_dropdown_options['dd_color_navbar'] = trim($_POST['dd_color_navbar']);
        $custom_dropdown_options['dd_color_main_link'] = trim($_POST['dd_color_main_link']);
        $custom_dropdown_options['dd_color_main_link_hovered'] = trim($_POST['dd_color_main_link_hovered']);
        $custom_dropdown_options['dd_color_main_bg_hovered'] = trim($_POST['dd_color_main_bg_hovered']);
        $custom_dropdown_options['dd_color_main_link_hovered_hassubmenu'] = trim($_POST['dd_color_main_link_hovered_hassubmenu']);
        $custom_dropdown_options['dd_color_main_bg_hovered_hassubmenu'] = trim($_POST['dd_color_main_bg_hovered_hassubmenu']);
        $custom_dropdown_options['dd_color_main_link_active'] = trim($_POST['dd_color_main_link_active']);
        $custom_dropdown_options['dd_color_main_bg_active'] = trim($_POST['dd_color_main_bg_active']);
        $custom_dropdown_options['dd_color_submenu_link'] = trim($_POST['dd_color_submenu_link']);
        $custom_dropdown_options['dd_color_submenu_bg'] = trim($_POST['dd_color_submenu_bg']);
        $custom_dropdown_options['dd_color_submenu_link_hovered'] = trim($_POST['dd_color_submenu_link_hovered']);
        $custom_dropdown_options['dd_color_submenu_bg_hovered'] = trim($_POST['dd_color_submenu_bg_hovered']);
        $custom_dropdown_options['dd_color_submenu_link_active'] = trim($_POST['dd_color_submenu_link_active']);
        $custom_dropdown_options['dd_color_submenu_bg_active'] = trim($_POST['dd_color_submenu_bg_active']);
        $custom_dropdown_options['dd_import-nav-colors'] = trim($_POST['dd_import-nav-colors']);
        //*SAVE CUSTOM CSS*//
        $custom_dropdown_options['dd_customcss-option'] = trim($_POST['dd_customcss-option']);
        /**//////////////////////////////////////**/
        //*--SAVE ALL VARIABLES--*//
        /**//////////////////////////////////////**/
        update_option('custom-dropdown-menus', $custom_dropdown_options);

        echo "<div class=\"updated\">Your changes have been saved successfully!</div>";
    }
    //IF CHOSE RESET, RESET ALL COLORS
    if(isset($_POST['reset'])) {
      $custom_dropdown_options['dd_color_navbar'] = "#555";
        $custom_dropdown_options['dd_color_main_link'] = "#ddd";
        $custom_dropdown_options['dd_color_main_link_hovered'] = "#fff";
        $custom_dropdown_options['dd_color_main_bg_hovered'] = "";
        $custom_dropdown_options['dd_color_main_link_hovered_hassubmenu'] = "#555";
        $custom_dropdown_options['dd_color_main_bg_hovered_hassubmenu'] = "#fff";
        $custom_dropdown_options['dd_color_main_link_active'] = "#fff";
        $custom_dropdown_options['dd_color_main_bg_active'] = "";
        $custom_dropdown_options['dd_color_submenu_link'] = "#777";
        $custom_dropdown_options['dd_color_submenu_bg'] = "#fff";
        $custom_dropdown_options['dd_color_submenu_link_hovered'] = "#111";
        $custom_dropdown_options['dd_color_submenu_bg_hovered'] = "#eee";
        $custom_dropdown_options['dd_color_submenu_link_active'] = "#fff";
        $custom_dropdown_options['dd_color_submenu_bg_active'] = "#666";

        update_option('custom-dropdown-menus', $custom_dropdown_options);

        echo "<div class=\"updated\">Colors have been successfully reset</div>";
    }
    ?>
<!-- =============================================================
	FORM FOR SETTINGS PAGE
  ============================================================= -->
<div class="wrap" id="menu_dd__options-settings-form">
<h2 id="menu_dd__options-main-title">Custom Dropdown Menu Settings</h2>

<form name="menu_dd__the_form" action="themes.php?page=menu_dd" method="post">
    <?php

    if ( function_exists( 'wp_nonce_field' ) )
	    wp_nonce_field( 'custom-dropdown-update_settings' );

    //LIST ALL REGISTERED NAV MENUS IN THEME
	$registered_nav_menus = get_registered_nav_menus();
  echo '<div class="choose-widgetize-menu">';
  echo '<strong>Choose Menu to add widgetize dropdowns to:</strong>&nbsp;&nbsp;';
	echo '<select name="select_reg_menu" id="select_reg_menu">';
	echo '<option></option>';
  if ($registered_nav_menus!=''){
  	foreach ( $registered_nav_menus as $location => $description ) {
  		echo '<option value="'.$location.'"';
          if (isset($custom_dropdown_options['select_reg_menu'])){
           if ($custom_dropdown_options['select_reg_menu'] == $location) {
              	echo 'selected';
          	}
          }
          	echo '>'. $description . ' (MENU NAME= '.$location.')' .'</option>';
  	}
  }
	echo '</select>';

  echo '</div>';?>
     <hr/>
    <div class="dd_alert-container"></div>

    <div class="dd_registered_nav_menu-container">

<?php
    //LIST ALL MAIN MENU ITEMS IN REGISTERED MENU SELECTED
    $select_reg_menu=$custom_dropdown_options['select_reg_menu'];
    $select_locations = get_nav_menu_locations();
    $select_menu = wp_get_nav_menu_object( $select_locations[ $select_reg_menu ] );
    $select_menuitems = wp_get_nav_menu_items( $select_menu->term_id, array( 'order' => 'DESC' ) );
    $mainMenuCount=0;
    if ($select_menuitems!="")://if menu is selected and contains menu items
      foreach( $select_menuitems as $select_item ):
        // item does not have a parent so menu_item_parent equals 0 (false)
        if ( !$select_item->menu_item_parent ):
            $select_id = get_post_meta( $select_item->ID, '_menu_item_object_id', true );
            $select_page = get_page( $select_id );
			      $select_title = $select_item->title;?>
			      <div class="registered_nav_menu_item">
          	<!--//////////////////////////////////////////////////////-->
          	<!--Title of menu item-->
            <!--//////////////////////////////////////////////////////-->
            <div class="registered_nav_menu_header">
              <div class="registered_nav_menu_title"><?php echo $select_title;?></div>
              <!--//////////////////////////////////////////////////////-->
              <!--Checkbox option to add custom dropdown to menu item-->
              <!--//////////////////////////////////////////////////////-->
              <div class="registered_nav_menu_checkbox">
  				      <input id="<?php echo 'CustomMenuChkbox'.$select_id?>" type="checkbox" <?php
      			     if ($custom_dropdown_options['menu_dd_addMenu_'.$select_id]=='on') {
          		    echo "checked";$custom_dropdown_options['menu_dd_addMenu_'.$select_id]='on';
      			     } ?> name="<?php echo 'menu_dd_addMenu_'.$select_id;?>" /> Widgetize Menu?
              </div>
            </div>
             <script>
             //SHOW RADIO BUTTONS WHEN CHECK "ADD CUSTOM MENU"
              document.getElementById("<?php echo 'CustomMenuChkbox'.$select_id?>").onclick = function () {
                document.getElementById("<?php echo 'numCols-radiobtns'.$select_id?>").style.display = (this.checked) ? 'block' : 'none';
              };
            </script>
            <!--//////////////////////////////////////////////////////-->
            <!--Choose number of columns in menu's custom dropdown-->
            <!--//////////////////////////////////////////////////////-->
            <div id="<?php echo 'numCols-radiobtns'.$select_id?>" class="dd_column-container"
            <?php if ($custom_dropdown_options['menu_dd_addMenu_'.$select_id]!='on'){echo 'style="display:none;"';}?>>
                <em>Number of Columns in Dropdown:</em><br/>
                <!--Radio - Column 1-->
                <div class="dd_column-option">
                  <div class="dd_column1-graphic dd_column-graphic">
                    <div class="dd_column" style="width:97.5px"></div>
                  </div>
                  <input type="radio" name="<?php echo 'numWidgetsMenu_'.$select_id;?>" value="1"
                  <?php if ($custom_dropdown_options['numWidgetsMenu_'.$select_id] == "1" || $custom_dropdown_options['numWidgetsMenu_'.$select_id] == "") {
                      echo "checked";}?>><br>1
                </div>

                <!--Radio - Column 2-->
                <div class="dd_column-option">
                  <div class="dd_column1-graphic dd_column-graphic">
                    <div class="dd_column" style="width:47.5px"></div>
                    <div class="dd_column" style="width:47.5px"></div>
                  </div>
                  <input type="radio" name="<?php echo 'numWidgetsMenu_'.$select_id;?>" value="2"
                  <?php if ($custom_dropdown_options['numWidgetsMenu_'.$select_id] == "2") {
                      echo "checked";}?>><br>2
                </div>

                <!--Radio - Column 3-->
                <div class="dd_column-option">
                  <div class="dd_column-graphic">
                    <div class="dd_column" style="width:31px"></div>
                    <div class="dd_column" style="width:31px"></div>
                    <div class="dd_column" style="width:31px"></div>
                  </div>
                  <input type="radio" name="<?php echo 'numWidgetsMenu_'.$select_id;?>" value="3"
                  <?php if ($custom_dropdown_options['numWidgetsMenu_'.$select_id] == "3") {
                      echo "checked";}?>><br>3
                </div>

                <!--Radio - Column 4-->
                <div class="dd_column-option">
                  <div class="dd_column1-graphic dd_column-graphic">
                    <div class="dd_column" style="width:23px"></div>
                    <div class="dd_column" style="width:23px"></div>
                    <div class="dd_column" style="width:23px"></div>
                    <div class="dd_column" style="width:23px"></div>
                  </div>
                  <input type="radio" name="<?php echo 'numWidgetsMenu_'.$select_id;?>" value="4"
                  <?php if ($custom_dropdown_options['numWidgetsMenu_'.$select_id] == "4") {
                      echo "checked";}?>><br>4
                </div>
            </div><!--end column options container-->
			</div>
    	<?php endif;
		$mainMenuCount++;
	endforeach;?>
</div><!--end nav container-->
<!--AUTO ACTIVATE DISPLAY WIDGETIZED MENU-->
  <div class="dd_activate-container">
    <label for="dd_activate_wdgtnav"><?php _e('<strong>Enable Menu:</strong>&nbsp;&nbsp;'); ?></label>
    <input id="dd_activate_wdgtnav" type="checkbox" <?php
     if ($custom_dropdown_options['dd_activate_wdgtnav']=='on') {
      echo "checked";$custom_dropdown_options['dd_activate_wdgtnav']='on';
     } ?> name="dd_activate_wdgtnav" />
  </div>
    <!--MANUAL REPLACEMENT-->
    <div id="dd_instructions-option-container" class="dd_option-container">

      <div class="dd_option-header"><span style="float:left">Manual Instructions:&nbsp;&nbsp;</span><span class="dd_option-arrow"></span></div>
      <div class="dd_option-body">
        <?php $locations = get_registered_nav_menus();
        $select_reg_menu=$custom_dropdown_options['select_reg_menu'];
          $menu_name='';
          $menu_slug='';
          if ($locations!=''){
          foreach ( $locations as $menu_location => $menu_description ) {
                   if ($select_reg_menu == $menu_location) {
                        $menu_name=$menu_description;
                        $menu_slug=$menu_location;
                  }
            }
          }?>
        <em>To manually replace your original navigation menu with this one</em>
        <p><strong>Find and replace this function:</strong><br/>
          wp_nav_menu(array( ... 'theme_location'  => '<?php echo $menu_slug;?>' ) );</p>
        <p><strong>With:</strong><br/>
         if (function_exists('widgetize_my_dropdown_menus')) {<br/>
           widgetize_my_dropdown_menus('<?php echo $menu_slug;?>');<br/>
         }
         </p>
         <hr/>
        <p>
          <em>Typically the function is located in your theme's header.php</em>
        </p>
      </div>
        <hr class="dd_hr"/>
    </div>

  <!--///////////////////////////ALL STYLE OPTIONS FOR PLUGIN/////////////////////////-->

  <div class="dd_allstyleoptions-container">
    <h3>Style Options</h3>
  <!--WIDTH OPTIONS-->
  <div id="dd_width-option-container" class="dd_option-container">
    Width of Widgetized Menus:&nbsp;&nbsp;
    <input type="radio" name="widthWidgetsMenu" value="navbar-width"
    <?php if ($custom_dropdown_options['widthWidgetsMenu'] == "navbar-width" || $custom_dropdown_options['widthWidgetsMenu'] == "") {
        echo "checked";}?>> Navbar's width
        <input type="radio" name="widthWidgetsMenu" value="full-width"
    <?php if ($custom_dropdown_options['widthWidgetsMenu'] == "full-width") {
        echo "checked";}?>> Full width
  </div>
  <hr class="dd_hr"/>
  <!--SEARCH OPTION-->
   <div id="dd_search-option-container">
    Show search bar?&nbsp;&nbsp;
      <input id="searchOptionMenu" type="checkbox" <?php
       if ($custom_dropdown_options['searchOptionMenu']=='on') {
        echo "checked";$custom_dropdown_options['searchOptionMenu']='on';
       } ?> name="searchOptionMenu" />
    </div>
  <hr class="dd_hr"/>
  <!--CONTACT OPTION-->
  <div id="dd_contact-option-container">
    <label for="contactOptionMenu"><?php _e('Contact link:&nbsp;&nbsp;'); ?></label>
    <input class="widefat" id="contactOptionMenu" name="contactOptionMenu" type="text" value="<?php echo $custom_dropdown_options['contactOptionMenu']; ?>" />
  </div>
  <hr class="dd_hr"/>
  <!--FACEBOOK OPTION-->
   <div id="dd_facebook-option-container">
    <label for="facebookOptionMenu"><?php _e('Facebook link:&nbsp;&nbsp;'); ?></label>
    <input class="widefat" id="facebookOptionMenu" name="facebookOptionMenu" type="text" value="<?php echo $custom_dropdown_options['facebookOptionMenu']; ?>" />
  </div>
  <hr class="dd_hr"/>
  <!--TWITTER OPTION-->
   <div id="dd_twitter-option-container">
    <label for="twitterOptionMenu"><?php _e('Twitter link:&nbsp;&nbsp;'); ?></label>
    <input class="widefat" id="twitterOptionMenu" name="twitterOptionMenu" type="text" value="<?php echo $custom_dropdown_options['twitterOptionMenu']; ?>" />
  </div>
  <hr class="dd_hr"/>
  <!--COLOR OPTIONS-->
  <div id="dd_color-option-container" class="dd_option-container">
    <div class="dd_option-header"><span style="float:left">Colors:&nbsp;&nbsp;</span><span class="dd_option-arrow"></span></div>
    <div class="dd_option-body">
        <!--IMPORT ORIGINAL STYLES-->
        <div id="dd_import-nav-colors_container">
          <input id="dd_import-nav-colors" type="checkbox" <?php
           if ($custom_dropdown_options['dd_import-nav-colors']=='on') {
            echo "checked";$custom_dropdown_options['dd_import-nav-colors']='on';
           } ?> name="dd_import-nav-colors" /> Attempt to import original navigation's colors
        </div>
        <script>
        //SHOW RADIO BUTTONS WHEN CHECK "ADD CUSTOM MENU"
              document.getElementById("dd_import-nav-colors").onclick = function () {
              document.getElementById("dd_option-allcolors").style.display = (this.checked) ? 'none' : 'block';
          };
        </script>
      <div id="dd_option-allcolors" <?php if ($custom_dropdown_options['dd_import-nav-colors']=='on') {
        echo 'style="display:none;"';}?>>
        <!--NAVBAR COLOR-->
        <div>
          <label for="<?php echo 'dd_color_navbar'; ?>"><?php _e('Navbar <br><em></em>'); ?></label><br/>
          <input class="my-color-field" id="<?php echo 'dd_color_navbar'; ?>" name="dd_color_navbar" type="text"
          value="<?php if (empty($custom_dropdown_options['dd_color_navbar'])){echo '#555';} else{ echo $custom_dropdown_options['dd_color_navbar'];}?>" />

        </div>
        <!--MAIN MENU LINK COLOR-->
        <div>
          <label for="<?php echo 'dd_color_main_link'; ?>"><?php _e('Main Menu Link <br><em></em>'); ?></label><br/>
          <input class="my-color-field" id="<?php echo 'dd_color_main_link'; ?>" name="dd_color_main_link" type="text"
          value="<?php if (empty($custom_dropdown_options['dd_color_main_link'])){echo '#ddd';} else{ echo $custom_dropdown_options['dd_color_main_link'];}?>" />
        </div>
        <!--MAIN MENU LINK HOVERED COLOR (NO SUBMENU)-->
        <div>
          <label for="<?php echo 'dd_color_main_link_hovered'; ?>"><?php _e('Main Menu Link <br><em>hover & no submenu</em>'); ?></label><br/>
          <input class="my-color-field" id="<?php echo 'dd_color_main_link_hovered'; ?>" name="dd_color_main_link_hovered" type="text"
          value="<?php if (empty($custom_dropdown_options['dd_color_main_link_hovered'])){echo '#fff;';} else{ echo $custom_dropdown_options['dd_color_main_link_hovered'];}?>" />
        </div>
        <!--MAIN MENU BACKGROUND HOVERED COLOR (NO SUBMENU) (no default)-->
        <div>
          <label for="<?php echo 'dd_color_main_bg_hovered'; ?>"><?php _e('Main Menu Bg <br><em>hover & no submenu</em>'); ?></label><br/>
          <input class="my-color-field" id="<?php echo 'dd_color_main_bg_hovered'; ?>" name="dd_color_main_bg_hovered" type="text"
          value="<?php echo $custom_dropdown_options['dd_color_main_bg_hovered'];?>" />
        </div>
            <!--MAIN MENU LINK HOVERED COLOR (HAS SUBMENU)-->
        <div>
          <label for="<?php echo 'dd_color_main_link_hovered_hassubmenu'; ?>"><?php _e('Main Menu Link <br><em>hover & has submenu</em>'); ?></label><br/>
          <input class="my-color-field" id="<?php echo 'dd_color_main_link_hovered_hassubmenu'; ?>" name="dd_color_main_link_hovered_hassubmenu" type="text"
          value="<?php if (empty($custom_dropdown_options['dd_color_main_link_hovered_hassubmenu'])){echo '#555';} else{echo $custom_dropdown_options['dd_color_main_link_hovered_hassubmenu'];}?>" />
        </div>
        <!--MAIN MENU BACKGROUND HOVERED COLOR (HAS SUBMENU)-->
        <div>
          <label for="<?php echo 'dd_color_main_bg_hovered_hassubmenu'; ?>"><?php _e('Main Menu Bg <br><em>hover & has submenu</em>'); ?></label><br/>
          <input class="my-color-field" id="<?php echo 'dd_color_main_bg_hovered_hassubmenu'; ?>" name="dd_color_main_bg_hovered_hassubmenu" type="text"
          value="<?php if (empty($custom_dropdown_options['dd_color_main_bg_hovered_hassubmenu'])){echo '#fff';} else{echo $custom_dropdown_options['dd_color_main_bg_hovered_hassubmenu'];}?>" />
        </div>
         <!--MAIN MENU LINK ACTIVE COLOR-->
        <div>
          <label for="<?php echo 'dd_color_main_link_active'; ?>"><?php _e('Main Menu Link <br><em>active</em>'); ?></label><br/>
          <input class="my-color-field" id="<?php echo 'dd_color_main_link_active'; ?>" name="dd_color_main_link_active" type="text"
          value="<?php if (empty($custom_dropdown_options['dd_color_main_link_active'])){echo '#fff';} else{ echo $custom_dropdown_options['dd_color_main_link_active'];}?>" />
        </div>
        <!--MAIN MENU BACKGROUND ACTIVE COLOR (no default)-->
        <div>
          <label for="<?php echo 'dd_color_main_bg_active'; ?>"><?php _e('Main Menu Bg <br><em>active</em>'); ?></label><br/>
          <input class="my-color-field" id="<?php echo 'dd_color_main_bg_active'; ?>" name="dd_color_main_bg_active" type="text"
          value="<?php echo $custom_dropdown_options['dd_color_main_bg_active'];?>" />
        </div>
        <!--SUB MENU LINK COLOR-->
        <div>
          <label for="<?php echo 'dd_color_submenu_link'; ?>"><?php _e('Sub Menu Link <br/><em></em>'); ?></label><br/>
          <input class="my-color-field" id="<?php echo 'dd_color_submenu_link'; ?>" name="dd_color_submenu_link" type="text"
          value="<?php if (empty($custom_dropdown_options['dd_color_submenu_link'])){echo '#777';} else{echo $custom_dropdown_options['dd_color_submenu_link'];}?>" />
        </div>
        <!--SUB MENU BACKGROUND COLOR-->
        <div>
          <label for="<?php echo 'dd_color_submenu_bg'; ?>"><?php _e('Sub Menu Bg <br/><em></em>'); ?></label><br/>
          <input class="my-color-field" id="<?php echo 'dd_color_submenu_bg'; ?>" name="dd_color_submenu_bg" type="text"
          value="<?php if (empty($custom_dropdown_options['dd_color_submenu_bg'])){echo '#fff';} else{echo $custom_dropdown_options['dd_color_submenu_bg'];}?>" />
        </div>
          <!--SUB MENU LINK HOVERED COLOR-->
        <div>
          <label for="<?php echo 'dd_color_submenu_link_hovered'; ?>"><?php _e('Sub Menu Link <br><em>hover</em>'); ?></label><br/>
          <input class="my-color-field" id="<?php echo 'dd_color_submenu_link_hovered'; ?>" name="dd_color_submenu_link_hovered" type="text"
          value="<?php if (empty($custom_dropdown_options['dd_color_submenu_link_hovered'])){echo '#111';} else{echo $custom_dropdown_options['dd_color_submenu_link_hovered'];}?>" />
        </div>
        <!--SUB MENU BACKGROUND HOVERED COLOR-->
        <div>
          <label for="<?php echo 'dd_color_submenu_bg_hovered'; ?>"><?php _e('Sub Menu Bg <br><em>hover</em>'); ?></label><br/>
          <input class="my-color-field" id="<?php echo 'dd_color_submenu_bg_hovered'; ?>" name="dd_color_submenu_bg_hovered" type="text"
          value="<?php if (empty($custom_dropdown_options['dd_color_submenu_bg_hovered'])){echo '#eee';} else{echo $custom_dropdown_options['dd_color_submenu_bg_hovered'];}?>" />
        </div>
         <!--SUB MENU LINK ACTIVE COLOR-->
        <div>
          <label for="<?php echo 'dd_color_submenu_link_active'; ?>"><?php _e('Sub Menu Link <br><em>active</em>'); ?></label><br/>
          <input class="my-color-field" id="<?php echo 'dd_color_submenu_link_active'; ?>" name="dd_color_submenu_link_active" type="text"
          value="<?php if (empty($custom_dropdown_options['dd_color_submenu_link_active'])){echo '#fff';} else {echo $custom_dropdown_options['dd_color_submenu_link_active'];}?>" />
        </div>
        <!--SUB MENU BACKGROUND ACTIVE COLOR-->
        <div>
          <label for="<?php echo 'dd_color_submenu_bg_active'; ?>"><?php _e('Sub Menu Bg <br><em>active</em>'); ?></label><br/>
          <input class="my-color-field" id="<?php echo 'dd_color_submenu_bg_active'; ?>" name="dd_color_submenu_bg_active" type="text"
          value="<?php if (empty($custom_dropdown_options['dd_color_submenu_bg_active'])){echo '#666';} else{echo $custom_dropdown_options['dd_color_submenu_bg_active'];}?>" />
        </div>
      </div><!--end all colors-->
    </div>
  </div>
  <hr class="dd_hr"/>
  <!--CUSTOM CSS-->
  <div id="dd_customcss-option-container" class="dd_option-container">
    <div class="dd_option-header"><span style="float:left">Custom CSS:&nbsp;&nbsp;</span><span class="dd_option-arrow"></span></div>
    <div class="dd_option-body">
        <br/>
        <textarea id="<?php echo 'dd_customcss-option'; ?>" name="dd_customcss-option"><?php echo $custom_dropdown_options['dd_customcss-option'];?></textarea>
    </div>
  </div>
  <hr class="dd_hr"/>
</div>
  <!--///////////////////////////END STYLE OPTIONS/////////////////////////-->
  <?php elseif($select_reg_menu!="")://if menu selected contains no menu items
  $locations = get_registered_nav_menus();
  $menu_name='';
  $menu_slug='';
  if ($locations!=''){
  foreach ( $locations as $menu_location => $menu_description ) {
           if ($select_reg_menu == $menu_location) {
                $menu_name=$menu_description;
                $menu_slug=$menu_location;
          }
    }
  }
  echo '<div class="dd_alert-container">Please add menu items to '.$menu_name . ' (MENU NAME= '.$menu_slug.')</div>';

  endif;
		?>
      </div><!--end menu_dd__options-settings-instructions-->

    <p class="submit">
    <input type="hidden" name="save_changes" value="1" />
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>

</form>
<form method="post" action="" class="dd_resetcolors-form">
 <p class="submit">
 <input name="reset" class="button button-secondary" type="submit" value="Reset colors">
 <input type="hidden" name="action" value="reset"  />
 </p>
</form>

</div>

<?php
}
// END WRITTEN SETTINGS PAGE/////////////////////////////////
