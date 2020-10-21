<?php
/**
* Mediasyntax Plugin, nonbold component: Mediawiki style **-string
*
* @license GPL 2 (http://www.gnu.org/licenses/gpl.html)
* @author Thorsten Staerk <dev@staerk.de>, http://www.staerk.de/thorsten/mediasyntax
*
* This file exists so the mediasyntax plugin does not use the ** string as markup for bold
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

  function handle($match, $state, $pos, Doku_Handler $handler)
  {
    return array($match, $state, $pos);
  }

  function render($mode, Doku_Renderer $renderer, $data)
  {
    if($mode == 'xhtml')
    {
      $renderer->doc .= "**";
    }
    return false;
  }
}
