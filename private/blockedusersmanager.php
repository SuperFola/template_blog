<?php
class BlockedUsersManager {
    protected $ips;
    protected $directory;
    protected $filename;
    
    public function __construct() {
        $ips = array();
        $this->directory = __DIR__.'/config';
        $this->filename = 'blockedips.json';
        
        $filepath = $this->directory.'/'.$this->filename;

        if (!is_dir($this->directory)) {
            mkdir($this->directory);
        }
        if (!is_file($filepath)) {
            $this->persistList();
        }

        $this->ips = $this->getFile($filepath);
    }
    
    public function isBlocked($ip) {
        foreach($this->findAll() as $ip_blocked){
            if ($ip_blocked['ip'] == $ip)
                return true;
        }
        return false;
    }
    
    public function getSize() {
        return count($this->ips);
    }
    
    public function findAll() {
        return $this->ips;
    }
    
    public function hydrate($array) {
        $this->ips[] = array('ip' => $array['ip'], 'by' => $array['executeur']);
    }
    
    public function deleteNumber($number) {
        unset($this->ips[$number]);
    }
    
    public function unlock($ip) {
        if ($this->isBlocked($ip)) {
            $to_unlock = -1;
            $nb = 0;
            foreach($this->findAll() as $ip_blocked){
                if ($ip_blocked['ip'] == $ip) {
                    $to_unlock = $nb;
                    break;
                }
                $nb++;
            }
            if ($to_unlock != -1) {
                $this->deleteNumber($to_unlock);
                return true;
            }
            return false;
        }
        return false;
    }
    
    public function persistList() {
        $filepath = $this->directory.'/'.$this->filename;

        $this->saveFile($filepath, serialize($this->ips));
    }
    
    private function saveFile($filepath, $string) {
        $file = fopen($filepath, 'w+');
        fwrite($file, $string);
        fclose($file);
    }
    
    private function getFile($filepath) {
        return unserialize(file_get_contents($filepath));
    }
} 
?>