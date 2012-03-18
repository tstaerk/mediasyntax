<?php
/**
 * Mediasyntax Plugin: Mediawiki style teletyper text
 * 
 * This is a part of the mediasyntax plugin for dokuwiki. It handles the <tt> tag.
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
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
class syntax_plugin_mediasyntax_teletyper extends DokuWiki_Syntax_Plugin 
{

  function getType(){ return 'protected'; }
  function getSort(){ return 40; }
 
  function connectTo($mode)
  {
    $this->Lexer->addEntryPattern(
      '<tt>',
      $mode,
      'plugin_mediasyntax_teletyper'
    );
  }
  
  function postConnect()
  {
    $this->Lexer->addExitPattern(
      '</tt>',
      'plugin_mediasyntax_teletyper'
    );
  }
  
  function handle($match, $state, $pos, &$handler)
  {
    dbglog("entering function handle, match is $match, state is $state, pos is $pos");
    dbglog(gettype($handler));
    if ($state == DOKU_LEXER_UNMATCHED) return array($state,$match);
    if ($state == DOKU_LEXER_ENTER) return array($state,$match);
    if ($state == DOKU_LEXER_EXIT) return array($state,$match);
  }
  
  function render($mode, &$renderer, $data)
  // For understanding this see the very valuable code by Christopher Smith on http://www.dokuwiki.org/devel:syntax_plugins
  // $data is always what the function handle returned!
  {
    dbglog("entering function render, mode is $mode, data is $data, data's type is ".gettype($data));
    dbglog($renderer->doc);
    list($state,$match) = $data;
    dbglog("state is $state, match is $match");
    if ($mode == 'xhtml')
    {
      if ($state==DOKU_LEXER_ENTER) $renderer->doc .= "<tt>";
      if ($state==DOKU_LEXER_UNMATCHED) $renderer->doc .= $match;
      if ($state==DOKU_LEXER_EXIT) $renderer->doc .= "</tt>";
    }
    return false;
  }
}
     
//Setup VIM: ex: et ts=4 enc=utf-8 :
