<?php
/**
* Mediasyntax Plugin, nonunderline component: Mediawiki style //-string
*
* @license GPL 2 (http://www.gnu.org/licenses/gpl.html)
* @author Thorsten Staerk <dev@staerk.de>
*
* This file exists so the mediasyntax plugin does not use the __ string as markup for italic
*/
 
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
 
/**
* All DokuWiki plugins to extend the parser/rendering mechanism
* need to inherit from this class
*/
class syntax_plugin_mediasyntax_nonunderline extends DokuWiki_Syntax_Plugin
{

  function getType() 
  {
    // source: http://github.com/splitbrain/dokuwiki/blob/master/inc/parser/parser.php#L12
    return 'formatting';
  }

  function getSort()
  {
    // to overwrite dokuwiki's default, getSort must deliver a lower value
    return 70;
  }
  
  function getAllowedTypes()
  {
    return array('formatting', 'substition', 'disabled', 'protected');
  }
  
  function connectTo($mode)
  {
    $this->Lexer->addSpecialPattern('__',$mode,'plugin_mediasyntax_nonunderline');
  }

  function handle($match, $state, $pos, Doku_Handler $handler)
  {
    return array($match, $state, $pos);
  }
  
  function render($mode, Doku_Renderer $renderer, $data)
  {
    // This is valid globally, not only for xhtml or so.
    $renderer->doc .= "__";
    return false;
  }
}
