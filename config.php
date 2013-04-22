<?php

class Config extends \Laravel\Config {

	/**
	 * base code by matteo patisso, enhanced by simon aebersold
	 *
	 * write/edit a laravel config file
	 * the config files needs write access!
	 *
	 * @param  string  $file
	 * @param  string  $key
	 * @param  mixed   $val
	 * @return bool
	 */

	public static function write($file, $key, $val)
	{
		Config::set($file . '.' . $key, $val);
		$config = var_export(self::get(), true);
		
		if (file_put_contents('application/config/'.$file.'.php', "<?php return $config;") > 0)
		{
			return true;
		}
		else
		{
			return false;
		}
	}

}
