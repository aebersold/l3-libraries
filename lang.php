<?php

class Lang extends \Laravel\Lang {

	/**
	 * inspired by Benjamin Harris
	 * language detection based on URL / session/cookie
	 *
	 * Language detection priority:
	 * 1. use language saved in the session/cookie
	 * 2. use lang in URL, if valid (site.com/en/about)
	 * 3. use language of browser setting, if valid
	 * 4. use default language
	 */

	public static function detect()
	{
		// current uri language ($lang_uri)
		$lang_uri = URI::segment(1);
		$domain = str_replace('www.','',Request::server('server_name'));
		$appdomains = Config::get('application.domains');
		$applanguages = Config::get('application.languages');
		$applanguage = Config::get('application.language');

		// Set default session language if none is set
		if(!Session::has('language'))
		{
			// use lang in uri, if provided
			if(in_array($lang_uri, $applanguages))
			{
				$lang = $lang_uri;	
			}
		    // is it a laguage specific domain?
			elseif(array_key_exists($domain, $appdomains))
			{
				$lang = $appdomains[$domain];
			}
			// detect browser language
		    elseif(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
			{
				$headerlang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

				if(in_array($headerlang, $applanguages))
				{
					// browser lang is supported, use it
					$lang = $headerlang;
				}
				else
				{
					// use default application lang
					$lang = $applanguage;
				}
			}
			// no lang in uri nor in browser. use default
			else 
			{
					// use default application lang
					$lang = $applanguage;			
			}

			// set application language for that user
		    Session::put('language', $lang);
		    Config::set('application.language',  $lang);
		    setlocale(LC_ALL, $lang.'_'.strtoupper($lang));
		}
		else
		{
			// session is availible, set application to session lang
			Config::set('application.language', Session::get('language'));
			setlocale(LC_ALL, Session::get('language').'_'.strtoupper(Session::get('language')));
		}

		// prefix is missing? add it
		if(!in_array($lang_uri, $applanguages)) 
		{
			return Redirect::to(URI::current());
		}
		// a valid prefix is there, but not the correct lang? change app lang
		elseif(in_array($lang_uri, $applanguages) AND $lang_uri != Config::get('application.language'))
		{
		    Session::put('language', $lang_uri);
		    Config::set('application.language',  $lang_uri);
		    setlocale(LC_ALL, $lang_uri.'_'.strtoupper($lang_uri)); 	
		}

		View::share('lang', Config::get('application.language'));

	}

}