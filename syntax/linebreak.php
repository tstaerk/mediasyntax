<?php
/**
 * Mediasyntax Plugin, linebreak component: Inserts a line break
 * based on Linebreak Plugin http://wiki.splitbrain.org/plugin:linebreak
 * 
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Christopher Smith <chris@jalakai.co.uk>
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
class syntax_plugin_mediasyntax_linebreak extends DokuWiki_Syntax_Plugin {
 
  function getInfo(){
    return array(
      'author' => 'Thorsten Stärk, Gina Häußge, Michael Klier, Christopher Smith',
      'email'  => 'dev@staerk.de',
      'date'   => '2010-03-29',
      'name'   => 'Mediasyntax Plugin (linebreak component)',
      'desc'   => 'Provide a line break for a new line in the raw wiki data',
      'url'    => 'http://wiki.splitbrain.org/plugin:mediasyntax',
    );
  }

  function getType() { return 'substition'; }
  function getSort() { return 100; }
  
  function connectTo($mode) {
    $this->Lexer->addSpecialPattern(
      '(?<!^|\n)\n(?!\n|>)',
      $mode,
      'plugin_mediasyntax_linebreak'
    ); 
  }

  function handle($match, $state, $pos, &$handler){ 

    if ($match == "\n") return true;
    return false;
  }

  function render($mode, &$renderer, $data){
  
    if($mode == 'xhtml'){
        if ($data) $renderer->doc .= "<br />";
        return true;
    }
    return false;
  }
}
