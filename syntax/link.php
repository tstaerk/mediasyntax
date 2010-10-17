<?php
/**
 * Mediasyntax Plugin, external link component: Mediawiki style external links
 * 
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Thorsten Staerk
 */
 
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
 
/**
 * All DokuWiki plugins to extend the parser/rendering mechanism
 * need to inherit from this class
 */
class syntax_plugin_mediasyntax_link extends DokuWiki_Syntax_Plugin 
{

  function getType(){ return 'protected'; }

  function getSort(){ return 101; }
  
  function connectTo($mode)
  {
    $this->Lexer->addEntryPattern(
      '\[http(?=.*?\])',
      $mode,
      'plugin_mediasyntax_link'
    );
  }
  
  function postConnect()
  {
    $this->Lexer->addExitPattern(
      '\]',
      'plugin_mediasyntax_link'
    );
  }
  
  function handle($match, $state, $pos, &$handler)
  {
    if ($state == DOKU_LEXER_UNMATCHED)
    {
      $target="http".$match;
      $targets=explode(' ',$target);
      $cleartext=preg_replace("/^(.*?) /", "", $match);
      $handler->_addCall('externallink', array($targets[0],$cleartext), $pos);
    }
    return true;
  }
  
  function render($mode, &$renderer, $data) { return true; }
}
     
//Setup VIM: ex: et ts=4 enc=utf-8 :
