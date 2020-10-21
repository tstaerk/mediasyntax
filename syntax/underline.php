<?php
/**
 * Mediasyntax Plugin: Mediawiki style underline text
 * <u>..</u> is allowed in mediawiki syntax, see
 * http://svn.wikimedia.org/viewvc/mediawiki/trunk/phase3/includes/Sanitizer.php?view=markup
 * This is a part of the mediasyntax plugin for dokuwiki. It handles the <u> tag.
 * This file must be called underline.php or it will not work.
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Thorsten Staerk <dev@staerk.de>
 */
class syntax_plugin_mediasyntax_underline extends DokuWiki_Syntax_Plugin
{

  function getType() { return 'protected'; }
  function getSort() { return 40; }

  function connectTo($mode)
  {
    $this->Lexer->addEntryPattern(
      '<u>',
      $mode,
      'plugin_mediasyntax_underline'
    );
  }

  function postConnect()
  {
    $this->Lexer->addExitPattern(
      '</u>',
      'plugin_mediasyntax_underline'
    );
  }

  function handle($match, $state, $pos, Doku_Handler $handler)
  {
    if ($state == DOKU_LEXER_UNMATCHED) return array($state,$match);
    if ($state == DOKU_LEXER_ENTER) return array($state,$match);
    if ($state == DOKU_LEXER_EXIT) return array($state,$match);
  }

  function render($mode, Doku_Renderer $renderer, $data)
  // For understanding this see the very valuable code by Christopher Smith on http://www.dokuwiki.org/devel:syntax_plugins
  // $data is always what the function handle returned!
  {
    list($state,$match) = $data;
    if ($mode == 'xhtml')
    {
      if ($state==DOKU_LEXER_ENTER) $renderer->doc .= "<u>";
      if ($state==DOKU_LEXER_UNMATCHED) $renderer->doc .= $match;
      if ($state==DOKU_LEXER_EXIT) $renderer->doc .= "</u>";
    }
    return false;
  }
}

//Setup VIM: ex: et ts=4 enc=utf-8 :
