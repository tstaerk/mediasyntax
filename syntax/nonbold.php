<?php
/**
* Mediasyntax Plugin, nonbold component: Mediawiki style **-string
*
* @license GPL 2 (http://www.gnu.org/licenses/gpl.html)
* @author Thorsten Staerk <dev@staerk.de>, http://www.staerk.de/thorsten/mediasyntax
*
* This file exists so the mediasyntax plugin does not use the ** string as markup for bold
*/
 
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
 
/**
* All DokuWiki plugins to extend the parser/rendering mechanism
* need to inherit from this class
*/
class syntax_plugin_mediasyntax_nonbold extends DokuWiki_Syntax_Plugin
{

  function getType() 
  {
  // source: http://github.com/splitbrain/dokuwiki/blob/master/inc/parser/parser.php#L12
    return 'formatting';
  }

  function getSort()
  {
  // emphasis has a sort of 80. Set this to 70 and it will be active.
  // Set it to 90 and it will not be active.
    return 10;
  }
  
  function getAllowedTypes()
  {
    return array('formatting', 'substition', 'disabled', 'protected');
  }
  
  function connectTo($mode)
  {
    $this->Lexer->addSpecialPattern('\*\*',$mode,'plugin_mediasyntax_nonbold');
  }

  function handle($match, $state, $pos, &$handler)
  {
    return array($match, $state, $pos);
  }
  
  function render($mode, &$renderer, $data)
  {
    if($mode == 'xhtml')
    {
      $renderer->doc .= "**";
    }
    return false;
  }
}
