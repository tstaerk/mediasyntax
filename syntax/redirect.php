<?php
/**
 * Mediasyntax Plugin, redirect component: Mediawiki style redirects
 * based on the cool goto plugin by Allen Ormond
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
class syntax_plugin_mediasyntax_redirect extends DokuWiki_Syntax_Plugin 
{

  function getType(){ return 'formatting'; }
  function getSort(){ return 58; }
  
  function connectTo($mode)
  {
    $this->Lexer->addEntryPattern(
      '^[\#]*REDIRECT[ ]+\[\[',
      $mode,
      'plugin_mediasyntax_redirect'
    );
    $this->Lexer->addEntryPattern(
      '^[\#]*redirect[ ]+\[\[',
      $mode,
      'plugin_mediasyntax_redirect'
    );
    $this->Lexer->addEntryPattern(
      '^[\#]*reDirect[ ]+\[\[',
      $mode,
      'plugin_mediasyntax_redirect'
    );
  }
  
  function postConnect()
  {
    $this->Lexer->addExitPattern(
      '\]\]',
      'plugin_mediasyntax_redirect'
    );
  }
  
  function handle($match, $state, $pos, &$handler)
  {
    if ($state == DOKU_LEXER_UNMATCHED)
    {
      if ($pos==13) return $match; // position must be at the beginning of the page
    }
  }
  
  function render($mode, &$renderer, $data) 
  {
    if (strlen($data)>0)
    {
      $renderer->doc = "You will be redirected in two seconds to $data";
      $renderer->doc .= '<script>url="'.wl($data).'";setTimeout("location.href=url",'.(2000).');</script>';
    }
    return true; 
  }
}
     
//Setup VIM: ex: et ts=4 enc=utf-8 :
