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
class syntax_plugin_mediasyntax_codeblock extends DokuWiki_Syntax_Plugin 
{
  function getInfo()
  {
    return array(
      'author' => 'Thorsten Stärk, Gina Häußge, Michael Klier, Esther Brunner',
      'email'  => 'dev@staerk.de',
      'date'   => '2010-03-29',
      'name'   => 'Mediasyntax Plugin, code-formatted block component',
      'desc'   => 'Mediasyntax style preformatted text',
      'url'    => 'http://wiki.splitbrain.org/plugin:mediasyntax',
    );
  }

  function getType(){ return 'protected'; }
  function getPType(){ return 'block'; }
  function getSort(){ return 9; }
  
  function connectTo($mode)
  {
    $this->Lexer->addEntryPattern(
      '\n(?= .*?)',
      $mode,
      'plugin_mediasyntax_codeblock'
    );
  }
  
  function postConnect()
  {
    $this->Lexer->addExitPattern(
      '\n(?=[^ ].*?)',
      'plugin_mediasyntax_codeblock'
    );
  }
  
  function handle($match, $state, $pos, &$handler)
  {
    if ($state == DOKU_LEXER_UNMATCHED)
    {
       // $match2 = $match, but cut one blank at the beginning of every line.
       for ($i=0;$i<strlen($match);$i++) 
       {
         if ($i==0);
         elseif ($match[$i-1] == "\n" && $match[$i] == " ") then ;
         else $match2.=$match[$i];
       }
       $handler->_addCall('preformatted', array($match2), $pos);
    }
    return true;
  }
  
  function render($mode, &$renderer, $data)
  {
    return true;
  }
}
     
//Setup VIM: ex: et ts=4 enc=utf-8 :
