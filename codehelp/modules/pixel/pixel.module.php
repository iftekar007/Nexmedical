<?php

require_once ai_cascadepath('includes/plugins/modules/class.module_base.php');
require_once ai_cascadepath(dirname(__FILE__) . '/includes/class.te_share_links.php');

/**
 * share_links module
 */
class share_links_module extends module_base
{
    public $mod_system_name = 'share_links'; // Not static because parent needs to access this
    public $mod_name = 'Share Links';
    public $mod_description = 'Share Links (My Links / My URLs) Manager';
    public $mod_version = '2.1';
    public $mod_ignore_lock_at_or_before_version = '0.0';

    /**
     * Called when module is loaded AND is initiated
     *
     * @param $settings Array of settings, unrealized from the database
     */
    public function mod_load_settings( $settings )
    {
        $this->enable_landing_page_manager = (isset($settings['enable_landing_page_manager']) ? $settings['enable_landing_page_manager'] : '');
    }

    /**
     * mod_upgrade()
     *
     * Run any version upgrades.  Only triggered when db version # is out of date when compared to static version # within the module.
     */
    public function mod_upgrade( $db_version )
    {
        global $AI;

        if ( $this->mod_is_older_version($db_version, '.1') )
        {
            db_query("
				CREATE TABLE `share_links` (
				  `id` int(11) NOT NULL AUTO_INCREMENT,
				  `name` varchar(40) NOT NULL DEFAULT '',
				  `description` text NOT NULL,
				  `url` varchar(255) NOT NULL DEFAULT '',
				  `img_url` varchar(255) NOT NULL DEFAULT '',
				  `is_pixel` int(11) NOT NULL DEFAULT '0',
				  PRIMARY KEY (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=latin1
			;");

			//CREATE PAGE(s)
			$AI->skin->create_dynamicpage(
				'share_links' //$pagename
				, array('body' => 'includes/modules/share_links/share_links.php') //$content
				, array('body' => 'file') //$types
				, 'default' //$skinname = 'default'
				, 'N' //$requires_ssl = 'N'
				, 'en' //$lang = ''
				);

			//ADD PERMISSIONS
			$perm_classes = array('share_links');
			$perm_groups = array('Website Developers', 'Administrators');
			$perm_types = array('ajax','ajax_cmd_inline_edit','ajax_cmd_inline_save','asearch','copy','delete','insert','multidelete','table','update','view');
			$AI->grant_multiple_perms( $perm_classes, $perm_groups, $perm_types, false );
			$perm_groups = array('Users');
			$perm_types = array('ajax','table');
			$AI->grant_multiple_perms( $perm_classes, $perm_groups, $perm_types, false );
			$AI->grant_page_perm( 'share_links', array('Website Developers','Administrators','Users','Anonymous') );

			// SETUP DYNAMIC AREAS
			$sql = "
				SELECT id
				FROM dynamic_areas
				WHERE name = 'my-urls-header'
				LIMIT 1
			;";
			$existing_id = (int) db_lookup_scalar($sql);
			if ( $existing_id < 1 )
			{
				$sql_now = date('Y-m-d H:i:s');
				$sql = "
					INSERT INTO dynamic_areas
					SET name = 'my-urls-header'
					, content = '<h2>Share Links</h2><p style=\"text-align:center\"><strong>The links below are to your personal website and landing pages.  Note that each of the website addresses do have a unique indentifier that ensures you are given credit for all activity related to your links.  You will want to drive your traffic to these links.</strong></p>'
					, lang = 'en'
					, created_on = '" . db_in($sql_now) . "'
					, saved_on = '" . db_in($sql_now) . "'
				;";
				db_query($sql);
			}

			$this->mod_set_db_version('.1');
		}

		if ( $this->mod_is_older_version($db_version, '.2') )
		{
			db_query("ALTER TABLE share_links ADD COLUMN requires_success_line BOOL NOT NULL DEFAULT 0;");
			$this->mod_set_db_version('.2');
		}

		if ( $this->mod_is_older_version($db_version, '.3') )
		{
			db_query("ALTER TABLE share_links ADD COLUMN postal_parrot_var_name VARCHAR(255) NOT NULL DEFAULT '';");
			$this->mod_set_db_version('.3');
		}

		if ( $this->mod_is_older_version($db_version, '.4') )
		{
			//ADD SORTING AND IS_PUBLIC FIELDS
			db_query("ALTER TABLE share_links ADD COLUMN `sort_order` decimal(10,2) NOT NULL;");
			db_query("ALTER TABLE share_links ADD COLUMN `is_public` tinyint(1) NOT NULL default '1';");

			//GRANT SORTING PERMS
			$AI->grant_multiple_perms( 'share_links', array('Website Developers', 'Administrators'), array('ajax_cmd_update_sort_index'), false );

			$this->mod_set_db_version('.4');
		}

		if ( $this->mod_is_older_version($db_version, '.5') )
		{
			db_query("ALTER TABLE `share_links` ADD `template_id` INT(11) NOT NULL DEFAULT '0', ADD INDEX (`template_id`);");

			$this->mod_set_db_version('.5');
		}

		if ( $this->mod_is_older_version($db_version, '.6') )
		{
			db_query("ALTER TABLE `share_links` ADD `drip_id` INT(11) NOT NULL DEFAULT '0';");
			db_query("ALTER TABLE `share_links` ADD `owner_id` INT(11) NOT NULL DEFAULT '0', ADD INDEX (`owner_id`);");

			db_query("CREATE TABLE IF NOT EXISTS `landing_page_templates` (
			 `id` int(11) NOT NULL auto_increment,
			 `folder` varchar(255) NOT NULL,
			 `thumbnail` varchar(255) NOT NULL,
			 PRIMARY KEY  (`id`)
			) ENGINE=MyISAM DEFAULT CHARSET=latin1");

			// Initial Landing Page Templates
			db_query("INSERT INTO `landing_page_templates` (`id`, `folder`, `thumbnail`) VALUES
			(1, 'system/lp/4', ''),
			(2, 'system/lp/8', ''),
			(3, 'system/lp/10', '');");

			// Screenshot Page
			$AI->skin->create_dynamicpage(
				'screenshot' //$pagename
				, array('body' => 'includes/plugins/landing_pages/screenshot.php') //$content
				, array('body' => 'file') //$types
				, 'full_page' //$skinname = 'default'
				, 'Either' //$requires_ssl = 'N'
				, 'en' //$lang = ''
				);

			//ADD PERMISSIONS
			$AI->grant_page_perm( 'screenshot', array('Website Developers','Administrators','Users','Anonymous') );

			// Screenshot Page
			$AI->skin->create_dynamicpage(
				'l' //$pagename
				, array('body' => 'includes/plugins/landing_pages/controller.php') //$content
				, array('body' => 'file') //$types
				, 'full_page_buffered' //$skinname = 'default'
				, 'Either' //$requires_ssl = 'N'
				, 'en' //$lang = ''
				, 'l/*' // the url
				);

			//ADD PERMISSIONS
			$AI->grant_page_perm( 'l', array('Website Developers','Administrators','Users','Anonymous') );

			$this->mod_set_db_version('.6');
		}

		if ( $this->mod_is_older_version($db_version, '.7') )
		{
			// Needed to allow users editable controls. Controls are restricted programmically
			$perm_classes = array('share_links');
			$perm_groups = array('Users');
			$perm_types = array('delete','insert','update');
			$AI->grant_multiple_perms( $perm_classes, $perm_groups, $perm_types, false );

			$this->mod_set_db_version('.7');
		}

		if ( $this->mod_is_older_version($db_version, '.8') )
		{
			$AI->grant_multiple_perms(array('share_links'), array('Website Developers', 'Administrators', 'Users'), array('ajax_cmd_check_url'), false);

			$this->mod_set_db_version('.8');
		}

		if ( $this->mod_is_older_version($db_version, '.9') )
		{
			db_query("ALTER TABLE `share_links` ADD `domain_id` INT( 11 ) NOT NULL DEFAULT '0' AFTER `url`, ADD INDEX (`domain_id`);");
			db_query("ALTER TABLE `share_links` ADD `sub_domain_id` INT( 11 ) NOT NULL DEFAULT '0' AFTER `domain_id`, ADD INDEX (`sub_domain_id`);");

			$this->mod_set_db_version('.9');
		}

		if ( $this->mod_is_older_version($db_version, '1.0') )
		{
			db_query("INSERT INTO `landing_page_templates` (`id`, `folder`, `thumbnail`) VALUES (NULL, 'system/lp/dynamic_1', '');");
			db_query("DELETE FROM `landing_page_templates` WHERE folder = 'system/lp/4';");
			db_query("DELETE FROM `landing_page_templates` WHERE folder = 'system/lp/8';");
			db_query("DELETE FROM `landing_page_templates` WHERE folder = 'system/lp/10';");

			$this->mod_set_db_version('1.0');
		}

		if ( $this->mod_is_older_version($db_version, '1.1') )
		{
			db_query("ALTER TABLE `landing_page_templates` CHANGE `thumbnail` `show_hide` ENUM('show','hide') CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT 'show';");
			db_query("ALTER TABLE `landing_page_templates` ADD `sort_order` INT(11) NOT NULL DEFAULT '0', ADD INDEX (`sort_order`);");

			db_query("TRUNCATE TABLE `landing_page_templates`;");
			db_query("INSERT INTO `landing_page_templates` (`id`, `folder`, `show_hide`, `sort_order`) VALUES (NULL, 'system/lp/mebox_1', 'hide', '0');");
			db_query("INSERT INTO `landing_page_templates` (`id`, `folder`, `show_hide`, `sort_order`) VALUES (NULL, 'system/lp/dynamic_1', 'show', '5');");
			db_query("INSERT INTO `landing_page_templates` (`id`, `folder`, `show_hide`, `sort_order`) VALUES (NULL, 'system/lp/landing_page_22', 'show', '10');");

			db_query("UPDATE share_links SET template_id = 0, img_url = '' WHERE template_id = 1;");
			db_query("UPDATE share_links SET template_id = 0, img_url = '' WHERE template_id = 2;");
			db_query("UPDATE share_links SET template_id = 0, img_url = '' WHERE template_id = 3;");
			db_query("UPDATE share_links SET template_id = 1, img_url = 'template:1' WHERE template_id = 4;");
			db_query("UPDATE share_links SET template_id = 2, img_url = 'template:2' WHERE template_id = 5;");

			$this->mod_set_db_version('1.1');
		}

		if ( $this->mod_is_older_version($db_version, '1.2') )
		{
			db_query("INSERT INTO `landing_page_templates` (`id`, `folder`, `show_hide`, `sort_order`) VALUES (NULL, 'system/lp/ai', 'show', '10');");

			$this->mod_set_db_version('1.2');
		}

		if ( $this->mod_is_older_version($db_version, '1.4') )
		{

			$AI->grant_multiple_perms(array('share_links'), array('Website Developers', 'Administrators'), array('insert_share_link'), false);

			$this->mod_set_db_version('1.4');
		}


		if($this->mod_is_older_version($db_version, '1.5'))
		{

			$perm_classes = array('share_links');
			$perm_groups = array('Website Developers', 'Administrators');
			$perm_types = array('administrate');
			$AI->grant_multiple_perms( $perm_classes, $perm_groups, $perm_types, false );
			$this->mod_set_db_version('1.5');

			db_query("ALTER TABLE share_links ADD COLUMN file_name VARCHAR(150) NOT NULL DEFAULT '' ;");
		}

		if($this->mod_is_older_version($db_version, '1.6'))
		{
			//CREATE PAGE(s)
			$AI->skin->create_dynamicpage(
					'affiliate_links' //$pagename
					, array('body' => 'includes/modules/share_links/affiliate_links.php') //$content
					, array('body' => 'file') //$types
					, 'default' //$skinname = 'default'
					, 'N' //$requires_ssl = 'N'
					, 'en' //$lang = ''
			);

			//ADD PERMISSIONS
			$perm_classes = array('affiliate_links');
			$perm_groups = array('Website Developers', 'Administrators');
			$perm_types = array('ajax','ajax_cmd_inline_edit','ajax_cmd_inline_save','asearch','copy','delete','insert','multidelete','table','update','view');
			$AI->grant_multiple_perms( $perm_classes, $perm_groups, $perm_types, false );
			$perm_groups = array('Users');
			$perm_types = array('ajax','table');
			$AI->grant_multiple_perms( $perm_classes, $perm_groups, $perm_types, false );
			$AI->grant_page_perm( 'affiliate_links', array('Website Developers','Administrators','Users','Anonymous') );


			$AI->grant_multiple_perms( 'affiliate_links', array('Website Developers', 'Administrators'), array('ajax_cmd_update_sort_index'), false );

			$perm_classes = array('affiliate_links');
			$perm_groups = array('Users');
			$perm_types = array('delete','insert','update');
			$AI->grant_multiple_perms( $perm_classes, $perm_groups, $perm_types, false );
			$AI->grant_multiple_perms(array('affiliate_links'), array('Website Developers', 'Administrators', 'Users'), array('ajax_cmd_check_url'), false);
			$AI->grant_multiple_perms(array('affiliate_links'), array('Website Developers', 'Administrators'), array('insert_share_link'), false);
			$perm_classes = array('affiliate_links');
			$perm_groups = array('Website Developers', 'Administrators');
			$perm_types = array('administrate');
			$AI->grant_multiple_perms( $perm_classes, $perm_groups, $perm_types, false );

			db_query("ALTER TABLE share_links ADD COLUMN page_name_source VARCHAR(50) NOT NULL DEFAULT '' ;");

			$current_page_source = db_lookup_scalar("SELECT url FROM ai_dynamicpages AS ai_dynapage
			JOIN ai_skintags AS ai_st ON ai_st.dynamicpage_id = ai_dynapage.pageID
			WHERE ai_st.tag_value='includes/modules/share_links/share_links.php' AND ai_st.tag_type='file' AND ai_st.tag_name='body' LIMIT 1");



			db_query("UPDATE share_links SET page_name_source = '".db_in($current_page_source)."'");

			$this->mod_set_db_version('1.6');
		}


		if($this->mod_is_older_version($db_version, '1.7'))
		{

			$perm_classes = array('affiliate_links');
			$perm_groups = array('Website Developers', 'Administrators');
			$perm_types = array('view');
			$AI->deny_multiple_perms( $perm_classes, $perm_groups, $perm_types, false );

			$perm_classes = array('share_links');
			$perm_groups = array('Website Developers', 'Administrators');
			$perm_types = array('view');
			$AI->deny_multiple_perms( $perm_classes, $perm_groups, $perm_types, false );

			$this->mod_set_db_version('1.7');
		}
		if($this->mod_is_older_version($db_version, '1.8'))
		{
			global $AI;
			$AI->grant_multiple_perms(array('share_links'), array('Website Developers', 'Administrators','Users'), array('landing_page_manager'), false);
			$this->mod_set_db_version('1.8');
		}
		if($this->mod_is_older_version($db_version, '2.1'))
		{
			global $AI;
			$AI->deny_multiple_perms(array('share_links'), array('Users'), array('landing_page_manager'), false);
			$this->mod_set_db_version('2.1');
		}
	}

	/**
	 * Display help documents
	 */
	public function mod_help()
	{
		echo '<p>This module creates a share link database and its respective table edit to manage share links available for the regular user.</p>';
	}

	/**
	 * Draw a form to build settings.
	 * @param $fieldstart The starting string to use for input fields
	 * @return null
	 */
	public function mod_settings( $fieldstart )
	{
			echo '<p>For More Information, click help link above.</p>';
			echo '<label for="enable_landing_page_manager">Enable Landing Page Manager?</label>';
			echo '&nbsp;<input type="radio" name="'.$fieldstart.'enable_landing_page_manager" value="Yes" ';
			if($this->enable_landing_page_manager == "Yes" || $this->enable_landing_page_manager == "") { echo ' checked="checked"'; }
			echo ' id="enable_landing_page_manager"> Yes ';
			echo '&nbsp;';
			echo '&nbsp;<input type="radio" name="'.$fieldstart.'enable_landing_page_manager" value="No" ';
			if($this->enable_landing_page_manager == "No") { echo ' checked="checked"'; }
			echo ' id="enable_landing_page_manager"> No';
	}

	/**
	 * Run though the inputed fields
	 *
	 * @see mod_settings
	 * @param $form_items The values submitted by the form drawn in mod_settings()
	 */
	public function mod_settings_validate( $form_items )
	{
		return true;
	}

	////////////////////////////////////////////////////////////////
	// HOOKS ///////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////

	public function hook_determine_skinname($param_query)
	{
		if($param_query == "share_links" && (@$_GET['te_mode'] == "insert" || @$_GET['te_mode'] == "copy" || @$_GET['te_mode'] == "update")) {

			require_once( ai_cascadepath( 'includes/modules/share_links/includes/class.te_share_links.php' ) );
			$te_share_links = new C_te_share_links();
			$te_share_links->select($te_share_links->te_key);

			if(!empty($te_share_links->db['template_id']) || $te_share_links->te_mode == "insert") {
				$_GET['ai_skin'] = "boxless";
			}
		}
	}

	public function hook_get_share_links( $prospect_lead_id = null, $return_var_names = false )
	{
		$share_links = array();
		$sql = "
			SELECT name, url, requires_success_line, postal_parrot_var_name, template_id, domain_id, sub_domain_id
			FROM share_links
			ORDER BY id ASC
		;";
		$res = db_query($sql);
		if ( $res )
		{
			$numrows = db_num_rows($res);
			if ( 0 < $numrows )
			{
				while ( $res && $row = db_fetch_assoc($res) )
				{
					$name = db_out($row['name']);
					$url = db_out($row['url']);
					
					// Adjust url if necessary
					require_once( ai_cascadepath( 'includes/modules/share_links/includes/class.te_share_links.php' ) );	
					$te_share_links = new C_te_share_links();

					if(empty($row['template_id'])) {
						$url = $te_share_links->interpret_url($url);
					} else {
						$url = $te_share_links->interpret_url_v2($url,$row['domain_id'],$row['sub_domain_id']);
					}
					
					$var_name = db_out($row['postal_parrot_var_name']);
					$requires_success_line = db_out($row['requires_success_line']);

					if ( $requires_success_line == '1' )
					{
						$is_in_success_line = aimod_run_hook_module('success_line', 'hook_is_in_success_line', (int) $prospect_lead_id);
						if ( !$is_in_success_line )
						{
							continue;
						}
					}

					if ( !empty($prospect_lead_id) )
					{
						$url .= '?lid=' . (int) $prospect_lead_id;
					}
					if ( $return_var_names )
					{
						if ( $var_name == '' )
						{
							continue;
						}
						$name = $var_name;
					}
					$share_links[$name] = C_te_share_links::interpret_url($url, $prospect_lead_id);
				}
			}
		}
		return $share_links;
	}

	/**
	 * Perform actions when this module is enabled
	 */
	public function hook_enable_module()
	{
		global $AI;
		////////////////////////////////////////////////////////////////
		// INSTALL MY HOMEPAGE SHARE LINK TO SHARE LINKS TE
		$user_profiles_enabled = (int) util_mod_enabled('user_profiles');
		// Do only if User Profiles is enabled (soft dependency)
		if ( !$user_profiles_enabled )
		{
			return;
		}
		// Install if it does not already exist
		$existing_id = (int) db_lookup_value('share_links', 'name', 'My Home Page', 'id', false);
		if ( $existing_id > 0 )
		{
			return;
		}
		// Determine the site-replication style and construct the URL mask
		$url_mask = defined('AI_DEFAULT_HTTP_URL') // We must get the default url without the parsed subdomain!
		          ? trim(AI_DEFAULT_HTTP_URL)
		          : trim(AI_HTTP_URL)
		          ;
		$ummod = aimod_get_module('user_management');
		$rep_mode = $ummod->site_replication_mode; // 'subdomain' or 'path'
		$url_mask = $rep_mode == 'path'
				? $url_mask . '+[sub_domain]'
		          : preg_replace('/\\bwww\\b/', '[sub_domain]', $url_mask)
				;

		// Install via TE
		require_once ai_cascadepath('includes/modules/share_links/includes/class.te_share_links.php');
		@ob_start(); // TE constructor likes to do output
		$te = new C_te_share_links();
		@ob_end_clean();
		$te->te_mode = 'insert';
		$te->posts = array
			( 'name' => 'My Home Page'
			, 'description' => 'This is your primary home page, which include your personal profile, virtual business card, and contact form.'
			, 'url' => $url_mask
			, 'img_url' => 'includes/modules/share_links/thumb_homepage.png'
			//, 'sort_order' => '-1.00' // TE won't take the value :(
			, 'drip_id' => '0'
			, 'template_id' => '0'
			, 'page_name_source' => 'share_links'
			);
		$te->read_db_post();
		$te->insert();
	}

	/**
	 * Perform actions when this module is disabled
	 */
	public function hook_disable_module() {}
};