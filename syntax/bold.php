<?php
/**
 * Mediasyntax Plugin, bold component: Mediawiki style bold text
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
class syntax_plugin_mediasyntax_bold extends DokuWiki_Syntax_Plugin {
 
  function getInfo(){
    return array(
      'author' => 'Thorsten Stärk, Gina Häußge, Michael Klier, Esther Brunner',
      'email'  => 'dev@staerk.de',
      'date'   => '2010-03-29',
      'name'   => 'Mediasyntax Plugin, bold component',
      'desc'   => 'Mediasyntax style bold text',
      'url'    => 'http://wiki.splitbrain.org/plugin:mediasyntax',
    );
  }

  function getType(){ return 'protected'; }
  function getPType(){ return 'block'; }
  function getSort()
  { 
    return 99; // returning a value >= 100 makes it not work.
  }
  
  function connectTo($mode){
    $this->Lexer->addEntryPattern(
      '(?=\'\'\'.*?)',
      $mode,
      'plugin_mediasyntax_bold'
    );
  }
  
  function postConnect(){
    $this->Lexer->addExitPattern(
      '\n(?=[^ ].*?)',
      'plugin_mediasyntax_bold'
    );
  }
  
  function handle($match, $state, $pos, &$handler){
    if ($state == DOKU_LEXER_UNMATCHED){
      $handler->_addCall('bold', array($match), $pos);
    }
    return $match;
  }
  
  function render($mode, &$renderer, $data){
    $renderer->doc .= "<b>".substr($data,3,strlen($data)-6);
    return true;
  }
}
     
//Setup VIM: ex: et ts=4 enc=utf-8 :
