<?php

/**
	PHP Fat-Free Framework

	The contents of this file are subject to the terms of the GNU General
	Public License Version 3.0. You may not use this file except in
	compliance with the license. Any of the license terms and conditions
	can be waived if you get permission from the copyright holder.

	Copyright (c) 2009-2011 F3::Factory
	Bong Cosca <bong.cosca@yahoo.com>

		@package Base
		@version 2.0.0
**/

//! Base structure
class Base {

	//@{ Framework details
	const
		TEXT_AppName='Fat-Free Framework',
		TEXT_Version='2.0.0';
	//@}

	//@{ Locale-specific error/exception messages
	const
		TEXT_Illegal='%s is not a valid framework variable name',
		TEXT_Config='The configuration file %s was not found',
		TEXT_Section='%s is not a valid section',
		TEXT_MSet='Invalid multi-variable assignment',
		TEXT_NotArray='%s is not an array',
		TEXT_PHPExt='PHP extension %s is not enabled',
		TEXT_Apache='Apache mod_rewrite module is not enabled',
		TEXT_Object='%s cannot be used in object context',
		TEXT_Class='Undefined class %s',
		TEXT_Method='Undefined method %s',
		TEXT_NotFound='The URL %s was not found (%s request)',
		TEXT_Callback='The callback function %s is invalid',
		TEXT_Import='Import file %s not found',
		TEXT_NoRoutes='No routes specified',
		TEXT_HTTP='HTTP status code %s is invalid',
		TEXT_Render='Unable to render %s - file does not exist',
		TEXT_Form='The input handler for %s is invalid',
		TEXT_Static='%s must be a static method',
		TEXT_Template='Template expression %s cannot be resolved',
		TEXT_Write='%s must have write permission on %s',
		TEXT_Tags='PHP short tags are not supported by this server';
	//@}

	//@{ HTTP status codes (RFC 2616)
	const
		HTTP_100='Continue',
		HTTP_101='Switching Protocols',
		HTTP_200='OK',
		HTTP_201='Created',
		HTTP_202='Accepted',
		HTTP_203='Non-Authorative Information',
		HTTP_204='No Content',
		HTTP_205='Reset Content',
		HTTP_206='Partial Content',
		HTTP_300='Multiple Choices',
		HTTP_301='Moved Permanently',
		HTTP_302='Found',
		HTTP_303='See Other',
		HTTP_304='Not Modified',
		HTTP_305='Use Proxy',
		HTTP_306='Temporary Redirect',
		HTTP_400='Bad Request',
		HTTP_401='Unauthorized',
		HTTP_402='Payment Required',
		HTTP_403='Forbidden',
		HTTP_404='Not Found',
		HTTP_405='Method Not Allowed',
		HTTP_406='Not Acceptable',
		HTTP_407='Proxy Authentication Required',
		HTTP_408='Request Timeout',
		HTTP_409='Conflict',
		HTTP_410='Gone',
		HTTP_411='Length Required',
		HTTP_412='Precondition Failed',
		HTTP_413='Request Entity Too Large',
		HTTP_414='Request-URI Too Long',
		HTTP_415='Unsupported Media Type',
		HTTP_416='Requested Range Not Satisfiable',
		HTTP_417='Expectation Failed',
		HTTP_500='Internal Server Error',
		HTTP_501='Not Implemented',
		HTTP_502='Bad Gateway',
		HTTP_503='Service Unavailable',
		HTTP_504='Gateway Timeout',
		HTTP_505='HTTP Version Not Supported';
	//@}

	//@{ HTTP headers (RFC 2616)
	const
		HTTP_AcceptEnc='Accept-Encoding',
		HTTP_Agent='User-Agent',
		HTTP_Cache='Cache-Control',
		HTTP_Connect='Connection',
		HTTP_Content='Content-Type',
		HTTP_Disposition='Content-Disposition',
		HTTP_Encoding='Content-Encoding',
		HTTP_Expires='Expires',
		HTTP_Host='Host',
		HTTP_IfMod='If-Modified-Since',
		HTTP_Keep='Keep-Alive',
		HTTP_LastMod='Last-Modified',
		HTTP_Length='Content-Length',
		HTTP_Location='Location',
		HTTP_Partial='Accept-Ranges',
		HTTP_Powered='X-Powered-By',
		HTTP_Pragma='Pragma',
		HTTP_Referer='Referer',
		HTTP_Transfer='Content-Transfer-Encoding',
		HTTP_WebAuth='WWW-Authenticate';
	//@}

	const
		//! Framework-mapped PHP globals
		PHP_Globals='GET|POST|COOKIE|REQUEST|SESSION|FILES|SERVER|ENV',
		//! HTTP methods for RESTful interface
		HTTP_Methods='GET|HEAD|POST|PUT|DELETE|OPTIONS';

	//@{ Global variables and references to constants
	protected static
		$vars,
		$null=NULL,
		$true=TRUE,
		$false=FALSE;
	//@}

	private static
		//! Read-only framework variables
		$readonly='BASE|PROTOCOL|ROUTES|STATS|VERSION';

	/**
		Convert Windows double-backslashes to slashes; Also for
		referencing namespaced classes in subdirectories
			@return string
			@param $str string
			@public
	**/
	static function fixslashes($str) {
		return $str?strtr($str,'\\','/'):$str;
	}

	/**
		Convert PHP expression/value to string
			@return string
			@param $val mixed
			@public
	**/
	static function stringify($val) {
		return preg_replace('/\s+=>\s+/','=>',
			is_object($val) && !method_exists($val,'__set_state')?
				(method_exists($val,'__toString')?
					var_export((string)stripslashes($val),TRUE):
					('object:'.get_class($val))):
				var_export($val,TRUE));
	}

	/**
		Flatten array values and return as CSV string
			@return string
			@param $args mixed
			@public
	**/
	static function csv($args) {
		if (!is_array($args))
			$args=array($args);
		$str='';
		foreach ($args as $key=>$val) {
			$str.=($str?',':'');
			if (is_string($key))
				$str.=var_export($key,TRUE).'=>';
			$str.=is_array($val)?
				('array('.self::csv($val).')'):self::stringify($val);
		}
		return $str;
	}

	/**
		Split pipe-, semi-colon, comma-separated string
			@return array
			@param $str string
			@public
	**/
	static function split($str) {
		return array_map('trim',
			preg_split('/[|;,]/',$str,0,PREG_SPLIT_NO_EMPTY));
	}

	/**
		Generate Base36/CRC32 hash code
			@return string
			@param $str string
			@public
	**/
	static function hash($str) {
		return str_pad(base_convert(
			sprintf('%u',crc32($str)),10,36),7,'0',STR_PAD_LEFT);
	}

	/**
		Convert hexadecimal to binary-packed data
			@return string
			@param $hex string
			@public
	**/
	static function hexbin($hex) {
		return pack('H*',$hex);
	}

	/**
		Convert binary-packed data to hexadecimal
			@return string
			@param $bin string
			@public
	**/
	static function binhex($bin) {
		return implode('',unpack('H*',$bin));
	}

	/**
		Returns -1 if the specified number is negative, 0 if zero, or 1 if
		the number is positive
			@return integer
			@param $num mixed
			@public
	**/
	static function sign($num) {
		return $num?$num/abs($num):0;
	}

	/**
		Convert engineering-notated string to bytes
			@return integer
			@param $str string
			@public
	**/
	static function bytes($str) {
		$greek='KMGT';
		$exp=strpbrk($str,$greek);
		return pow(1024,strpos($greek,$exp)+1)*(int)$str;
	}

	/**
		Convert from JS dot notation to PHP array notation
			@return string
			@param $key string
			@public
	**/
	static function remix($key) {
		$out='';
		$obj=FALSE;
		foreach (preg_split('/\[\s*[\'"]?|[\'"]?\s*\]|\.|(->)/',
			$key,NULL,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE) as $fix) {
			if ($out) {
				if ($fix=='->') {
					$obj=TRUE;
					continue;
				}
				elseif ($obj) {
					$obj=FALSE;
					$fix='->'.$fix;
				}
				else
					$fix='['.var_export($fix,TRUE).']';
			}
			$out.=$fix;
		}
		return $out;
	}

	/**
		Return TRUE if specified string is a valid framework variable name
			@return boolean
			@param $key string
			@public
	**/
	static function valid($key) {
		if (preg_match('/^(\w+(?:\[[^\]]+\]|\.\w+|\s*->\s*\w+)*)$/',$key))
			return TRUE;
		// Invalid variable name
		trigger_error(sprintf(self::TEXT_Illegal,var_export($key,TRUE)));
		return FALSE;
	}

	/**
		Get framework variable reference/contents
			@return mixed
			@param $key string
			@param $set boolean
			@param $check boolean
			@public
	**/
	static function &ref($key,$set=TRUE,$check=TRUE) {
		// Traverse array
		$matches=preg_split(
			'/\[\s*[\'"]?|[\'"]?\s*\]|\.|(->)/',self::remix($key),
			NULL,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
		// Referencing a SESSION variable element auto-starts a session
		if ($check && $matches[0]=='SESSION' && !session_id()) {
			session_start();
			// Sync framework and PHP global
			self::$vars['SESSION']=&$_SESSION;
		}
		// Read-only framework variable?
		if ($set && !preg_match('/^('.self::$readonly.')\b/',$matches[0]))
			$var=&self::$vars;
		else
			$var=self::$vars;
		$obj=FALSE;
		foreach ($matches as $match)
			if ($match=='->')
				$obj=TRUE;
			else {
				if (preg_match('/@(\w+)/',$match,$token))
					// Token found
					$match=&self::ref($token[1]);
				if ($set) {
					// Create property/array element if not found
					if ($obj) {
						if (!is_object($var))
							$var=new stdClass;
						if (!isset($var->$match))
							$var->$match=NULL;
						$var=&$var->$match;
						$obj=FALSE;
					}
					else
						$var=&$var[$match];
				}
				elseif ($obj && isset($var->$match)) {
					// Object property found
					$var=$var->$match;
					$obj=FALSE;
				}
				elseif (is_array($var) && isset($var[$match]))
					// Array element found
					$var=$var[$match];
				else
					// Property/array element doesn't exist
					return self::$null;
			}
		if ($set && count($matches)>1 &&
			preg_match('/GET|POST|COOKIE/',$matches[0],$php)) {
			// Sync with REQUEST
			$req=&self::ref(
				preg_replace('/^'.$php[0].'\b/','REQUEST',$key),TRUE
			);
			$req=$var;
		}
		return $var;
	}

	/**
		Copy contents of framework variable to another
			@param $src string
			@param $dst string
			@public
	**/
	static function copy($src,$dst) {
		$ref=&self::ref($dst);
		$ref=self::ref($src);
	}

	/**
		Concatenate string to framework string variable
			@param $var string
			@param $val string
			@public
	**/
	static function concat($var,$val) {
		$ref=&self::ref($var);
		$ref.=$val;
	}

	/**
		Format framework string variable
			@return string
			@public
	**/
	static function sprintf() {
		return call_user_func_array('sprintf',
			array_map('self::resolve',func_get_args()));
	}

	/**
		Add keyed element to the end of framework array variable
			@param $var string
			@param $key string
			@param $val mixed
			@public
	**/
	static function append($var,$key,$val) {
		$ref=&self::ref($var);
		$ref[self::resolve($key)]=$val;
	}

	/**
		Swap keys and values of framework array variable
			@param $var string
			@public
	**/
	static function flip($var) {
		$ref=&self::ref($var);
		$ref=array_combine(array_values($ref),array_keys($ref));
	}

	/**
		Merge one or more framework array variables
			@public
	**/
	static function merge() {
		$args=func_get_args();
		foreach ($args as &$arg) {
			if (is_string($arg))
				$arg=self::ref($arg);
			if (!is_array($arg))
				trigger_error(sprintf(self::TEXT_NotArray,
					self::stringify($arg)));
		}
		$ref=&self::ref($var);
		$ref=call_user_func_array('array_merge',$args);
	}

	/**
		Add element to the end of framework array variable
			@param $var string
			@param $val mixed
			@public
	**/
	static function push($var,$val) {
		$ref=&self::ref($var);
		if (!is_array($ref))
			$ref=array();
		array_push($ref,is_array($val)?
			array_map('self::resolve',$val):
			(is_string($val)?self::resolve($val):$val));
	}

	/**
		Remove last element of framework array variable and
		return the element
			@return mixed
			@param $var string
			@public
	**/
	static function pop($var) {
		$ref=&self::ref($var);
		if (is_array($ref))
			return array_pop($ref);
		trigger_error(sprintf(self::TEXT_NotArray,$var));
		return FALSE;
	}

	/**
		Add element to the beginning of framework array variable
			@param $var string
			@param $val mixed
			@public
	**/
	static function unshift($var,$val) {
		$ref=&self::ref($var);
		if (!is_array($ref))
			$ref=array();
		array_unshift($ref,is_array($val)?
			array_map('self::resolve',$val):
			(is_string($val)?self::resolve($val):$val));
	}

	/**
		Remove first element of framework array variable and
		return the element
			@return mixed
			@param $var string
			@public
	**/
	static function shift($var) {
		$ref=&self::ref($var);
		if (is_array($ref))
			return array_shift($ref);
		trigger_error(sprintf(self::TEXT_NotArray,$var));
		return FALSE;
	}

	/**
		Evaluate template expressions in string
			@return string
			@param $val mixed
			@public
	**/
	static function resolve($val) {
		// Analyze string for correct framework expression syntax
		$self=__CLASS__;
		$str=preg_replace_callback(
			// Expression
			'/{{(.+?)}}/i',
			function($expr) use($self) {
				// Evaluate expression
				$out=preg_replace_callback(
					// Function
					'/(?<!@)\b(\w+)\s*\(([^\)]*)\)/',
					function($func) use($self) {
						return is_callable($ref=$self::ref($func[1],FALSE))?
							// Variable holds an anonymous function
							call_user_func_array($ref,str_getcsv($func[2])):
							// Check if empty array
							($func[1].$func[2]=='array'?'NULL':$func[0]);
					},
					preg_replace_callback(
						// Framework variable
						'/(?<!\w)@(\w+(?:\[[^\]]+\]|\.\w+)*'.
						'(?:\s*->\s*\w+)?)\s*(\(([^\)]*)\))?(?:\\\(.+))?/',
						function($var) use($self) {
							// Retrieve variable contents
							$val=$self::ref($var[1],FALSE);
							if (isset($var[2]) && is_callable($val))
								// Anonymous function
								$val=call_user_func_array(
									$val,str_getcsv($var[3]));
							if (isset($var[4]) && class_exists('ICU',FALSE))
								// ICU-formatted string
								$val=call_user_func_array('ICU::format',
									array($val,str_getcsv($var[4])));
							return $self::stringify($val);
						},
						$expr[1]
					)
				);
				return !preg_match('/@|\bnew\s+/i',$out) &&
					($eval=eval('return (string)'.$out.';'))!==FALSE?
						$eval:$out;
			},
			$val
		);
		return $str;
	}

	/**
		Return TRUE if IP address is local or within a private IPv4 range
			@return boolean
			@param $addr string
			@public
	**/
	static function privateip($addr) {
		return preg_match('/^127\.0\.0\.\d{1,3}$/',$addr) ||
			!filter_var($addr,FILTER_VALIDATE_IP,
				FILTER_FLAG_IPV4|FILTER_FLAG_NO_PRIV_RANGE);
	}

	/**
		Sniff headers for real IP address
			@return string
			@public
	**/
	static function realip() {
		if (isset($_SERVER['HTTP_CLIENT_IP']))
			// Behind proxy
			return $_SERVER['HTTP_CLIENT_IP'];
		elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			// Use first IP address in list
			list($ip)=explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
			return $ip;
		}
		return $_SERVER['REMOTE_ADDR'];
	}

	/**
		Return HTML-friendly dump of PHP expression
			@return string
			@param $expr mixed
			@public
	**/
	static function dump($expr) {
		ob_start();
		var_dump($expr);
		echo '<pre><code>'.ob_get_clean().'</code></pre>'."\n";
	}

	/**
		Clean and repair HTML
			@return string
			@param $html string
			@public
	**/
	static function tidy($html) {
		if (!extension_loaded('tidy'))
			return $html;
		$tidy=new Tidy;
		$tidy->parseString($html,self::$vars['TIDY'],
			str_replace('-','',self::$vars['ENCODING']));
		$tidy->cleanRepair();
		return (string)$tidy;
	}

	/**
		Create folder; Trigger error and return FALSE if script has no
		permission to create folder in the specified path
			@param $name string
			@param $perm int
			@public
	**/
	static function mkdir($name,$perm=0755) {
		if (!is_writable(dirname($name)) &&
			function_exists('posix_getpwuid')) {
			$uid=posix_getpwuid(posix_geteuid());
			trigger_error(sprintf(self::TEXT_Write,
				$uid['name'],realpath(dirname($name))));
			return FALSE;
		}
		// Create the folder
		umask(0);
		mkdir($name,$perm);
	}

	/**
		Intercept calls to undefined methods
			@param $func string
			@param $args array
			@public
	**/
	function __call($func,array $args) {
		trigger_error(sprintf(self::TEXT_Method,get_called_class().'->'.
			$func.'('.self::csv($args).')'));
	}

	/**
		Intercept calls to undefined static methods
			@param $func string
			@param $args array
			@public
	**/
	static function __callStatic($func,array $args) {
		trigger_error(sprintf(self::TEXT_Method,get_called_class().'::'.
			$func.'('.self::csv($args).')'));
	}

	/**
		Class constructor
			@public
	**/
	function __construct() {
		// Prohibit use of class as an object
		trigger_error(sprintf(self::TEXT_Object,get_called_class()));
	}

}

//! Main framework code
class F3 extends Base {

	/**
		Bind value to framework variable
			@param $key string
			@param $val mixed
			@param $persist boolean
			@param $resolve boolean
			@public
	**/
	static function set($key,$val,$persist=FALSE,$resolve=TRUE) {
		if (preg_match('/{{.+}}/',$key))
			// Variable variable
			$key=self::resolve($key);
		if (!self::valid($key))
			return;
		$var=&self::ref($key);
		if (is_string($val) && $resolve)
			$val=self::resolve($val);
		elseif (is_array($val)) {
			$var=array();
			// Recursive token substitution
			foreach ($val as $subk=>$subv)
				self::set($key.'['.var_export($subk,TRUE).']',
					$subv,FALSE);
			return;
		}
		$var=$val;
		if (preg_match('/LANGUAGE|LOCALES/',$key) && class_exists('ICU'))
			// Load appropriate dictionaries
			ICU::load();
		// Initialize cache if explicitly defined
		elseif ($key=='CACHE' && !is_bool($val))
			Cache::prep();
		if ($persist) {
			$hash='var.'.self::hash(self::remix($key));
			Cache::set($hash,$val);
		}
	}

	/**
		Retrieve value of framework variable and apply locale rules
			@return mixed
			@param $key string
			@param $args mixed
			@public
	**/
	static function get($key,$args=NULL) {
		if (preg_match('/{{.+}}/',$key))
			// Variable variable
			$key=self::resolve($key);
		if (!self::valid($key))
			return self::$null;
		$val=self::ref($key,FALSE);
		if (is_string($val))
			return class_exists('ICU',FALSE)?ICU::format($val,$args):$val;
		elseif (is_null($val)) {
			// Attempt to retrieve from cache
			$hash='var.'.self::hash(self::remix($key));
			if (Cache::cached($hash))
				$val=Cache::get($hash);
		}
		return $val;
	}

	/**
		Unset framework variable
			@param $key string
			@public
	**/
	static function clear($key) {
		if (preg_match('/{{.+}}/',$key))
			// Variable variable
			$key=self::resolve($key);
		if (!self::valid($key))
			return;
		// Clearing SESSION array ends the current session
		if ($key=='SESSION') {
			if (!session_id())
				session_start();
			// End the session
			session_unset();
			session_destroy();
		}
		preg_match('/^('.self::PHP_Globals.')(.*)$/',$key,$match);
		if (isset($match[1])) {
			$name=self::remix($key,FALSE);
			eval($match[2]?'unset($_'.$name.');':'$_'.$name.'=NULL;');
		}
		$name=preg_replace('/^(\w+)/','[\'\1\']',self::remix($key));
		// Assign NULL to framework variables; do not unset
		eval(ctype_upper(preg_replace('/^\w+/','\0',$key))?
			'self::$vars'.$name.'=NULL;':'unset(self::$vars'.$name.');');
		// Remove from cache
		$hash='var.'.self::hash(self::remix($key));
		if (Cache::cached($hash))
			Cache::clear($hash);
	}

	/**
		Return TRUE if framework variable has been assigned a value
			@return boolean
			@param $key string
			@public
	**/
	static function exists($key) {
		if (preg_match('/{{.+}}/',$key))
			// Variable variable
			$key=self::resolve($key);
		if (!self::valid($key))
			return FALSE;
		$var=&self::ref($key,FALSE,FALSE);
		return isset($var);
	}

	/**
		Multi-variable assignment using associative array
			@param $arg array
			@param $pfx string
			@public
	**/
	static function mset($arg,$pfx='') {
		if (!is_array($arg))
			// Invalid argument
			trigger_error(self::TEXT_MSet);
		else
			// Bind key-value pairs
			foreach ($arg as $key=>$val)
				self::set($pfx.$key,$val);
	}

	/**
		Determine if framework variable has been cached
			@return mixed
			@param $key string
			@public
	**/
	static function cached($key) {
		if (preg_match('/{{.+}}/',$key))
			// Variable variable
			$key=self::resolve($key);
		return self::valid($key)?
			Cache::cached('var.'.self::hash(self::remix($key))):
			FALSE;
	}

	/**
		Configure framework according to INI-style file settings;
		Cache auto-generated PHP code to speed up execution
			@param $file string
			@public
	**/
	static function config($file) {
		// Generate hash code for config file
		$hash='php.'.self::hash($file);
		$cached=Cache::cached($hash);
		if ($cached && filemtime($file)<$cached)
			// Retrieve from cache
			$save=Cache::get($hash);
		else {
			if (!is_file($file)) {
				// Configuration file not found
				trigger_error(sprintf(self::TEXT_Config,$file));
				return;
			}
			// Map sections to framework methods
			$map=array('globals'=>'set','routes'=>'route','maps'=>'map');
			// Read the .ini file
			preg_match_all(
				'/\s*(?:\[(.+?)\]|(?:;.+?)?|(?:([^=]+)=(.+?)))(?:\v|$)/s',
					file_get_contents($file),$matches,PREG_SET_ORDER);
			$cfg=array();
			$ptr=&$cfg;
			foreach ($matches as $match) {
				if (isset($match[1]) && !empty($match[1])) {
					// Section header
					if (!isset($map[$match[1]])) {
						// Unknown section
						trigger_error(sprintf(self::TEXT_Section,$section));
						return;
					}
					$ptr=&$cfg[$match[1]];
				}
				elseif (isset($match[2]) && !empty($match[2])) {
					$csv=array_map(
						function($val) {
							// Typecast if necessary
							return is_numeric($val) ||
								preg_match('/^(TRUE|FALSE)\b/i',$val)?
									eval('return '.$val.';'):$val;
						},
						str_getcsv($match[3])
					);
					// Convert comma-separated values to array
					$match[3]=count($csv)>1?$csv:$csv[0];
					if (preg_match('/([^\[]+)\[([^\]]*)\]/',$match[2],$sub)) {
						if ($sub[2])
							// Associative array
							$ptr[$sub[1]][$sub[2]]=$match[3];
						else
							// Numeric-indexed array
							$ptr[$sub[1]][]=$match[3];
					}
					else
						// Key-value pair
						$ptr[trim($match[2])]=$match[3];
				}
			}
			ob_start();
			foreach ($cfg as $section=>$pairs)
				if (isset($map[$section]) && is_array($pairs)) {
					$func=$map[$section];
					foreach ($pairs as $key=>$val)
						// Generate PHP snippet
						echo 'self::'.$func.'('.var_export($key,TRUE).','.
							($func=='set' || !is_array($val)?
								var_export($val,TRUE):self::csv($val)).
						');'."\n";
				}
			$save=ob_get_clean();
			// Compress and save to cache
			Cache::set($hash,$save);
		}
		// Execute cached PHP code
		eval($save);
		if (!is_null(self::$vars['ERROR']))
			// Remove from cache
			Cache::clear($hash);
	}

	/**
		Convert special characters to HTML entities using globally-
		defined character set
			@return string
			@param $str string
			@param $all boolean
			@public
	**/
	static function htmlencode($str,$all=FALSE) {
		return call_user_func(
			$all?'htmlentities':'htmlspecialchars',
			$str,ENT_COMPAT,self::$vars['ENCODING'],TRUE);
	}

	/**
		Convert HTML entities back to their equivalent characters
			@return string
			@param $str string
			@param $all boolean
			@public
	**/
	static function htmldecode($str,$all=FALSE) {
		return $all?
			html_entity_decode($str,ENT_COMPAT,self::$vars['ENCODING']):
			htmlspecialchars_decode($str,ENT_COMPAT);
	}

	/**
		Send HTTP status header; Return text equivalent of status code
			@return mixed
			@param $code int
			@public
	**/
	static function status($code) {
		if (!defined('self::HTTP_'.$code)) {
			// Invalid status code
			trigger_error(sprintf(self::TEXT_HTTP,$code));
			return FALSE;
		}
		// Get description
		$response=constant('self::HTTP_'.$code);
		// Send raw HTTP header
		if (PHP_SAPI!='cli' && !headers_sent())
			header($_SERVER['SERVER_PROTOCOL'].' '.$code.' '.$response);
		return $response;
	}

	/**
		Retrieve HTTP headers
			@return array
			@public
	**/
	static function headers() {
		if (PHP_SAPI!='cli') {
			if (function_exists('getallheaders'))
				// Apache server
				return getallheaders();
			// Workaround
			$req=array();
			foreach ($_SERVER as $key=>$val)
				if (substr($key,0,5)=='HTTP_')
					$req[preg_replace_callback(
						'/\w+\b/',
						function($word) {
							return ucfirst(strtolower($word[0]));
						},
						strtr(substr($key,5),'_','-')
					)]=$val;
			return $req;
		}
		return array();
	}

	/**
		Send HTTP header with expiration date (seconds from current time)
			@param $secs integer
			@public
	**/
	static function expire($secs=0) {
		if (PHP_SAPI!='cli' && !headers_sent()) {
			$time=time();
			$req=self::headers();
			if (isset($req[self::HTTP_IfMod]) &&
				strtotime($req[self::HTTP_IfMod])+$secs>$time) {
				self::status(304);
				die;
			}
			header(self::HTTP_Powered.': '.self::TEXT_AppName);
			if ($secs) {
				header_remove(self::HTTP_Pragma);
				header(self::HTTP_Expires.': '.gmdate('r',$time+$secs));
				header(self::HTTP_Cache.': max-age='.$secs);
				header(self::HTTP_LastMod.': '.gmdate('r'));
			}
			else {
				header(self::HTTP_Pragma.': no-cache');
				header(self::HTTP_Cache.': no-cache, must-revalidate');
			}
		}
	}

	/**
		Reroute to specified URI
			@param $uri string
			@public
	**/
	static function reroute($uri) {
		$uri=self::resolve($uri);
		if (PHP_SAPI!='cli' && !headers_sent()) {
			if (session_id())
				session_commit();
			// HTTP redirect
			self::status($_SERVER['REQUEST_METHOD']=='GET'?301:303);
			header(self::HTTP_Location.': '.
				(preg_match('/^https?:\/\//',$uri)?
					$uri:(self::$vars['BASE'].$uri)));
			die;
		}
		self::mock('GET '.$uri);
		self::run();
	}

	/**
		Assign handler to route pattern
			@param $pattern string
			@param $funcs mixed
			@param $ttl integer
			@param $throttle int
			@param $hotlink boolean
			@public
	**/
	static function route($pattern,$funcs,$ttl=0,$throttle=0,$hotlink=TRUE) {
		list($methods,$uri)=
			preg_split('/\s+/',$pattern,2,PREG_SPLIT_NO_EMPTY);
		foreach (self::split($methods) as $method)
			// Use pattern and HTTP methods as route indexes
			self::$vars['ROUTES'][$uri][strtoupper($method)]=
				// Save handler, cache timeout and hotlink permission
				array($funcs,$ttl,$hotlink,$throttle);
	}

	/**
		Provide REST interface by mapping URL to object/class
			@param $url string
			@param $obj mixed
			@param $ttl integer
			@param $throttle int
			@param $hotlink boolean
			@public
	**/
	static function map($url,$obj,$ttl=0,$throttle=0,$hotlink=TRUE) {
		foreach (explode('|',self::HTTP_Methods) as $method) {
			if (method_exists($obj,$method))
				self::route($method.' '.$url,
					array($obj,$method),$ttl,$throttle,$hotlink);
		}
	}

	/**
		Call route handler
			@return mixed
			@param $funcs string
			@param $listen boolean
			@public
	**/
	static function call($funcs,$listen=FALSE) {
		$classes=array();
		$funcs=is_string($funcs)?self::split($funcs):array($funcs);
		foreach ($funcs as $func) {
			if (is_string($func)) {
				$func=self::resolve($func);
				if (preg_match('/(.+)\s*(->|::)\s*(.+)/s',$func,$match)) {
					if (!class_exists($match[1]) ||
						!method_exists($match[1],$match[3])) {
						trigger_error(sprintf(self::TEXT_Callback,$func));
						return FALSE;
					}
					$func=array($match[2]=='->'?
						new $match[1]:$match[1],$match[3]);
				}
				elseif (!function_exists($func)) {
					if (preg_match('/\.php$/i',$func)) {
						foreach (self::split(self::$vars['IMPORTS'])
							as $path)
							if (is_file($file=$path.$func)) {
								$instance=new F3instance;
								return $instance->sandbox($file);
							}
						trigger_error(sprintf(self::TEXT_Import,$func));
					}
					else
						trigger_error(sprintf(self::TEXT_Callback,$func));
					return FALSE;
				}
			}
			if (!is_callable($func)) {
				trigger_error(sprintf(self::TEXT_Callback,
					is_array($func) && count($func)>1?
						(get_class($func[0]).(is_object($func[0])?'->':'::').
							$func[1]):$func));
				return FALSE;
			}
			$oop=is_array($func) &&
				(is_object($func[0]) || is_string($func[0]));
			if ($listen && $oop &&
				method_exists($func[0],$before='beforeRoute') &&
				!in_array($func[0],$classes)) {
				// Execute beforeRoute() once per class
				if (call_user_func(array($func[0],$before))===FALSE)
					return FALSE;
				$classes[]=is_object($func[0])?get_class($func[0]):$func[0];
			}
			$out=call_user_func($func);
			if ($listen && $oop &&
				method_exists($func[0],$after='afterRoute') &&
				!in_array($func[0],$classes)) {
				// Execute afterRoute() once per class
				call_user_func(array($func[0],$after));
				$classes[]=is_object($func[0])?get_class($func[0]):$func[0];
			}
		}
		return $out;
	}

	/**
		Process routes based on incoming URI
			@public
	**/
	static function run() {
		// Validate user against spam blacklists
		if (self::$vars['DNSBL'] && !self::privateip($addr=self::realip()) &&
			(!self::$vars['EXEMPT'] ||
			!in_array($addr,self::split(self::$vars['EXEMPT'])))) {
			// Convert to reverse IP dotted quad
			$quad=implode('.',array_reverse(explode('.',$addr)));
			foreach (self::split(self::$vars['DNSBL']) as $list)
				// Check against DNS blacklist
				if (gethostbyname($quad.'.'.$list)!=$quad.'.'.$list) {
					if (self::$vars['SPAM'])
						// Spammer detected; Send to blackhole
						self::reroute(self::$vars['SPAM']);
					else
						// HTTP 404 message
						self::error(404);
				}
		}
		// Process routes
		if (!isset(self::$vars['ROUTES']) || !self::$vars['ROUTES']) {
			trigger_error(self::TEXT_NoRoutes);
			return;
		}
		$found=FALSE;
		// Detailed routes get matched first
		krsort(self::$vars['ROUTES']);
		// Save the current time
		$time=time();
		foreach (self::$vars['ROUTES'] as $uri=>$route) {
			if (!preg_match('/^'.
				preg_replace(
					'/(?:{{)?@(\w+\b)(?:}})?/i',
					// Valid URL characters (RFC 1738)
					'(?P<\1>[\w\-\.!~\*\'"(),\s]+)',
					// Wildcard character in URI
					str_replace('\*','(.*)',preg_quote($uri,'/'))
				).'\/?(?:\?.*)?$/i',
				preg_replace('/^'.preg_quote(self::$vars['BASE'],'/').
					'\b(.+)/','\1',rawurldecode($_SERVER['REQUEST_URI'])),
				$args))
				continue;
			$found=TRUE;
			// Inspect each defined route
			foreach ($route as $method=>$proc) {
				if (!preg_match('/'.$method.'/',$_SERVER['REQUEST_METHOD']))
					continue;
				list($funcs,$ttl,$throttle,$hotlink)=$proc;
				if (!$hotlink && isset(self::$vars['HOTLINK']) &&
					isset($_SERVER['HTTP_REFERER']) &&
					parse_url($_SERVER['HTTP_REFERER'],PHP_URL_HOST)!=
						$_SERVER['SERVER_NAME'])
					// Hot link detected; Redirect page
					self::reroute(self::$vars['HOTLINK']);
				// Save named uri captures
				foreach ($args as $key=>$arg)
					// Remove non-zero indexed elements
					if (is_numeric($key) && $key)
						unset($args[$key]);
				self::$vars['PARAMS']=$args;
				// Default: Do not cache
				self::expire(0);
				if ($_SERVER['REQUEST_METHOD']=='GET' && $ttl) {
					$_SERVER['REQUEST_TTL']=$ttl;
					// Get HTTP request headers
					$req=self::headers();
					// Content divider
					$div=chr(0);
					// Get hash code for this Web page
					$hash='url.'.self::hash(
						$_SERVER['REQUEST_METHOD'].' '.
						$_SERVER['REQUEST_URI']
					);
					$cached=Cache::cached($hash);
					$uri='/^'.self::HTTP_Content.':.+/';
					$time=time();
					if ($cached && $time-$cached<$ttl) {
						if (!isset($req[self::HTTP_IfMod]) ||
							$cached>strtotime($req[self::HTTP_IfMod])) {
							// Activate cache timer
							self::expire($cached+$ttl-$time);
							// Retrieve from cache
							$buffer=Cache::get($hash);
							$type=strstr($buffer,$div,TRUE);
							if (PHP_SAPI!='cli' && !headers_sent() &&
								preg_match($uri,$type,$match))
								// Cached MIME type
								header($match[0]);
							// Save response
							self::$vars['RESPONSE']=substr(
								strstr($buffer,$div),1);
						}
						else {
							// Client-side cache is still fresh
							self::status(304);
							die;
						}
					}
					else {
						// Activate cache timer
						self::expire($ttl);
						$type='';
						foreach (headers_list() as $hdr)
							if (preg_match($uri,$hdr)) {
								// Add Content-Type header to buffer
								$type=$hdr;
								break;
							}
						// Cache this page
						ob_start();
						self::call($funcs,TRUE);
						self::$vars['RESPONSE']=ob_get_clean();
						if (!self::$vars['ERROR'] &&
							self::$vars['RESPONSE'])
							// Compress and save to cache
							Cache::set($hash,
								$type.$div.self::$vars['RESPONSE']);
					}
				}
				else {
					// Capture output
					ob_start();
					if ($_SERVER['REQUEST_METHOD']=='PUT') {
						// Associate PUT with file handle of stdin stream
						self::$vars['PUT']=fopen('php://input','rb');
						self::call($funcs,TRUE);
						fclose(self::$vars['PUT']);
					}
					else
						self::call($funcs,TRUE);
					self::$vars['RESPONSE']=ob_get_clean();
				}
				$elapsed=time()-$time;
				$throttle=$throttle?:self::$vars['THROTTLE'];
				if ($throttle/1e3>$elapsed)
					// Delay output
					usleep(1e6*($throttle/1e3-$elapsed));
				if (self::$vars['RESPONSE'] && !self::$vars['QUIET'])
					// Display response
					echo self::$vars['RESPONSE'];
				// Hail the conquering hero
				return;
			}
		}
		// No such Web page
		self::error(404);
	}

	/**
		Transmit a file for downloading by HTTP client; If kilobytes per
		second is specified, output is throttled (bandwidth will not be
		controlled by default); Return TRUE if successful, FALSE otherwise;
		Support for partial downloads is indicated by third argument
			@param $file string
			@param $kbps integer
			@param $partial
			@public
	**/
	static function send($file,$kbps=0,$partial=TRUE) {
		$file=self::resolve($file);
		if (!is_file($file)) {
			self::error(404);
			return FALSE;
		}
		if (PHP_SAPI!='cli') {
			header(self::HTTP_Content.': application/octet-stream');
			header(self::HTTP_Disposition.': filename="'.basename($file).'"');
			header(self::HTTP_Partial.': '.($partial?'bytes':'none'));
			header(self::HTTP_Length.': '.filesize($file));
			ob_end_flush();
		}
		$max=ini_get('max_execution_time');
		$ctr=1;
		$handle=fopen($file,'r');
		$time=microtime(TRUE);
		while (!feof($handle) && !connection_aborted()) {
			if ($kbps>0) {
				// Throttle bandwidth
				$ctr++;
				$elapsed=microtime(TRUE)-$time;
				if (($ctr/$kbps)>$elapsed)
					usleep(1e6*($ctr/$kbps-$elapsed));
			}
			// Send 1KiB and reset timer
			echo fread($handle,1024);
			set_time_limit($max);
		}
		fclose($handle);
		return TRUE;
	}

	/**
		Remove HTML tags (except those enumerated) to protect against
		XSS/code injection attacks
			@return mixed
			@param $input string
			@param $tags string
			@public
	**/
	static function scrub($input,$tags=NULL) {
		if (is_array($input))
			foreach ($input as &$val)
				$val=self::scrub($val,$tags);
		if (is_string($input)) {
			$input=($tags=='*')?
				$input:strip_tags($input,is_string($tags)?
					('<'.implode('><',self::split($tags)).'>'):$tags);
		}
		return $input;
	}

	/**
		Call form field handler
			@param $fields string
			@param $funcs mixed
			@param $tags string
			@param $filter integer
			@param $opt array
			@public
	**/
	static function input($fields,$funcs=NULL,
		$tags=NULL,$filter=FILTER_UNSAFE_RAW,$opt=array()) {
		$funcs=is_string($funcs)?self::split($funcs):array($funcs);
		foreach (self::split($fields) as $field) {
			$found=FALSE;
			// Sanitize relevant globals
			foreach (explode('|','GET|POST|REQUEST') as $var)
				if (isset(self::$vars[$var][$field])) {
					self::$vars[$var][$field]=
						self::scrub(self::$vars[$var][$field],$tags);
					$val=filter_var(self::$vars[$var][$field],$filter,$opt);
					foreach ($funcs as $func)
						if ($func) {
							if (is_string($func) &&
								preg_match('/([\w\\\]+)\s*->\s*(\w+)/',
									$func,$match))
								// Convert class->method syntax
								$func=array(new $match[1],$match[2]);
							if (!is_callable($func)) {
								// Invalid handler
								trigger_error(
									sprintf(self::TEXT_Form,$field)
								);
								return;
							}
							if (!$found) {
								$out=call_user_func($func,$val,$field);
								if ($out)
									self::$vars[$var][$field]=$out;
								$found=TRUE;
							}
							elseif ($val)
								self::$vars[$var][$field]=$val;
						}
				}
			if (!$found) {
				// Invalid handler
				trigger_error(sprintf(self::TEXT_Form,$field));
				return;
			}
		}
	}

	/**
		Render user interface
			@return string
			@param $file string
			@public
	**/
	static function render($file) {
		$file=self::resolve($file);
		foreach (self::split(self::$vars['GUI']) as $gui)
			if (is_file($view=self::fixslashes($gui.$file))) {
				$instance=new F3instance;
				$out=$instance->grab($view);
				return self::$vars['TIDY']?self::tidy($out):$out;
			}
		trigger_error(sprintf(self::TEXT_Render,$view));
	}

	/**
		Return runtime performance analytics
			@return array
			@public
	**/
	static function profile() {
		$stats=&self::$vars['STATS'];
		// Compute elapsed time
		$stats['TIME']['elapsed']=microtime(TRUE)-$stats['TIME']['start'];
		// Compute memory consumption
		$stats['MEMORY']['current']=memory_get_usage();
		$stats['MEMORY']['peak']=memory_get_peak_usage();
		return $stats;
	}

	/**
		Mock environment for command-line use and/or unit testing
			@param $pattern string
			@param $params array
			@public
	**/
	static function mock($pattern,array $params=NULL) {
		// Override PHP globals
		list($method,$uri)=preg_split('/\s+/',
			$pattern,2,PREG_SPLIT_NO_EMPTY);
		$query=explode('&',parse_url($uri,PHP_URL_QUERY));
		foreach ($query as $pair)
			if (strpos($pair,'=')) {
				list($var,$val)=explode('=',$pair);
				self::$vars[$method][$var]=$val;
				self::$vars['REQUEST'][$var]=$val;
			}
		if (is_array($params))
			foreach ($params as $var=>$val) {
				self::$vars[$method][$var]=$val;
				self::$vars['REQUEST'][$var]=$val;
			}
		$_SERVER['REQUEST_METHOD']=$method;
		$_SERVER['REQUEST_URI']=self::$vars['BASE'].$uri;
	}

	/**
		Perform test and append result to TEST global variable
			@return string
			@param $cond boolean
			@param $pass string
			@param $fail string
			@public
	**/
	static function expect($cond,$pass=NULL,$fail=NULL) {
		if (is_string($cond))
			$cond=self::resolve($cond);
		$text=$cond?$pass:$fail;
		self::$vars['TEST'][]=array(
			'result'=>(int)(boolean)$cond,
			'text'=>is_string($text)?
				self::resolve($text):var_export($text,TRUE)
		);
		return $text;
	}

	/**
		Display default error page; Use custom page if found
			@param $code integer
			@param $str string
			@param $trace array
			@public
	**/
	static function error($code,$str='',array $trace=NULL) {
		$prior=self::$vars['ERROR'];
		$out='';
		if ($code==404)
			// No stack trace needed
			$str=self::resolve(sprintf(self::TEXT_NotFound,
				$_SERVER['REQUEST_URI'],$_SERVER['REQUEST_METHOD']));
		else {
			// Generate internal server error if code is zero
			if (!$code)
				$code=500;
			if (is_null($trace))
				$trace=debug_backtrace();
			$class=NULL;
			$line=0;
			if (is_array($trace)) {
				$plugins=is_array($plugins=glob(self::$vars['PLUGINS'].'*'))?
					array_map('self::fixslashes',$plugins):array();
				// Stringify the stack trace
				ob_start();
				foreach ($trace as $nexus) {
					// Remove stack trace noise
					if (self::$vars['DEBUG']<3 && (!isset($nexus['file']) ||
						self::$vars['DEBUG']<2 &&
						(strrchr(basename($nexus['file']),'.')=='.tmp' ||
						in_array(self::fixslashes(
							$nexus['file']),$plugins)) ||
						isset($nexus['function']) &&
						preg_match('/^(call_user_func(?:_array)?|'.
							'trigger_error|{.+}|'.__FUNCTION__.'|__)/',
								$nexus['function'])))
						continue;
					echo '#'.$line.' '.
						(isset($nexus['line'])?
							(urldecode(self::fixslashes($nexus['file'])).':'.
								$nexus['line'].' '):'').
						(isset($nexus['function'])?
							((isset($nexus['class'])?
								($nexus['class'].$nexus['type']):'').
									$nexus['function'].
							'('.(!preg_match('/{{.+}}/',$nexus['function']) &&
								isset($nexus['args'])?
								(self::csv($nexus['args'])):'').')'):'').
							"\n";
					$line++;
				}
				$out=ob_get_clean();
			}
		}
		if (PHP_SAPI!='cli' && !headers_sent())
			// Remove all pending headers
			header_remove();
		// Save error details
		self::$vars['ERROR']=array(
			'code'=>$code,
			'title'=>self::status($code),
			'text'=>preg_replace('/\v/','',$str),
			'trace'=>self::$vars['DEBUG']?$out:''
		);
		$error=&self::$vars['ERROR'];
		if (self::$vars['DEBUG']<2 && self::$vars['QUIET'])
			return;
		// Write to server's error log (with complete stack trace)
		error_log($error['text']);
		foreach (explode("\n",$out) as $str)
			if ($str)
				error_log($str);
		if ($prior || self::$vars['QUIET'])
			return;
		foreach (array('title','text','trace') as $sub)
			// Convert to HTML entities for safety
			$error[$sub]=self::htmlencode(rawurldecode($error[$sub]));
		$error['trace']=nl2br($error['trace']);
		$func=self::$vars['ONERROR'];
		if ($func)
			self::call($func);
		else
			echo '<html>'.
				'<head>'.
					'<title>'.$error['code'].' '.$error['title'].'</title>'.
				'</head>'.
				'<body>'.
					'<h1>'.$error['title'].'</h1>'.
					'<p><i>'.$error['text'].'</i></p>'.
					'<p>'.$error['trace'].'</p>'.
				'</body>'.
			'</html>';
	}

	/**
		Bootstrap code
			@public
	**/
	static function start() {
		// Prohibit multiple calls
		if (self::$vars)
			return;
		// Handle all exceptions/non-fatal errors
		error_reporting(E_ALL|E_STRICT);
		ini_set('display_errors',0);
		// Get PHP settings
		$ini=ini_get_all(NULL,FALSE);
		// Intercept errors and send output to browser
		set_error_handler(
			function($errno,$errstr) {
				if (error_reporting()) {
					// Error suppression (@) is not enabled
					$self=__CLASS__;
					$self::error(500,$errstr);
				}
			}
		);
		// Do the same for PHP exceptions
		set_exception_handler(
			function($ex) {
				if (!count($trace=$ex->getTrace())) {
					// Translate exception trace
					list($trace)=debug_backtrace();
					$arg=$trace['args'][0];
					$trace=array(
						array(
							'file'=>$arg->getFile(),
							'line'=>$arg->getLine(),
							'function'=>'{main}',
							'args'=>array()
						)
					);
				}
				$self=__CLASS__;
				$self::error($ex->getCode(),$ex->getMessage(),$trace);
				// PHP aborts at this point
			}
		);
		// Apache mod_rewrite enabled?
		if (function_exists('apache_get_modules') &&
			!in_array('mod_rewrite',apache_get_modules())) {
			trigger_error(self::TEXT_Apache);
			return;
		}
		// Fix Apache's VirtualDocumentRoot limitation
		$_SERVER['DOCUMENT_ROOT']=str_replace($_SERVER['SCRIPT_NAME'],'',
			$_SERVER['SCRIPT_FILENAME']);
		// Adjust HTTP request time precision
		$_SERVER['REQUEST_TIME']=microtime(TRUE);
		// Hydrate framework variables
		$root=self::fixslashes(realpath('.')).'/';
		self::$vars=array(
			// Autoload folders
			'AUTOLOAD'=>$root,
			// Web root folder
			'BASE'=>self::fixslashes(
				preg_replace('/\/[^\/]+$/','',$_SERVER['SCRIPT_NAME'])),
			// Cache backend to use (autodetect if true; disable if false)
			'CACHE'=>FALSE,
			// Stack trace verbosity:
			// 0-no stack trace, 1-noise removed, 2-normal, 3-verbose
			'DEBUG'=>1,
			// DNS black lists
			'DNSBL'=>NULL,
			// Document encoding
			'ENCODING'=>'utf-8',
			// Last error
			'ERROR'=>NULL,
			// Allow/prohibit framework class extension
			'EXTEND'=>TRUE,
			// IP addresses exempt from spam detection
			'EXEMPT'=>NULL,
			// User interface folders
			'GUI'=>$root,
			// URL for hotlink redirection
			'HOTLINK'=>NULL,
			// Include path for procedural code
			'IMPORTS'=>$root,
			// Default language (auto-detect if null)
			'LANGUAGE'=>NULL,
			// Autoloaded classes
			'LOADED'=>NULL,
			// Dictionary folder
			'LOCALES'=>$root,
			// Maximum POST size
			'MAXSIZE'=>self::bytes($ini['post_max_size']),
			// Custom error handler
			'ONERROR'=>NULL,
			// Plugins folder
			'PLUGINS'=>self::fixslashes(__DIR__).'/',
			// Server protocol
			'PROTOCOL'=>'http'.
				(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']!='off'?'s':''),
			// Allow framework to proxy for plugins
			'PROXY'=>FALSE,
			// Stream handle for HTTP PUT method
			'PUT'=>NULL,
			// Output suppression switch
			'QUIET'=>FALSE,
			// Absolute path to document root folder
			'ROOT'=>$root,
			// Framework routes
			'ROUTES'=>NULL,
			// URL for spam redirection
			'SPAM'=>NULL,
			// Profiler statistics
			'STATS'=>array(
				'MEMORY'=>array('start'=>memory_get_usage()),
				'TIME'=>array('start'=>microtime(TRUE))
			),
			// Temporary folder
			'TEMP'=>$root.'temp/',
			// Minimum script execution time
			'THROTTLE'=>0,
			// Tidy options
			'TIDY'=>array(),
			// Framework version
			'VERSION'=>self::TEXT_AppName.' '.self::TEXT_Version
		);
		// Alias the GUI variable (2.0+)
		self::$vars['UI']=&self::$vars['GUI'];
		// Create convenience containers for PHP globals
		foreach (explode('|',self::PHP_Globals) as $var) {
			// Sync framework and PHP globals
			self::$vars[$var]=&$GLOBALS['_'.$var];
			if ($ini['magic_quotes_gpc'] && preg_match('/^[GPCR]/',$var))
				// Corrective action on PHP magic quotes
				array_walk_recursive(
					self::$vars[$var],
					function(&$val) {
						$val=stripslashes($val);
					}
				);
		}
		if (PHP_SAPI=='cli') {
			// Command line: Parse GET variables in URL, if any
			if (isset($_SERVER['argc']) && $_SERVER['argc']<2)
				array_push($_SERVER['argv'],'/');
			preg_match_all('/[\?&]([^=]+)=([^&$]*)/',
				$_SERVER['argv'][1],$matches,PREG_SET_ORDER);
			foreach ($matches as $match) {
				$_REQUEST[$match[1]]=$match[2];
				$_GET[$match[1]]=$match[2];
			}
			// Detect host name from environment
			$_SERVER['SERVER_NAME']=gethostname();
			// Convert URI to human-readable string
			self::mock('GET '.$_SERVER['argv'][1]);
		}
		// Initialize autoload stack and shutdown sequence
		spl_autoload_register(__CLASS__.'::autoload');
		register_shutdown_function(__CLASS__.'::stop');
	}

	/**
		Execute shutdown function
			@public
	**/
	static function stop() {
		$error=error_get_last();
		if ($error && !self::$vars['QUIET'] && in_array($error['type'],
			array(E_ERROR,E_PARSE,E_CORE_ERROR,E_COMPILE_ERROR)))
			// Intercept fatal error
			self::error(500,$error['message'],array($error));
		if (isset(self::$vars['UNLOAD'])) {
			ob_end_flush();
			if (PHP_SAPI!='cli')
				header(self::HTTP_Connect.': close');
			self::call(self::$vars['UNLOAD']);
		}
	}

	/**
		onLoad event handler (static class initializer)
			@public
	**/
	static function loadstatic($class) {
		$loaded=&self::$vars['LOADED'];
		$lower=strtolower($class);
		if (!isset($loaded[$lower])) {
			$loaded[$lower]=
				array_map('strtolower',get_class_methods($class));
			if (in_array('onload',$loaded[$lower])) {
				// Execute onload method
				$method=new ReflectionMethod($class,'onload');
				if ($method->isStatic())
					call_user_func(array($class,'onload'));
				else
					trigger_error(sprintf(self::TEXT_Static,
						$class.'::onload'));
			}
		}
	}

	/**
		Intercept instantiation of objects in undefined classes
			@param $class string
			@public
	**/
	static function autoload($class) {
		$list=array_map('self::fixslashes',get_included_files());
		// Support both namespace mapping styles: NS_class and NS/class
		foreach (array(str_replace('\\','_',$class),$class) as $style)
			// Prioritize plugins
			foreach (self::split(self::$vars['PLUGINS'].';'.
				self::$vars['AUTOLOAD']) as $auto) {
				$path=self::fixslashes(realpath($auto));
				if (!$path)
					continue;
				$file=self::fixslashes($style).'.php';
				if (is_int(strpos($file,'/'))) {
					$ok=FALSE;
					// Case-insensitive check for folders
					foreach (explode('/',self::fixslashes(dirname($file)))
						as $dir)
						foreach (glob($path.'/*') as $found) {
							$found=self::fixslashes($found);
							if (strtolower($path.'/'.$dir)==
								strtolower($found)) {
								$path=$found;
								$ok=TRUE;
							}
						}
					if (!$ok)
						continue;
					$file=basename($file);
				}
				$glob=glob($path.'/*.php',GLOB_NOSORT);
				if ($glob) {
					$glob=array_map('self::fixslashes',$glob);
					// Case-insensitive check for file presence
					$fkey=array_search(strtolower($path.'/'.$file),
						array_map('strtolower',$glob));
					if (is_int($fkey) && !in_array($glob[$fkey],$list)) {
						$instance=new F3instance;
						$instance->sandbox($glob[$fkey]);
						// Verify that the class was loaded
						if (class_exists($class,FALSE)) {
							// Run onLoad event handler if defined
							self::loadstatic($class);
							return;
						}
					}
				}
			}
		if (count(spl_autoload_functions())==1)
			// No other registered autoload functions exist
			trigger_error(sprintf(self::TEXT_Class,$class));
	}

	/**
		Intercept calls to undefined static methods and proxy for the
		called class if found in the plugins folder
			@return mixed
			@param $func string
			@param $args array
			@public
	**/
	static function __callStatic($func,array $args) {
		if (self::$vars['PROXY'] &&
			$glob=glob(self::fixslashes(
				self::$vars['PLUGINS'].'/*.php',GLOB_NOSORT)))
			foreach ($glob as $file) {
				$class=strstr(basename($file),'.php',TRUE);
				// Prevent recursive calls
				$found=FALSE;
				foreach (debug_backtrace() as $trace)
					if (isset($trace['class']) &&
						// Support namespaces
						preg_match('/\b'.preg_quote($trace['class']).'\b/i',
						strtolower($class)) &&
						preg_match('/'.$trace['function'].'/i',
						strtolower($func))) {
						$found=TRUE;
						break;
					}
				if ($found)
					continue;
				// Run onLoad event handler if defined
				self::loadstatic($class);
				if (in_array($func,self::$vars['LOADED'][$class]))
					// Proxy for plugin
					return call_user_func_array(array($class,$func),$args);
			}
		if (count(spl_autoload_functions())==1)
			// No other registered autoload functions exist
			trigger_error(sprintf(self::TEXT_Method,$func));
		return FALSE;
	}

}

//! Cache engine
class Cache extends Base {

	//@{ Locale-specific error/exception messages
	const
		TEXT_Backend='Cache back-end is invalid',
		TEXT_Store='Unable to save %s to cache',
		TEXT_Fetch='Unable to retrieve %s from cache',
		TEXT_Clear='Unable to clear %s from cache';
	//@}

	static
		//! Level-1 cached object
		$buffer,
		//! Cache back-end
		$backend;

	/**
		Auto-detect extensions usable as cache back-ends; MemCache must be
		explicitly activated to work properly; Fall back to file system if
		none declared or detected
			@public
	**/
	static function detect() {
		$exts=array_intersect(array('apc','xcache'),
			array_map('strtolower',get_loaded_extensions()));
		$ref=array_merge($exts,array());
		self::$vars['CACHE']=array_shift($ref)?:
			('folder='.self::$vars['ROOT'].'cache/');
	}

	/**
		Initialize cache backend
			@return boolean
			@public
	**/
	static function prep() {
		if (!self::$vars['CACHE'])
			return TRUE;
		if (preg_match(
			'/^(apc)|(memcache)=(.+)|(xcache)|(folder)\=(.+\/)/i',
			self::$vars['CACHE'],$match)) {
			if (isset($match[5]) && $match[5]) {
				if (!is_dir($match[6]))
					self::mkdir($match[6]);
				// File system
				self::$backend=array('type'=>'folder','id'=>$match[6]);
			}
			else {
				$ext=strtolower($match[1]?:($match[2]?:$match[4]));
				if (!extension_loaded($ext)) {
					trigger_error(sprintf(self::TEXT_PHPExt,$ext));
					return FALSE;
				}
				if (isset($match[2]) && $match[2]) {
					// Open persistent MemCache connection(s)
					// Multiple servers separated by semi-colon
					$pool=explode(';',$match[3]);
					$mcache=NULL;
					foreach ($pool as $server) {
						// Hostname:port
						list($host,$port)=explode(':',$server);
						if (is_null($port))
							// Use default port
							$port=11211;
						// Connect to each server
						if (is_null($mcache))
							$mcache=memcache_pconnect($host,$port);
						else
							memcache_add_server($mcache,$host,$port);
					}
					// MemCache
					self::$backend=array('type'=>$ext,'id'=>$mcache);
				}
				else
					// APC and XCache
					self::$backend=array('type'=>$ext);
			}
			self::$buffer=NULL;
			return TRUE;
		}
		// Unknown back-end
		trigger_error(self::TEXT_Backend);
		return FALSE;
	}

	/**
		Store data in framework cache; Return TRUE/FALSE on success/failure
			@return boolean
			@param $name string
			@param $data mixed
			@public
	**/
	static function set($name,$data) {
		if (!self::$vars['CACHE'])
			return TRUE;
		if (is_null(self::$backend)) {
			// Auto-detect back-end
			self::detect();
			if (!self::prep())
				return FALSE;
		}
		$key=$_SERVER['SERVER_NAME'].'.'.$name;
		// Serialize data for storage
		$time=time();
		// Add timestamp
		$val=gzdeflate(serialize(array($time,$data)));
		// Instruct back-end to store data
		switch (self::$backend['type']) {
			case 'apc':
				$ok=apc_store($key,$val);
				break;
			case 'memcache':
				$ok=memcache_set(self::$backend['id'],$key,$val);
				break;
			case 'xcache':
				$ok=xcache_set($key,$val);
				break;
			case 'folder':
				$ok=file_put_contents(
					self::$backend['id'].$key,$val,LOCK_EX);
				break;
		}
		if (is_bool($ok) && !$ok) {
			trigger_error(sprintf(self::TEXT_Store,$name));
			return FALSE;
		}
		// Free up space for level-1 cache
		while (count(self::$buffer) && strlen(serialize($data))+
			strlen(serialize(array_slice(self::$buffer,1)))>
			ini_get('memory_limit')-memory_get_peak_usage())
				self::$buffer=array_slice(self::$buffer,1);
		self::$buffer[$name]=array('data'=>$data,'time'=>$time);
		return TRUE;
	}

	/**
		Retrieve value from framework cache
			@return mixed
			@param $name string
			@param $quiet boolean
			@public
	**/
	static function get($name,$quiet=FALSE) {
		if (!self::$vars['CACHE'])
			return FALSE;
		if (is_null(self::$backend)) {
			// Auto-detect back-end
			self::detect();
			if (!self::prep())
				return FALSE;
		}
		$stats=&self::$vars['STATS'];
		if (!isset($stats['CACHE']))
			$stats['CACHE']=array(
				'level-1'=>array('hits'=>0,'misses'=>0),
				'backend'=>array('hits'=>0,'misses'=>0)
			);
		// Check level-1 cache first
		if (isset(self::$buffer) && isset(self::$buffer[$name])) {
			$stats['CACHE']['level-1']['hits']++;
			return self::$buffer[$name]['data'];
		}
		else
			$stats['CACHE']['level-1']['misses']++;
		$key=$_SERVER['SERVER_NAME'].'.'.$name;
		// Instruct back-end to fetch data
		switch (self::$backend['type']) {
			case 'apc':
				$val=apc_fetch($key);
				break;
			case 'memcache':
				$val=memcache_get(self::$backend['id'],$key);
				break;
			case 'xcache':
				$val=xcache_get($key);
				break;
			case 'folder':
				$val=is_file(self::$backend['id'].$key)?
					file_get_contents(self::$backend['id'].$key):FALSE;
				break;
		}
		if (is_bool($val)) {
			$stats['CACHE']['backend']['misses']++;
			// No error display if specified
			if (!$quiet)
				trigger_error(sprintf(self::TEXT_Fetch,$name));
			self::$buffer[$name]=NULL;
			return FALSE;
		}
		// Unserialize timestamp and data
		list($time,$data)=unserialize(gzinflate($val));
		$stats['CACHE']['backend']['hits']++;
		// Free up space for level-1 cache
		while (count(self::$buffer) && strlen(serialize($data))+
			strlen(serialize(array_slice(self::$buffer,1)))>
			ini_get('memory_limit')-memory_get_peak_usage())
				self::$buffer=array_slice(self::$buffer,1);
		self::$buffer[$name]=array('data'=>$data,'time'=>$time);
		return $data;
	}

	/**
		Delete variable from framework cache
			@return boolean
			@param $name string
			@public
	**/
	static function clear($name) {
		if (!self::$vars['CACHE'])
			return TRUE;
		if (is_null(self::$backend)) {
			// Auto-detect back-end
			self::detect();
			if (!self::prep())
				return FALSE;
		}
		$key=$_SERVER['SERVER_NAME'].'.'.$name;
		// Instruct back-end to clear data
		switch (self::$backend['type']) {
			case 'apc':
				$ok=!apc_exists($key) || apc_delete($key);
				break;
			case 'memcache':
				$ok=memcache_delete(self::$backend['id'],$key);
				break;
			case 'xcache':
				$ok=!xcache_isset($key) || xcache_unset($key);
				break;
			case 'folder':
				$ok=is_file(self::$backend['id'].$key) &&
					unlink(self::$backend['id'].$key);
				break;
		}
		if (is_bool($ok) && !$ok) {
			trigger_error(sprintf(self::TEXT_Clear,$name));
			return FALSE;
		}
		// Check level-1 cache first
		if (isset(self::$buffer) && isset(self::$buffer[$name]))
			unset(self::$buffer[$name]);
		return TRUE;
	}

	/**
		Return FALSE if specified variable is not in cache;
		otherwise, return Un*x timestamp
			@return mixed
			@param $name string
			@public
	**/
	static function cached($name) {
		return self::get($name,TRUE)?self::$buffer[$name]['time']:FALSE;
	}

}

//! F3 object mode
class F3instance {

	const
		TEXT_Conflict='%s conflicts with framework method name';

	/**
		Get framework variable reference; Workaround for PHP's
		call_user_func() reference limitation
			@return mixed
			@param $key string
			@param $set boolean
			@public
	**/
	function &ref($key,$set=TRUE) {
		return F3::ref($key,$set);
	}

	/*
		Run PHP code in sandbox
			@param $file string
			@public
	*/
	function sandbox($file) {
		return require $file;
	}

	/**
		Grab file contents
			@return mixed
			@param $file string
			@public
	**/
	function grab($file) {
		$file=F3::resolve($file);
		ob_start();
		if (!ini_get('short_open_tag')) {
			$text=preg_replace_callback(
				'/<\?(?:\s|\s*(=))(.+?)\?>/s',
				function($tag) {
					return '<?php '.($tag[1]?'echo ':'').trim($tag[2]).' ?>';
				},
				$orig=file_get_contents($file)
			);
			if (ini_get('allow_url_fopen') && ini_get('allow_url_include'))
				// Stream wrap
				$file='data:text/plain,'.urlencode($text);
			elseif ($text!=$orig) {
				// Save re-tagged file in temporary folder
				if (!is_dir($ref=F3::ref('TEMP')))
					F3::mkdir($ref);
				$temp=$ref.$_SERVER['SERVER_NAME'].'.tpl.'.F3::hash($file);
				if (!is_file($temp)) {
					// Create semaphore
					$hash='sem.'.F3::hash($file);
					$cached=Cache::cached($hash);
					while ($cached)
						// Locked by another process
						usleep(mt_rand(0,1000));
					Cache::set($hash,TRUE);
					file_put_contents($temp,$text,LOCK_EX);
					// Remove semaphore
					Cache::clear($hash);
				}
				$file=$temp;
			}
		}
		// Render
		$this->sandbox($file);
		return ob_get_clean();
	}

	/**
		Proxy for framework methods
			@return mixed
			@param $func string
			@param $args array
			@public
	**/
	function __call($func,array $args) {
		return call_user_func_array('F3::'.$func,$args);
	}

	/**
		Class constructor
			@public
	**/
	function __construct($boot=FALSE) {
		if ($boot)
			F3::start();
		// Allow application to override framework methods?
		if (F3::ref('EXTEND'))
			// User assumes risk
			return;
		// Get all framework methods not defined in this class
		$def=array_diff(get_class_methods('F3'),get_class_methods(__CLASS__));
		// Check for conflicts
		$class=new ReflectionClass($this);
		foreach ($class->getMethods() as $func)
			if (in_array($func->name,$def))
				trigger_error(sprintf(self::TEXT_Conflict,
					get_called_class().'->'.$func->name));
	}

}

// Bootstrap
return new F3instance(TRUE);
