<?php
/**
 * Mediasyntax Plugin, bold component: Mediawiki style bold text
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
class syntax_plugin_mediasyntax_bold extends DokuWiki_Syntax_Plugin 
{

  function getType() { return 'substition'; }
  function getSort() { return 32; }
 
  function connectTo($mode) 
  {
    $this->Lexer->addSpecialPattern('\'\'\'',$mode,'plugin_mediasyntax_bold');
  }
 
  function handle($match, $state, $pos, &$handler) 
  {
    return array($match, $state, $pos);
  }
 
  function render($mode, &$renderer, $data) 
  {
    GLOBAL $bold;
    if($mode == 'xhtml')
    {
      if (!$bold) $renderer->doc .= "<b>";
      else $renderer->doc .= "</b>";
      if ($bold) $bold=false;
      else $bold=true;
    }
    return false;
  }
}
     
//Setup VIM: ex: et ts=4 enc=utf-8 :
