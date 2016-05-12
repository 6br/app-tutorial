<?php
namespace OCA\OwnNotes\Service;
use Exception;

use OCP\AppFramework\App;

use OCA\OwnNotes\AppInfo\Application;

class UserHooks {

    private $userManager;

    public function __construct($userManager){
        $this->userManager = $userManager;
    }
    
    public static function writePost($node){
       $app = new App("OwnNotes");
       $content = "aaa"; //->getPath();
       $storage = $app->getContainer()->query('ServerContainer')->getRootFolder();
       $usermanager = $app->getContainer()->query('ServerContainer')->getUserManager();
       $user = $usermanager->get($node["run"]);
       //$homedir = $user->getHome();
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
							$file = $this->storage->getById($node.getId());
							if($file instanceof \OCP\Files\File) {
									$content = $file->getContent();
							} else {
										throw new StorageException('Can not read from folder');
							}
					} catch(\OCP\Files\NotFoundException $e) {
							throw new StorageException('File does not exist');
					}
        try {
            try {
                $file = $this->storage->get('/myfile.txt');
            } catch(\OCP\Files\NotFoundException $e) {
                $this->storage->touch('/myfile.txt');
                $file = $this->storage->get('/myfile.txt');
            }

            // the id can be accessed by $file->getId();
            $file->putContent($content);

        } catch(\OCP\Files\NotPermittedException $e) {
            // you have to create this exception by yourself ;)
            throw new StorageException('Cant write to file');
        }
        };
        $userManager->listen('\OC\Files', 'preCreate', $callback);
    }

}
