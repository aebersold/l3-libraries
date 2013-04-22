<?php 

class URL extends \Laravel\URL {

	/**
	 * Generate an URL to an amazon S3 file.
	 * Requires a valid URL in the application config file
	 * use s3bucket_http, s3bucket_https 
	 *
	 * @param  string  $url
	 * @param  bool    $https
	 * @return string
	 */

	public static function to_s3($url, $https = false)
	{
		if (static::valid($url) or static::valid('http:'.$url)) return $url;

		$bucket = ($https == true ? Config::get('application.s3bucket_https') : Config::get('application.s3bucket_http'));

		return $bucket.'/'.$url;
	}

}
