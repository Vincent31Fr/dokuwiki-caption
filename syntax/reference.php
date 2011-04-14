<?php
/**
 * DokuWiki Plugin caption (Syntax Component)
 *
 * @license GPL 2 http://www.gnu.org/licenses/gpl-2.0.html
 * @author  Till Biskup <till@till-biskup>
 */

// must be run within Dokuwiki
if (!defined('DOKU_INC')) die();

if (!defined('DOKU_LF')) define('DOKU_LF', "\n");
if (!defined('DOKU_TAB')) define('DOKU_TAB', "\t");
if (!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');

require_once DOKU_PLUGIN.'syntax.php';

class syntax_plugin_caption_reference extends DokuWiki_Syntax_Plugin {

    /**
     * Array containing the types of environment supported by the plugin
     */
	private $_types = array('figure','table');

	private $_type = '';
	private $_incaption = false;

	private $_fignum = 1;
	private $_tabnum = 1;
	
    /**
     * return some info
     */
    function getInfo(){
        return confToHash(dirname(__FILE__).'/../plugin.info.txt');
    }

    public function getType() {
        return 'container';
    }

    public function getAllowedTypes() {
        return array('formatting', 'substition', 'disabled', 'container', 'protected');
    }

    public function getPType() {
        return 'normal';
    }

    public function getSort() {
        return 319;
    }


    public function connectTo($mode) {
        $this->Lexer->addSpecialPattern('{{ref>.+?}}',$mode,'plugin_caption_reference');
    }

    public function handle($match, $state, $pos, &$handler){
        if (!(strpos($match,'{{ref>')===false)) {
          	return array($state, substr($match,6,-2));
	    }
        return array();
    }

    public function render($mode, &$renderer, $data) {
        if ($mode == 'xhtml') {

            list($state,$match) = $data;
            
            switch ($state) {
                case DOKU_LEXER_SPECIAL :
                	$renderer->doc .= $_SESSION['caption_labels'][$match];
                    break;
            }
            return true;
        }
        
        if ($mode == 'latex') {

            list($state,$match) = $data;
            
            switch ($state) {
                case DOKU_LEXER_SPECIAL :
                	$renderer->doc .= '\ref{'.$match.'}';
                    break;
            }
            return true;
        }

        // unsupported $mode
        return false;
    }
}

// vim:ts=4:sw=4:et: