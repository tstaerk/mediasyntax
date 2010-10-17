<?php
/**
* Mediasyntax Plugin, nonitalic component: Mediawiki style //-string
*
* @license GPL 2 (http://www.gnu.org/licenses/gpl.html)
* @author Thorsten Staerk <dev@staerk.de>
*
* This file exists so the mediasyntax plugin does not use the // string as markup for italic
*/
 
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'syntax.php');
 
/**
* All DokuWiki plugins to extend the parser/rendering mechanism
* need to inherit from this class
*/
class syntax_plugin_mediasyntax_nonitalic extends DokuWiki_Syntax_Plugin
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
    return 70;
  }
  
  function getAllowedTypes()
  {
    return array('formatting', 'substition', 'disabled', 'protected');
  }
  
  function connectTo($mode)
  {
    $this->Lexer->addEntryPattern(
      '//(?=[^\x00]*[^:])',
      $mode,
      'plugin_mediasyntax_nonitalic'
    );
  }
  
  function postConnect()
  {
    $this->Lexer->addExitPattern(
      '//',
      'plugin_mediasyntax_nonitalic'
    );
  }
  
  function handle($match, $state, $pos, &$handler)
  {
    if ($state == DOKU_LEXER_UNMATCHED)
    {
      $handler->_addCall('unformatted', array($match), $pos);
    }
    return true;
  }
  
  function render($mode, &$renderer, $data)
  {
    GLOBAL $done;
    if($mode == 'xhtml')
    {
      if (!$done) $renderer->doc .= "//";
      else $renderer->doc.="/";
      $done=true;
    }
    return true;
  }
}
