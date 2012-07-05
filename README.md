# Codeigniter ColourLovers library

The library makes possible to communicate with the colourlovers.com API.

## Installation

Copy the files to their appropriate folders, then set up the config file.

## Usage

Simple usage:

	$this->load->library('cicolourlovers');
	$result = $this->cicolourlovers->colors();

Adding parameters:

	$result = $this->cicolourlovers->colors(array('numResults' => 10, 'sortBy' => 'DESC'));

Get back one color info (hex value):

	$result = $this->cicolourlovers->color('AA44F2');

Get back a specific id paletter:

	$result = $this->cicolourlovers->palette(12345);

Overwrite config setting:

	$result = $this->cicolourlovers->colors(array('format' => 'json'));

You can find the full list of methods at [www.colourlovers.com/api](www.colourlovers.com/api)

## Requirements

CodeIgniter 2.0+  
PHP 5 cURL installed  
[cURL library](http://getsparks.org/packages/curl) by Phil Sturgeon  

##License

[MIT License](http://www.opensource.org/licenses/MIT)

C. 2012 Barna Szalai (sz.b@devartpro.com)