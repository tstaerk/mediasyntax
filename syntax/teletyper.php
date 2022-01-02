<?php
/**
 * Mediasyntax Plugin: Mediawiki style teletyper text
 *
 * This is a part of the mediasyntax plugin for dokuwiki. It handles the <tt> tag.
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Thorsten Staerk <dev@staerk.de>
 */
class syntax_plugin_mediasyntax_teletyper extends DokuWiki_Syntax_Plugin
{

  function getType() { return 'protected'; }
  function getSort() { return 40; }

  function connectTo($mode)
  {
    $this->Lexer->addEntryPattern(
      '<tt>',
      $mode,
      'plugin_mediasyntax_teletyper'
    );
  }

  function postConnect()
  {
    $this->Lexer->addExitPattern(
      '</tt>',
      'plugin_mediasyntax_teletyper'
    );
  }

  function handle($match, $state, $pos, Doku_Handler $handler)
  {
    dbglog("entering function ".__FUNCTION__.", match is $match, state is $state, pos is $pos");
    if ($state == DOKU_LEXER_UNMATCHED) return array($state,$match);
    if ($state == DOKU_LEXER_ENTER) return array($state,$match);
    if ($state == DOKU_LEXER_EXIT) return array($state,$match);
  }

  function render($mode, Doku_Renderer $renderer, $data)
  // For understanding this see the very valuable code by Christopher Smith on http://www.dokuwiki.org/devel:syntax_plugins
  // $data is always what the function handle returned!
  {
    dbglog("entering function ".__FUNCTION__.", mode is $mode, data is $data, data's type is ".gettype($data));
    list($state,$match) = $data;
    dbglog("state is $state, match is $match");
    if ($mode == 'xhtml')
    {
      if ($state==DOKU_LEXER_ENTER) $renderer->doc .= "<tt>";
      if ($state==DOKU_LEXER_UNMATCHED) $renderer->doc .= $match;
      if ($state==DOKU_LEXER_EXIT) $renderer->doc .= "</tt>";
    }
    return false;
  }
}

//Setup VIM: ex: et ts=4 enc=utf-8 :
