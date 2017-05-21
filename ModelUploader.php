<?
/**
 * ModelUploader
 *
 * Uploads asset(s) to the Client Platform API
 * Author: 	Muhammad Salahuddin
 */

class ModelUploader{

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
	 * freindly filename to be used for the file being uploaded on the client server
	 * @var string
	 */
	protected $file_name;

	/**
	 * path of the file to upload
	 * @var string
	 */
	protected $file_path;

	/**
	 * project Id from Lagao
	 * @var string
	 */
	protected $project_id;

	/**
	 * session token used for committing the upload session
	 * @var string
	 */
	protected $session_token;

	/**
	 * signed url where files are uploaded 
	 * @var string
	 */
	protected $url;

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
	 * status count
	 * @var integer
	 */
	protected $status_count=0;

	/**
	 * asset id
	 * @var string
	 */
	protected $asset_id;

	/**
	 * tag
	 * @var string
	 */
	protected $tag;

	/**
     * Create a new ModelUploader instance.
     * @param String $fileName
     * @param String $filePath
     * @param String $projectId
     */
    public function __construct($fileName, $filePath, $projectId)
    {
       
        $this->file_name = $fileName;
        $this->file_path = $filePath;
        $this->project_id = $projectId;

        $this->tag = basename($fileName,'.obj');
    }

    /**
     * set session token 
     * @param String $sessionToken
     */
    public function setSessionToken($sessionToken){
    	$this->session_token = $sessionToken;
    }

    /**
     * set signed url
     * @param String $url
     */
    public function setUrl($url){
    	$this->url = $url;
    }

    /**
     * sets asset it
     * @param String $assetId
     */
    public function setAssetId($assetId){
    	$this->asset_id = $assetId;
    }

    /**
     * begins upload process
     */
    public function upload(){

    	if(empty($this->file_name) || empty($this->file_path) || empty($this->project_id)){
    		$err = 'File ERROR ('.__METHOD__.'): file name, file path and project id must be provided.';
    		$this->appendDebug($err);
			die($this->debug_str);
    	}

    	$time_start = microtime(true); 

    	$this->createNewSession();

    	$this->uploadToS3();

    	$this->commitSession();

    	$this->checkSessionStatus();

    	$this->setTags();

    	$time_end = microtime(true);

    	$this->appendDebug('****Total execution time in seconds****<br/>'.($time_end - $time_start).'<br/>');

    	$this->displayDebug();

    }

    /**
     * Request an upload session
     */
    protected function createNewSession(){

    	$data_string = sprintf(
    	'{
			"access_token":"%s",
			"files": [
				{
					"name":      "%s",
					"folder_id": "%s"
				}
			]
		}',$this->ACCESS_TOKEN , $this->file_name, $this->project_id);

    	$ch = curl_init(); 
	
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string)
		));

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

		curl_setopt($ch, CURLOPT_TIMEOUT, 30); 

		curl_setopt($ch, CURLOPT_URL, sprintf('%s%s%s.json', $this->host, $this->api_path, '/upload_sessions'));

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

		$response_object = json_decode($response);

		$this->setUrl($response_object->{'files'}[0]->{'url'});

		$this->setSessionToken($response_object->{'session_token'});
    }

    /**
     * Upload file to the signed url
     */
    protected function uploadToS3(){

    	if(!@$fp = fopen($this->file_path, "r")){
    		$err = 'File ERROR ('.__METHOD__.'): '.$this->file_path.' does not exist.<br/>';
			$this->appendDebug($err);
			die($this->debug_str);
    	}

    	$ch = curl_init(); 

    	curl_setopt($ch, CURLOPT_PUT,true);

		curl_setopt($ch, CURLOPT_HEADER,true);

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_TIMEOUT, 30);

		curl_setopt($ch, CURLOPT_URL, $this->url); 

		curl_setopt($ch, CURLOPT_INFILE, $fp);

		curl_setopt($ch, CURLOPT_INFILESIZE, filesize($this->file_path));

		$response = curl_exec($ch); 

		curl_close($ch);

		fclose($fp);

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
     * Commit the upload session 
     */
    protected function commitSession(){

    	$data_string = sprintf(
    	'{
			"access_token":"%s"
		}',$this->ACCESS_TOKEN);

		$ch = curl_init(); 
	
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string)
		));

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

		curl_setopt($ch, CURLOPT_TIMEOUT, 30); 

		curl_setopt($ch, CURLOPT_URL, sprintf('%s%s%s/%s.json', $this->host, $this->api_path, '/upload_sessions', $this->session_token));

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
     * Check session status
     */
    protected function checkSessionStatus(){

    	$this->status_count++;

    	$data_string = sprintf(
    	'{
			"access_token":"%s"
		}',$this->ACCESS_TOKEN);

		$ch = curl_init(); 
	
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string)
		));

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

		curl_setopt($ch, CURLOPT_TIMEOUT, 30);

		curl_setopt($ch, CURLOPT_URL, sprintf('%s%s%s/%s.json', $this->host, $this->api_path, '/upload_sessions', $this->session_token));

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

		$response_object = json_decode($response);

		//$this->appendDebug('<br/>status: '.$response_object->{'files'}[0]->{'status'}.'<br/>');

		if($response_object->{'files'}[0]->{'status'} === 'transcoding'){

			if($this->status_count === 30){
				$err = '****STATUS CHECK EXCEEDED MAX TIME****<br/>';
				$this->appendDebug($err);
				die($this->debug_str);
			}

			sleep(1);
			$this->checkSessionStatus();
		}
		else{
			//$this->asset_id = $response_object->{'files'}[0]->{'assets'}[0]->{'id'};
			$this->setAssetId($response_object->{'files'}[0]->{'assets'}[0]->{'id'});
			$this->appendDebug('**asset_id='.$this->asset_id.'<br/>');
			$this->appendDebug('**tag='.$this->tag.'<br/>');
		}

    }

    /**
     * Set tags
     */
    protected function setTags(){

    	$data_string = sprintf(
    	'{
			"access_token":"%s",
			"asset":{
				"tags_attributes":[
					{ "name" : "%s"}
				]
			}
		}',$this->ACCESS_TOKEN, $this->tag);

		//$this->appendDebug('<br/>'.$data_string.'<br/>');
		//return;

		$ch = curl_init(); 
	
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json',
			'Content-Length: ' . strlen($data_string)
		));

		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');

		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 

		curl_setopt($ch, CURLOPT_TIMEOUT, 30);

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
	* clears the current debug data for this instance
	*
	*/
	protected function clearDebug() {
		$this->debug_str = '';
	}

	/**
	* display the current debug data for this instance
	*
	*/
	protected function displayDebug() {
		print $this->debug_str;
	}
}

?>