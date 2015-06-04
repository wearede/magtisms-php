<?php

/**
* 
*/
class magtisms
{

	/**
	 * URL for sending SMS.
	 * @var string
	 */
	private $sms_send_url = 'http://81.95.160.47/mt/oneway';

	/**
	 * URL for tracking SMS status.
	 * @var string
	 */
	private $sms_track_url = 'http://81.95.160.47/bi/track';

	/**
	 * Client username provided by magticom
	 * @var string
	 */
	private $username;

	/**
	 * Client password provided by magticom
	 * @var string
	 */
	private $password;

	/**
	 * Unique client id provided by magticom
	 * @var integer
	 */
	private $client_id;

	/**
	 * Unique service id provided by magticom
	 * @var integer
	 */
	private $service_id;

	/**
	 * Optional parameter: message character coding.
	 * 0 = 7 bit gsm
	 * 1 = 8 bit iso-8859-1
	 * 2 = unicode UCS2
	 * Default coding is 0
	 * @var integer
	 */
	private $coding;

	/**
	 * Return codes
	 * @var array
	 */
	private $responses = array(

		'send_sms'  => array(

		),
		'track_sms' => array(

		),

	);
	
	/**
	 * @param string   $username   Client username provided by magticom
	 * @param string   $password   Client password provided by magticom
	 * @param integer  $client_id  Unique client id provided by magticom
	 * @param integer  $service_id Unique service id provided by magticom
	 * @param integer  $coding     Default is 0, 0 = 7big gsm
	 */
	function __construct( $username, $password, $client_id, $service_id, $coding = 0 )
	{
		$this->username   = $username;
		$this->password   = $password;
		$this->client_id  = $client_id;
		$this->service_id = $service_id;
		$this->coding     = $coding;
	}

	/**
	 * Curl is responsible for sending data to remote server
	 * @param  string $query_string created from an array using method build_query_string
	 * @param  string $url          either $sms_send_url or $sms_track_url
	 * @return string               returns magti server response
	 */
	private function curl( $query_string, $url )
	{
		$curl = curl_init();

		curl_setopt( $curl, CURLOPT_POSTFIELDS,     $query_string );
		curl_setopt( $curl, CURLOPT_VERBOSE,        1 );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt( $curl, CURLOPT_URL,            $url );

		$result = curl_exec( $curl );
		
		return $result;
	}

	/**
	 * Building string from array
	 * @param  array $post_fields
	 * @return string
	 */
	private function build_query_string( $post_fields )
	{
		return http_build_query( $post_fields );
	}

	/**
	 * [parse_result description]
	 * @param  [type] $string [description]
	 * @return [type]         [description]
	 */
	private function parse_result( $string )
	{
		return $string;
	}

	/**
	 * [process description]
	 * @param  [type] $post_fields [description]
	 * @param  [type] $url         [description]
	 * @return [type]              [description]
	 */
	private function process( $post_fields, $url )
	{
		$string = $this->build_query_string( $post_fields );
		$result = $this->curl( $string, $url );
		$parsed = $this->parse_result( $result );

		return $parsed;
	}

	/**
	 * [send_sms description]
	 * @param  [type] $to   [description]
	 * @param  [type] $text [description]
	 * @return [type]       [description]
	 */
	public function send_sms( $to, $text )
	{
		$post_fields = array(
			'username'   => $this->username,
			'password'   => $this->password,
			'client_id'  => $this->client_id,
			'service_id' => $this->service_id,
			'to'         => $to,
			'text'       => $text,
		);

		return $this->process( $post_fields, $this->sms_send_url );
	}

	/**
	 * [track_sms description]
	 * @param  [type] $sms_id [description]
	 * @return [type]         [description]
	 */
	public function track_sms( $sms_id )
	{
		$post_fields = array(
			'username'   => $this->username,
			'password'   => $this->password,
			'client_id'  => $this->client_id,
			'service_id' => $this->service_id,
			'message_id' => $sms_id,
		);

		return $this->process( $post_fields, $this->sms_track_url );
	}

}

?>