<?php
/**
 * Mediasyntax Plugin, preformatted block component: Mediawiki style preformatted text
 * 
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Esther Brunner <wikidesign@gmail.com>
 */
 
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
 
/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_mediasyntax_preblock extends DokuWiki_Syntax_Plugin 
{

  function getType(){ return 'protected'; }
  function getPType(){ return 'block'; }
  function getSort(){ return 101; }
  
  function connectTo($mode)
  {
    $this->Lexer->addEntryPattern(
      '<pre>',
      $mode,
      'plugin_mediasyntax_preblock'
    );
  }
  
  function postConnect()
  {
    $this->Lexer->addExitPattern(
      '</pre>',
      'plugin_mediasyntax_preblock'
    );
  }
  
  function handle($match, $state, $pos, &$handler)
  {
    if ($state == DOKU_LEXER_UNMATCHED)
    {
      $handler->_addCall('preformatted', array($match), $pos);
    }
    return true;
  }
  
  function render($mode, &$renderer, $data)
  {
    return true;
  }
}
     
//Setup VIM: ex: et ts=4 enc=utf-8 :
