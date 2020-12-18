<?php

class OEmbed extends PluginAbstract
{
	/**
	 * @var string Name of plugin
	 */
	public $name = 'OEmbed';

	/**
	 * @var string Description of plugin
	 */
	public $description = 'Allow embed links to be auto-embedded in services such as WordPress, etc.';

	/**
	 * @var string Name of plugin author
	 */
	public $author = 'Justin Henry';

	/**
	 * @var string URL to plugin's website
	 */
	public $url = 'https://uvm.edu/~jhenry/';

	/**
	 * @var string Current version of plugin
	 */
	public $version = '0.1.0';

	/**
	 * Performs install operations for plugin. Called when user clicks install
	 * plugin in admin panel.
	 *
	 */
	public function install()
	{
	}

	/**
	 * Performs uninstall operations for plugin. Called when user clicks
	 * uninstall plugin in admin panel and prior to files being removed.
	 *
	 */
	public function uninstall()
	{
	}

	/**
	 * Attaches plugin methods to hooks in code base
	 */
	public function load()
	{
		Plugin::attachFilter ( 'router.static_routes' , array( __CLASS__ , 'addOEmbedRoute'));
	}
	  /**
   * Add route for oembed  
   * 
   */
  public static function addOEmbedRoute($routes)
  {
    $routes['apiVideoOEmbed'] = new Route(array(
          'path' => 'api/video/oEmbed',
          'location' => DOC_ROOT . '/cc-content/plugins/OEmbed/video.OEmbed.php',
          'name' => 'video-oembed'
          ));
    return $routes;
  }
}
