<?php 
/** 
 * Media Component of mediasyntax plugin: displays media, e.g. an image in a page 
 * 
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html) 
 * @author     Esther Brunner <wikidesign@gmail.com>
 * @author     Christopher Smith <chris@jalakai.co.uk>
 * @author     Gina Häußge, Michael Klier <dokuwiki@chimeric.de>
 * @author     Thorsten Staerk <dev@staerk.de>
 */ 
 
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

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
    // connect an [[Image:foo.bar]] occurrence to the handler "media"
    // goal: e.g. show an image
    {  
        $this->Lexer->addSpecialPattern("\[\[Image:.+?\]\]", $mode, 'media');  
    } 

}
// vim:ts=4:sw=4:et:enc=utf-8:
