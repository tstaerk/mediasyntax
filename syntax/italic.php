<?php
/**
 * Mediasyntax Plugin, preformatted block component: Mediawiki style italic text
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
class syntax_plugin_mediasyntax_italic extends DokuWiki_Syntax_Plugin 
{

  function getType(){ return 'formatting'; }

  function getSort(){ return 40; }
  
  function connectTo($mode)
  {
    $this->Lexer->addEntryPattern(
      '\'\'(?=.*\'\')',
      $mode,
      'plugin_mediasyntax_italic'
    );
  }
  
  function postConnect()
  {
    $this->Lexer->addExitPattern(
      '\'\'',
      'plugin_mediasyntax_italic'
    );
  }
  
  function handle($match, $state, $pos, &$handler)
  {
    $handler->_nestingTag($match, $state, $pos, 'emphasis');
    return true;
  }

  function render($mode, &$renderer, $data)
  // do not flood apache's log with PHP Warning:  render() not implemented
  {
    return true;
  }
}
     
//Setup VIM: ex: et ts=4 enc=utf-8 :
