<?php
/**
* Mediasyntax Plugin, nonitalic component: Mediawiki style //-string
*
* @license GPL 2 (http://www.gnu.org/licenses/gpl.html)
* @author Thorsten Staerk <dev@staerk.de>
*
* This file exists so the mediasyntax plugin does not use the // string as markup for italic
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
    // to overwrite dokuwiki's default, getSort must deliver a lower value
    return 70;
  }

  function getAllowedTypes()
  {
    return array('formatting', 'substition', 'disabled', 'protected');
  }

  function connectTo($mode)
  {
    $this->Lexer->addSpecialPattern('\/\/',$mode,'plugin_mediasyntax_nonitalic');
  }

  function handle($match, $state, $pos, Doku_Handler $handler)
  {
    return array($match, $state, $pos);
  }

  function render($mode, Doku_Renderer $renderer, $data)
  {
    // This is valid globally, not only for xhtml or so.
    $renderer->doc .= "//";
    return false;
  }
}
