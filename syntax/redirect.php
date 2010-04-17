<?php
/**
 * Mediasyntax Plugin, redirect component: Mediawiki style redirects
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
  function getInfo()
  {
    return array(
      'author' => 'Thorsten Stärk',
      'email'  => 'dev@staerk.de',
      'date'   => '2010-04-15',
      'name'   => 'Mediasyntax Plugin, redirect component',
      'desc'   => 'Mediasyntax style redirects',
      'url'    => 'http://wiki.splitbrain.org/plugin:mediasyntax',
    );
  }

  function getType(){ return 'protected'; }
  function getPType(){ return 'block'; }
  function getSort(){ return 8; }
  
  function connectTo($mode)
  {
    $this->Lexer->addEntryPattern(
      '^[\#]*REDIRECT[ ]+\[\[',
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
    if ($state == DOKU_LEXER_MATCHED)
    {
      $target="this is handle".$match;
      $handler->_addCall('preformatted', array($target), $pos);
    }
    
    return array($match);
  }
  
  function render($mode, &$renderer, $data) 
  {
    $renderer->doc .= "data is >".$data[0]."<";
    if (strlen($data[0])>3)
      $renderer->doc .= '<script>url="'.wl($data[0]).'";setTimeout("location.href=url",'.(5000).');</script>';
    return true; 
  }
}
     
//Setup VIM: ex: et ts=4 enc=utf-8 :
