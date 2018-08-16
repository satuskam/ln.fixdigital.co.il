<?php
/*
Plugin Name: Hide All Nags
Description: The plugin disables/hides all plugin notifications and inline warnings in your admin panel.
Version: 1.0
Author:  satuskam
*/


class WP_HPUW
{
	const VER="1.0";
	var $options=array();
	var $options_name="WP_HPUW";
	
	public function __construct()
	{
            add_action("in_admin_header",array($this,"skip_notices"),100000);
            add_filter('transient_update_plugins',array($this,'skip_updates'),10000,1);
            add_filter('site_transient_update_plugins',array($this,'skip_updates'),10000,1);
	}
	
	public function __destruct(){}
	
	public function getVersion()
	{
		return self::VER;
	}
	
	public function activate()
	{
		$this->options=get_option($this->options_name);
		if(!is_array($this->options) or empty($this->options))
		{
			$this->default_options();
			$this->store_options();
		}
	}
	
	public function deactivate(){}
	
	private function default_options()
	{
		$defaults=array("ver"=>$this->getVersion(),"notifications"=>"1","updates"=>array());
		
		$this->options=$defaults;
		$this->store_options();
	}

	private function store_options()
	{
		update_option($this->options_name,$this->options);
	}

	public function skip_updates($transientData)
	{
		foreach($this->options["updates"] as $ix=>$plugin_file)
		{
			if(isset($transientData->response[$plugin_file])) 
			{
				unset($transientData->response[$plugin_file]);
			}
		}
		
		return $transientData;
	}
	
	public function skip_notices()
	{
		global $wp_filter;

		if(is_network_admin() and isset($wp_filter["network_admin_notices"]))
		{
			unset($wp_filter['network_admin_notices']); 
		}
		elseif(is_user_admin() and isset($wp_filter["user_admin_notices"]))
		{
			unset($wp_filter['user_admin_notices']); 
		}
		else
		{
			if(isset($wp_filter["admin_notices"]))
			{
				unset($wp_filter['admin_notices']); 
			}
		}
		
		if(isset($wp_filter["all_admin_notices"]))
		{
			unset($wp_filter['all_admin_notices']); 
		}
	}
	
	
}


function nag_load()
{
	if(!isset($GLOBALS["WP_HPUW"]))
	{
		$GLOBALS["WP_HPUW"] = new WP_HPUW();
	}
}

add_action("plugins_loaded",'nag_load',101);

function nag_activate()
{
	$o=new WP_HPUW();
	$o->activate();
}
register_activation_hook(__FILE__, "nag_activate");

function nag_deactivate()
{
	$o=new WP_HPUW();
	$o->deactivate();
}
register_deactivation_hook(__FILE__, "nag_deactivate");

function nag_unistall()
{
	$o=new WP_HPUW();
	if($o->options["nag_search_page_id"]>0)
	{
		wp_delete_post($o->options["nag_search_page_id"], true);
	}
}
register_uninstall_hook(__FILE__, "nag_unistall");

?>