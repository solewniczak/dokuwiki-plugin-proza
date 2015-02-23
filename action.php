<?php
 
if(!defined('DOKU_INC')) die();
 
class action_plugin_proza extends DokuWiki_Action_Plugin {

	private $action = '';
	private $params = array();
	private $t = array();

	/**
	 * Register its handlers with the DokuWiki's event controller
	 */
	function register(Doku_Event_Handler $controller) {
		$controller->register_hook('ACTION_ACT_PREPROCESS', 'BEFORE', $this, 'action_act_preprocess');
		$controller->register_hook('TPL_ACT_RENDER', 'BEFORE', $this, 'tpl_act_render');
		$controller->register_hook('TEMPLATE_PAGETOOLS_DISPLAY', 'BEFORE', $this, 'tpl_pagetools_display');
	}

	function __construct() {
		global $ACT;

		$id = $_GET['id'];
		$ex = explode(':', $id);
		if ($ex[0] == 'proza' && $ACT == 'show') {
			$this->action = $ex[1];
			$ex = array_slice($ex, 2);
		/*BEZ w innym języku*/
		} else if ($ex[1] == 'proza' && $ACT == 'show') {
			/*$l = $ex[0];
			$p = DOKU_PLUGIN.'proza/lang/';
			$f = $p.$ex[0].'/lang.php';
			if ( ! file_exists($f))
			$f = $p.'en/lang.php';*/

			$this->action = $ex[2];
			$ex = array_slice($ex, 3);
		}
		for ($i = 0; $i < count($ex); $i += 2)
			$this->params[urldecode($ex[$i])] = urldecode($ex[$i+1]);
		$this->setupLocale();
	}
	public function preventDefault() {
		throw new Exception('preventDefault');
	}

	function id() {
		$args = func_get_args();
		array_unshift($args, 'proza');
		if ($this->lang_code != '')
			array_unshift($args, $this->lang_code);
		return implode(':', $args);
	}

	function display_error($error) {
		echo '<div class="error">';
		echo $error;
		echo '</div>';
	}

	function display_validation_errors($errors) {
		if ( ! is_array($errors)) return;

		foreach ($errors as $e) {
			$this->display_error($this->getLang('h_'.$e[0]).': '.$this->getLang('e_'.$e[1]));
		}
	}

	function tpl_pagetools_display($event, $param) {
		if ($this->action == '')  return;
		$event->preventDefault();
	}

	function action_act_preprocess($event, $param) {
		if ($this->action == '') return;

		$ctl = DOKU_PLUGIN."proza/ctl/".str_replace('/', '', $this->action).".php";
		if (file_exists($ctl)) {
			//wczytaj konfigurację
			$this->loadConfig();
			try {
				require $ctl;
			} catch(Proza_DBException $e) {
				$this->error = $e->getMessage();
				$this->action = 'error';
			}
		}
	}

	function tpl_act_render($event, $param) {
		if ($this->action == '') return;
		elseif ($this->action == 'error') { $this->display_error($this->error); $event->preventDefault(); return; }
		$tpl = DOKU_PLUGIN."proza/tpl/".str_replace('/', '', $this->action).".php";
		if (file_exists($tpl))
			require $tpl;
		$event->preventDefault();
	}
}