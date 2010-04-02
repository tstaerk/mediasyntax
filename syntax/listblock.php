<?php
/**
 * Mediasyntax Plugin, listblock component: Mediawiki style ordered and unordered lists
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
class syntax_plugin_mediasyntax_listblock extends DokuWiki_Syntax_Plugin {
 
  function getInfo(){
    return array(
      'author' => 'Thorsten Stärk, Gina Häußge, Michael Klier, Esther Brunner',
      'email'  => 'dev@staerk.de',
      'date'   => '2010-03-29',
      'name'   => 'Mediasyntax Plugin, listblock component',
      'desc'   => 'Mediawiki style ordered and unorderered lists',
      'url'    => 'http://wiki.splitbrain.org/plugin:mediasyntax',
    );
  }

  function getType(){ return 'container'; }
  function getPType(){ return 'block'; }
  function getSort(){ return 9; }
  
  function getAllowedTypes(){
    return array('formatting', 'substition', 'disabled', 'protected');
  }
  
  function connectTo($mode){
    $this->Lexer->addEntryPattern(
      '\n[ \t]*[\#\*](?!\*) *',
      $mode,
      'plugin_mediasyntax_listblock'
    );
    $this->Lexer->addPattern(
      '\n[ \t]*[\#\*\-]+',
      'plugin_mediasyntax_listblock'
    );
  }
  
  function postConnect(){
    $this->Lexer->addExitPattern(
      '\n',
      'plugin_mediasyntax_listblock'
    );
  }
  
  function handle($match, $state, $pos, &$handler){
    switch ($state){
      case DOKU_LEXER_ENTER:
        $ReWriter = & new Doku_Handler_List($handler->CallWriter);
        $ReWriter = & new Doku_Handler_Mediasyntax_List($handler->CallWriter);
        $handler->CallWriter = & $ReWriter;
        $handler->_addCall('list_open', array($match), $pos);
        break;
      case DOKU_LEXER_EXIT:
        $handler->_addCall('list_close', array(), $pos);
        $handler->CallWriter->process();
        $ReWriter = & $handler->CallWriter;
        $handler->CallWriter = & $ReWriter->CallWriter;
        break;
      case DOKU_LEXER_MATCHED:
        $handler->_addCall('list_item', array($match), $pos);
        break;
      case DOKU_LEXER_UNMATCHED:
        $handler->_addCall('cdata', array($match), $pos);
        break;
    }
    return true;
  }
  
  function render($mode, &$renderer, $data){
    return true;
  }
}

/* ----- Mediasyntax List Call Writer ----- */

class Doku_Handler_Mediasyntax_List extends Doku_Handler_List {

  function interpretSyntax($match, &$type){
    if (substr($match,-1) == '*') $type = 'u';
    else $type = 'u';
    $level = strlen(trim($match));  // Mediasyntax
    if ($level <= 1){
      $c = count(explode('  ',str_replace("\t",'  ',$match)));
      if ($c > $level) $level = $c; // DokuWiki
    }
    return $level;
  }
}
 
//Setup VIM: ex: et ts=4 enc=utf-8 :
