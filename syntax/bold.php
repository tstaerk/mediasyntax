<?php
/**
 * Mediasyntax Plugin, bold component: Mediawiki style bold text
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Thorsten Staerk
 */
class syntax_plugin_mediasyntax_bold extends DokuWiki_Syntax_Plugin
{

  function getType() { return 'substition'; }
  function getSort() { return 32; }

  function connectTo($mode)
  {
    $this->Lexer->addSpecialPattern('\'\'\'',$mode,'plugin_mediasyntax_bold');
  }

  function handle($match, $state, $pos, Doku_Handler $handler)
  {
    return array($match, $state, $pos);
  }

  function render($mode, Doku_Renderer $renderer, $data)
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
