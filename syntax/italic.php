<?php
/**
 * Mediasyntax Plugin, preformatted block component: Mediawiki style italic text
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Thorsten Staerk
 */
class syntax_plugin_mediasyntax_italic extends DokuWiki_Syntax_Plugin
{

  function getType(){ return 'formatting'; }

  function getSort(){ return 40; }

  function connectTo($mode)
  {
    $this->Lexer->addSpecialPattern('\'\'',$mode,'plugin_mediasyntax_italic');
  }

  function handle($match, $state, $pos, Doku_Handler $handler)
  {
    return array($match, $state, $pos);
  }

  function render($mode, Doku_Renderer $renderer, $data)
  {
    GLOBAL $italic;
    if($mode == 'xhtml')
    {
      if (!$italic) $renderer->doc .= "<i>";
      else $renderer->doc .= "</i>";
      if ($italic) $italic=false;
      else $italic=true;
    }
    return false;
  }
}

//Setup VIM: ex: et ts=4 enc=utf-8 :
