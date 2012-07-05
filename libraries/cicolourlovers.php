<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
* CIColourLovers class
*
* Make requests for the colourlovers.com API
*
* @package CodeIgniter
* @subpackage Libraries
* @category Libraries
* @author Barna Szalai <sz.b@devartpro.com>
* @created 05/07/2012
* @license www.opensource.org/licenses/MIT
*/

class cicolourlovers 
{
	private $_api_format = 'xml'; // xml or json
	private $_result_type = 'array'; // array, object, raw
	private $_ci;

	private $_api_url = 'http://www.colourlovers.com/api/';

	public function __construct()
	{
		$this->_ci =& get_instance();

		$this->_ci->load->spark('curl/1.2.1');

		$this->_ci->load->config('cicolourlovers');
		$this->_api_format = $this->_ci->config->item('api_format');
		$this->_result_type = $this->_ci->config->item('result_type');

		log_message('debug', 'CIColourLover library started.');
	}

	public function colors($params = array())
	{
		return $this->_build('colors', $params);
	}
	
	public function colors_new($params = array())
	{
		return $this->_build('colors_new', $params);
	}

	public function colors_top($params = array())
	{
		return $this->_build('colors_top', $params);
	}

	public function colors_random()
	{
		return $this->_build('colors_random', array());
	}

	public function color($hexvalue, $params = array())
	{
		if( ! preg_match('/^[a-fA-F0-9]{6}$/', $hexvalue))
		{
			return FALSE;
		}

		return $this->_build('color_'.$hexvalue, $params);
	}

	public function palettes($params = array())
	{
		return $this->_build('palettes', $params);
	}

	public function palettes_new($params = array())
	{
		return $this->_build('palettes_new', $params);
	}

	public function palettes_top($params = array())
	{
		return $this->_build('palettes_top', $params);
	}

	public function palettes_random()
	{
		return $this->_build('palettes_random', array());
	}

	public function palette($id, $params = array())
	{
		if( ! is_int($id))
		{
			return FALSE;
		}

		return $this->_build('palette_'.$id, $params);
	}

	public function patterns($params = array())
	{
		return $this->_build('patterns', $params);
	}

	public function patterns_new($params = array())
	{
		return $this->_build('patterns_new', $params);
	}

	public function patterns_top($params = array())
	{
		return $this->_build('patterns_top', $params);
	}

	public function patterns_random()
	{
		return $this->_build('patterns', array());
	}

	public function pattern($id, $params = array())
	{
		if( ! is_int($id))
		{
			return FALSE;
		}

		return $this->_build('pattern_'.$id, $params);
	}

	public function lovers($params = array())
	{
		return $this->_build('lovers', $params);
	}

	public function lovers_new($params = array())
	{
		return $this->_build('lovers_new', $params);
	}

	public function lovers_top($params = array())
	{
		return $this->_build('lovers_top', $params);
	}

	public function lover($username, $params = array())
	{
		if( ! is_string($username))
		{
			return FALSE;
		}

		return $this->_build('lover_'.$username, $params);
	}

	public function stats_colors($params = array())
	{
		return $this->_build('stats_colors', $params);
	}	

	public function stats_palettes($params = array())
	{
		return $this->_build('stats_palettes', $params);
	}	

	public function stats_patterns($params = array())
	{
		return $this->_build('stats_patterns', $params);
	}	

	public function stats_lovers($params = array())
	{
		return $this->_build('stats_lovers', $params);
	}	

	private function _build($method, $params = array())
	{
		// building the request link
		$link = array($this->_api_url, str_replace('_', '/', $method));

		// set format from params, if not entered then use config setting
		$params['format'] = $this->_api_format = (array_key_exists('format', $params)) ? $params['format'] : $this->_api_format;

		array_push($link, '?');
		array_push($link, http_build_query($params));

		$request = implode('', $link);
		
		// get the response
		$response = $this->_ci->curl->simple_get($request);

		// if something went wrong..
		if(count($this->_ci->curl->error_code)) 
		{
			log_message('error', 'Error in CIColourLovers library: '.$this->_ci->curl->error_string.' - cURL error code:'.$this->_ci->curl->error_code);
			throw new Exception($this->_ci->curl->error_string.' - cURL error code:'.$this->_ci->curl->error_code);			
		}
		
		if($this->_result_type !== 'raw')
		{
			switch($this->_api_format)
			{
				case 'xml' : 
					
					$simplexml = simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);

					// we need array result type
					if($this->_result_type === 'array') 
					{
						$response = $this->_xml_to_array($simplexml);
					}
					// or object result type
					else
					{
						$response = $simplexml;
					}
				
				break;

				case 'json'	:

					$return_type_array = FALSE;

					// return array or object
					if($this->_result_type === 'array') 
					{
						$return_type_array = TRUE;
					}
					$response = json_decode($response, $return_type_array);

				break;

				default:
				
					$response = FALSE;
				
				break;
			}
		}

		return $response;
	}

	private function _xml_to_array($xmlstring)
	{		
		$json = json_encode($xmlstring);
		$array = json_decode($json,TRUE);
		return $array;
	}
}
/* End of file CIColourLovers.php */
/* Location: ./application/libraries/CIColourLovers.php */