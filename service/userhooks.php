<?php
namespace OCA\OwnNotes\Service;
use Exception;

use OCP\AppFramework\App;
use OCA\OwnNotes\Db\Note;
use OCA\OwnNotes\AppInfo\Application;
use OC\Files\Node\HookConnector;

class UserHooks {

    private $userManager;

    public function __construct($userManager){
        $this->userManager = $userManager;
        $this->storage = $userManager;
    }
    
    public static function writePost($node){
       $app = new App("OwnNotes");
       $content = "aaa"; //->getPath();
       $storage = $app->getContainer()->query('ServerContainer')->getRootFolder();
       $usermanager = $app->getContainer()->query('ServerContainer')->getUserManager();
       $user = $usermanager->get($node["run"]);
       //$homedir = $user->getHome();
       //$hookconector = new gt
       //$truenode = $
       $homedir = "/var/www/html/data/test";
       /*
       /*

            // your code that executes before $user is deleted
					try {
							$file = $storage->getById($node["run"]);
							if($file instanceof \OCP\Files\File) {
									$content = $file->getContent();
							} else {
										throw new StorageException('Can not read from folder');
							}
					} catch(\OCP\Files\NotFoundException $e) {
							throw new StorageException('File does not exist');
					}*/
	\OCP\Util::writeLog('ftpquota', 'get user home failed: '.$user, \OCP\Util::ERROR);
	\OCP\Util::writeLog('ftpquota', 'get user home failed: '.escapeshellarg($homedir.'/files'.$node["path"]), \OCP\Util::ERROR);
	exec("/bin/cat ".escapeshellarg($homedir.'/files'.$node["path"]), $output, $return_value);
	//if ( $return_value !== 0 ) {
	\OCP\Util::writeLog('ftpquota', 'pure-quotacheck returned '.$return_value.' '.implode("\n", $output), \OCP\Util::ERROR);
	\OCP\Util::writeLog('ftpquota', 'pure-quotacheck returned '.var_export($node, true), \OCP\Util::ERROR);
        $mapper = $app->getContainer()->query('OCA\OwnNotes\Db\NoteMapper');
	$service = new NoteService($mapper);
        $service->create($node["path"], implode("\n", $output), $node["run"]);
        //}
	/*
        try {
            try {
                $file = $storage->get('/myfile.txt');
            } catch(\OCP\Files\NotFoundException $e) {
                $storage->touch('/myfile.txt');
                $file = $storage->get('/myfile.txt');
            }

            // the id can be accessed by $file->getId();
	    \OCP\Util::writeLog('ftpquota', 'get user home failed: '. $content, \OCP\Util::ERROR);
            $file->putContent($content);

        } catch(\OCP\Files\NotPermittedException $e) {
            // you have to create this exception by yourself ;)
            throw new StorageException('Cant write to file');
        }*/
    }

    public function register() {

        $callback = function($node) {
            // your code that executes before $user is deleted
					try {
							$file = $node;//this->storage->getById($node.getId());
							if($file instanceof \OCP\Files\File) {
									$content = $file->getContent();
							} else {
										throw new StorageException('Can not read from folder');
							}
					} catch(\OCP\Files\NotFoundException $e) {
							throw new StorageException('File does not exist');
					}
        $path = $node->getPath();
        $app = new App("OwnNotes");
        $homedir = "/var/www/html/data"; // in this docker image.
	$base_url = 'https://sharpmecab2.herokuapp.com/api/v1/';
/*
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $base_url);
	curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
	//$header2 = ['Content-Type: appllication/json'];
	$data = [ 'email' => $content ];
	curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data)); // jsonデータを送信
	//curl_setopt($curl, CURLOPT_HTTPHEADER, $header2);
	//curl_setopt($curl, CURLOPT_HEADER, true);
	//curl_setopt($curl, CURLOPT_POST, true);
	//curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	//curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	$base_url = 'https://sharpmecab2.herokuapp.com/api/v1/';
	$data = array('data' => $content, 'path' => $path);

	// use key 'http' even if you send the request to https://...
	$options = array(
	    'http' => array(
	            'header'  => "User-Agent Mozilla/5.0 (Windows NT 6.1; WOW64; rv:24.0) Gecko/20100101 Firefox/24.0\r\n"
                               . "Content-type: application/x-www-form-urlencoded\r\n",
		    'method'  => 'POST',
		    'content' => http_build_query($data)
		)
		);
	$context  = stream_context_create($options);
	//$result = file_get_contents($base_url, false, $context);

	//$header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE); 
	//$header22 = substr($response, 0, $header_size);
	//$body = substr($response, $header_size);
	//$result = json_decode($body, true); 
        //curl_close($curl);
	//\OCP\Util::writeLog('ftpquota', 'pure-quotacheck returned '.print_r($result, true), \OCP\Util::ERROR);
*/
        //exec("/bin/cat ".escapeshellarg($homedir.$path), $output, $return_value);
	//\OCP\Util::writeLog('ftpquota', 'pure-quotacheck returned '."/usr/bin/curl -F ".escapeshellarg("data=".$content." -F path=".$path." ".$base_url), \OCP\Util::ERROR);
        //try {
        //    exec("/usr/bin/curl -F ".escapeshellarg("data=".$content)." -F ".escapeshellarg("path=".$path)." ".$base_url, $output, $return_value);
        //} catch(Exception $e) {
            exec("/usr/bin/curl -F ".escapeshellarg("data=".base64_encode($content))." -F ".escapeshellarg("path=".$path)." ".$base_url, $output, $return_value);
	//}
        \OCP\Util::writeLog('ftpquota', 'pure-quotacheck returned '.$return_value.' '.implode("\n", $output), \OCP\Util::ERROR);
        if ( $return_value == 0 ) {
	//\OCP\Util::writeLog('ftpquota', 'pure-quotacheck returned '.$return_value.' '.implode("\n", $output), \OCP\Util::ERROR);
        	$mapper = $app->getContainer()->query('OCA\OwnNotes\Db\NoteMapper');
		$service = new NoteService($mapper);
        	$service->create($path.' '.date(DATE_ATOM), implode("\n", $output), $node->getOwner()->getUID());
        }
/*
        try {
            try {
                $file = $this->storage->get('/test/files/myfile2.txt');
            } catch(\OCP\Files\NotFoundException $e) {
                $this->storage->touch('/test/files/myfile2.txt');
                $file = $this->storage->get('/test/files/myfile2.txt');
            }

            // the id can be accessed by $file->getId();
            $file->unlock(\OCP\Lock\ILockingProvider::LOCK_EXCLUSIVE);
            $file->putContent($content);

        } catch(\OCP\Files\NotPermittedException $e) {
            // you have to create this exception by yourself ;)
            throw new StorageException('Cant write to file');
        }*/
        };

        $this->userManager->listen('\OC\Files', 'postWrite', $callback);
    }

}
