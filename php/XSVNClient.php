<?php

/**
 * Toknot
 *
 * XSVNClient
 *
 * PHP version 5.3
 * 
 * @package XDataStruct
 * @author chopins xiao <chopins.xiao@gmail.com>
 * @copyright  2012 The Authors
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @link       http://blog.toknot.com
 * @since      File available since Release $id$
 */
exists_frame();
/**
 * XSVNClient 
 * 
 * @package 
 * @version $id$
 * @author Chopins xiao <chopins.xiao@gmail.com> 
 */
class XSVNClient {
    private $socket = '';
    private $errno;
    private $errstr;
    private $server_data_dir;
    private $server_url;
    private $local_dir;
    private $repos_name = null;
    private $config_list = array();
    public function __construct($config_file) {
        $this->load_cfg($config_file);
        dl_extension('svn', 'svn_checkout');
   //     $this->deamon();
    }

    /**
     * set_repos_name 
     * 
     * @param mixed $name 
     * @access public
     * @return void
     */
    public function set_repos_name($name) {
        $this->repos_name = $name;
    }

    /**
     * repos_list 
     * 
     * @access public
     * @return array
     */
    public function repos_list() {
        $repos_list = array();
        $arr = scandir($this->server_data_dir);
        $k = 0;
        foreach($arr as $repos_name) {
            if($repos_name == '.' || $repos_name == '..') continue;
            $repos_info = svn_info($this->local_dir.'/'.$repos_name, false);
            $repos_list[$k] = $repos_info[0];
        }
        return $repos_list;
    }
    public function ls($dir = '/') {
        return svn_ls($this->server_url.'/'.$this->repos_name.$dir);
    }
    public function checkout() {
        return svn_checkout($this->server_url.'/'.$this->repos_name, 
                        $this->local_dir.'/'.$this->repos_name);
    }
    public function worker_revision() {
        $info = svn_info($this->local_dir.'/'.$this->repos_name, false);
        return $info[0]['revision'];
    }
    public function update($filepath) {
        return svn_update($this->local_dir.'/'.$this->repos_name.$filepath);
    }
    /**
     * change_list 
     * get server repository lastest to local woker revision change log list
     * 
     * @access public
     * @return void
     */
    public function change_list() {
        $local_revision = $this->worker_revision();
        $log_list = svn_log($this->server_url.'/'.$this->repos_name,SVN_REVISION_HEAD,
                            $local_revision);
        return $log_list;
    }
    public function update_all() {
        return svn_update($this->local_dir.'/'.$this->repos_name);
    }
    public function status() {
        return svn_status($this->local_dir.'/'.$this->repos_name);
    }
    public function logs($path = '/') {
        return svn_log($this->server_url.'/'.$this->repos_name.$path);
    }
    public function use_confg($idx) {
        $this->server_url = $this->config_list[$idx]['server_url'];
        $this->server_data_dir = $this->config_list[$idx]['repos_path'];
        $this->local_dir = $this->config_list[$idx]['worker_path'];
    }
    private function load_cfg($config_file) {
        $config_file = __X_APP_DATA_DIR__."/conf/{$config_file}";
        if(!file_exists($config_file)) {
            throw new XException("{$config_file} not exists");
        }
        $this->config_list = XConfig::parse_ini($config_file);
        return;
    }
}
