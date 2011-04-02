<?php 
/** 
 * Media Component of mediasyntax plugin: displays a wiki page within another 
 * 
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html) 
 * @author     Esther Brunner <wikidesign@gmail.com>
 * @author     Christopher Smith <chris@jalakai.co.uk>
 * @author     Gina Häußge, Michael Klier <dokuwiki@chimeric.de>
 * @author     Thorsten Staerk <dev@staerk.de>
 */ 
 
if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../../').'/'); 
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/'); 
require_once(DOKU_PLUGIN.'syntax.php'); 
  
/** 
 * All DokuWiki plugins to extend the parser/rendering mechanism 
 * need to inherit from this class 
 */ 
class syntax_plugin_mediasyntax_media extends DokuWiki_Syntax_Plugin 
{ 

    var $helper = null;

    function getType() { return 'substition'; }
    function getSort() { return 99; }
    function getPType() { return 'block'; }

    function connectTo($mode) 
    {  
        $this->Lexer->addSpecialPattern("\[\[Image:.+?\]\]", $mode, 'media');  
    } 

    function handle($match, $state, $pos, &$handler) 
    {

        $match = substr($match, 2, -2); // strip markup
        list($match, $flags) = explode('&', $match, 2);
        // break the pattern up into its parts 
        list($page, $sect) = preg_split('/#/u', $match, 2); 
        $mode="page";
        return array($mode, $page, cleanID($sect), explode('&', $flags)); 
    }

    function render($format, &$renderer, $data) 
    {
        return false;
    }
}
// vim:ts=4:sw=4:et:enc=utf-8:
