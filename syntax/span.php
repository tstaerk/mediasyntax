<?php
/**
 * Mediasyntax Plugin, span component
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
class syntax_plugin_mediasyntax_span extends DokuWiki_Syntax_Plugin 
{

  function getType(){ return 'paragraphs'; }
  // If I choose "protected", the span works perfect, but what's within 
  // <span> and </span> will not get dokuwiki-parsed. 
  // If I choose "substitution" it's the other way round.
  
  function getSort(){ return 100; }
  
  function connectTo($mode)
  {
    $this->Lexer->addSpecialPattern(
      '</*span.*?>', // .*? means "zero, one or more occurrences of any character except newline, non-greedy
      $mode,
      'plugin_mediasyntax_span'
    );
  }
    
  function handle($match, $state, $pos, &$handler) 
  // This first gets called with $state=1 and $match is the entryPattern that matched. 
  // Then it (the function handle) gets called with $state=3 and $match is the text
  // between the entryPattern and the exitPattern.
  // Then it gets called with $state=4 and $match is the exitPattern.
  // What this delivers is what is handed over as $data to the function render.
  {
    return array($match, $state, $pos);
  }
 
  function render($mode, &$renderer, $data) 
  {
    // $data is the return value of handle
    // $data[0] is always $match
    // $data[1] is always $state
    // $data[3] is always $pos
    if($mode == 'xhtml')
    {
      $renderer->doc .= "$data[0]";
    }
    return false;
  }
}
     
//Setup VIM: ex: et ts=4 enc=utf-8 :
