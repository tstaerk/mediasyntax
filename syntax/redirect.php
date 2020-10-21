<?php
/**
 * Mediasyntax Plugin, redirect component: Mediawiki style redirects
 * based on the cool goto plugin by Allen Ormond
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Thorsten Staerk
 */
class syntax_plugin_mediasyntax_redirect extends DokuWiki_Syntax_Plugin
{

  function getType(){ return 'formatting'; }
  function getSort(){ return 58; }

  function connectTo($mode)
  {
    $this->Lexer->addEntryPattern(
      '^[\#]*REDIRECT[ ]+\[\[',
      $mode,
      'plugin_mediasyntax_redirect'
    );
    $this->Lexer->addEntryPattern(
      '^[\#]*redirect[ ]+\[\[',
      $mode,
      'plugin_mediasyntax_redirect'
    );
    $this->Lexer->addEntryPattern(
      '^[\#]*reDirect[ ]+\[\[',
      $mode,
      'plugin_mediasyntax_redirect'
    );
  }

  function postConnect()
  {
    $this->Lexer->addExitPattern(
      '\]\]',
      'plugin_mediasyntax_redirect'
    );
  }

  function handle($match, $state, $pos, Doku_Handler $handler)
  {
    if ($state == DOKU_LEXER_UNMATCHED)
    {
      if ($pos==13) return $match; // position must be at the beginning of the page
    }
  }

  function render($mode, Doku_Renderer $renderer, $data)
  {
    if (strlen($data)>0)
    {
      $delay = $this->getConf('redirectPauseTime',2);
      $renderer->doc = 'You will be redirected in '.$delay.' seconds to <a href="' . wl($data) . '">'.$data.'</a>';
      $renderer->doc .= '<script>url="'.wl($data).'";setTimeout("location.href=url",'.($delay*1000).');</script>';
    }
    return true;
  }
}

//Setup VIM: ex: et ts=4 enc=utf-8 :
