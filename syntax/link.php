<?php
/**
 * Mediasyntax Plugin, external link component: Mediawiki style external links
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Thorsten Staerk
 */
class syntax_plugin_mediasyntax_link extends DokuWiki_Syntax_Plugin
{

  function getType(){ return 'protected'; }

  function getSort(){ return 101; }

  function connectTo($mode)
  {
    $this->Lexer->addEntryPattern(
      '\[http(?=.*?\])',
      $mode,
      'plugin_mediasyntax_link'
    );
  }

  function postConnect()
  {
    $this->Lexer->addExitPattern(
      '\]',
      'plugin_mediasyntax_link'
    );
  }

  function handle($match, $state, $pos, Doku_Handler $handler)
  {
    if ($state == DOKU_LEXER_UNMATCHED)
    {
      $target="http".$match;
      $targets=explode(' ',$target);
      $cleartext=preg_replace("/^(.*?) /", "", $match);
      $handler->addCall('externallink', array($targets[0],$cleartext), $pos);
    }
    return true;
  }

  function render($mode, Doku_Renderer $renderer, $data) { return true; }
}

//Setup VIM: ex: et ts=4 enc=utf-8 :
