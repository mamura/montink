<?php
class Template {

    protected $ci;
    protected $vars = [];

    public function __construct() {
        $this->ci =& get_instance();
    }

    public function set($name, $value) {
        $this->vars[$name] = $value;
    }

    public function load($layout, $view, $data = []) {
        $this->vars['contents'] = $this->ci->load->view($view, $data, true);
        $this->ci->load->view($layout, $this->vars);
    }
}
