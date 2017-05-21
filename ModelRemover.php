<?
/**
 * ModelRemover
 *
 * Removes asset(s) from the Client Platform API
 * Author: 	Muhammad Salahuddin
 */

class ModelRemover{

	/**
	 * host
	 * @var string
	 */
	protected $host='https://example.com'; 

	/**
	 * version number
	 * @var string
	 */
	protected $api_path='/v1';

	/**
	 * access token
	 * @var string
	 */
	protected $ACCESS_TOKEN='123456789';


	/**
	 * project Id
	 * @var string
	 */
	protected $project_id;

	/**
	 * asset Id
	 * @var string
	 */
	protected $asset_id;

	/**
	 * enable debuggin
	 * @var boolean
	 */
	private $debug=true;

	/**
	 * debug string
	 * @var string
	 */
	protected $debug_str='';

	/**
     * Create a new ModelRemover instance.
     * @param String $projectId
     * @param String $assetId
     */
    public function __construct($projectId, $assetId)
    {
       
        $this->project_id = $projectId;
        $this->asset_id = $assetId;
    }

    /**
     * begins remove process
     */
    public function remove(){

    	if(empty($this->project_id) || empty($this->asset_id)){
    		$err = 'File ERROR ('.__METHOD__.'): project id and asset id must be provided.';
    		$this->appendDebug($err);
			die($this->debug_str);
    	}

    	$this->removeFromProject();

    	$this->deleteAsset();

    	$this->displayDebug();

    }


    /**
     * removes asset from the project
     */
    public function removeFromProject(){

    	$data_string = sprintf(
    	'{
			"access_token":"%s",
			"project_id":"%s"
		}',$this->ACCESS_TOKEN, $this->project_id);

		$ch = curl_init(); 
	
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string)
		));

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

		curl_setopt($ch, CURLOPT_URL, sprintf('%s%s%s/%s.json', $this->host, $this->api_path, '/assets', $this->asset_id));

		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);  

		$response = curl_exec($ch); 
		
		curl_close($ch);

		// error check
		if($response === false){
			$err = 'cURL ERROR ('.__METHOD__.'): '.curl_errno($ch).': '.curl_error($ch).'<br/>';
			$this->appendDebug($err);
			die($this->debug_str);
		}

		// debug
		$this->appendDebug('****'.__METHOD__.'****<br/>'.$response.'<br/>');
    }

    /**
     * deletes asset
     */
    public function deleteAsset(){

    	$data_string = sprintf(
    	'{
			"access_token":"%s"
		}',$this->ACCESS_TOKEN);

		$ch = curl_init(); 
	
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string)
		));

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

		curl_setopt($ch, CURLOPT_URL, sprintf('%s%s%s/%s.json', $this->host, $this->api_path, '/assets', $this->asset_id));

		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);  

		$response = curl_exec($ch); 
		
		curl_close($ch);

		// error check
		if($response === false){
			$err = 'cURL ERROR ('.__METHOD__.'): '.curl_errno($ch).': '.curl_error($ch).'<br/>';
			$this->appendDebug($err);
			die($this->debug_str);
		}

		// debug
		$this->appendDebug('****'.__METHOD__.'****<br/>'.$response.'<br/>');
    }


    /**
	* adds debug data to the instance debug string without formatting
	*
	* @param    string $string debug data
	*/
	protected function appendDebug($string){
		if ($this->debug === true) {
			// it would be nice to use a memory stream here to use
			// memory more efficiently
			$this->debug_str .= $string;
		}
	}

	/**
	* display the current debug data for this instance
	*
	*/
	protected function displayDebug() {
		print $this->debug_str;
	}


}