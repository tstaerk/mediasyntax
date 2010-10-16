<?php
/**
 * Mediasyntax Plugin, nonitalic component: Mediawiki style //-string 
 * 
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Thorsten Staerk <dev@staerk.de>
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
  function getInfo()
  {
    return array(
      'author' => 'Thorsten Stärk, Gina Häußge, Michael Klier, Esther Brunner',
      'email'  => 'dev@staerk.de',
      'date'   => '2010-10-16',
      'name'   => 'Mediasyntax Plugin, non italic component',
      'desc'   => 'Mediawiki style handling of // string',
      'url'    => 'http://wiki.splitbrain.org/plugin:mediasyntax',
    );
  }

  function getType() { return 'container'; }
  function getPType() { return 'block'; }
  function getSort() 
  { 
  // emphasis has a sort of 80. Set this to 70 and it will be active.
  // Set it to 90 and it will not be active.
    return 90; 
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
    return true;
  }
}


//Setup VIM: ex: et ts=4 enc=utf-8 :
