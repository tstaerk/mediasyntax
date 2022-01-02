<?php
/**
 * Mediasyntax Plugin, listblock component: Mediawiki style ordered and unordered lists
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Esther Brunner <wikidesign@gmail.com>
 */
class syntax_plugin_mediasyntax_listblock extends DokuWiki_Syntax_Plugin
{

  function getType() { return 'container'; }
  function getPType() { return 'block'; }
  function getSort() { return 50; }

  function getAllowedTypes()
  {
    return array('formatting', 'substition', 'disabled', 'protected');
  }

  function connectTo($mode)
  {
    // a list block starts with a new line starting with one or more * or # signs
    $this->Lexer->addEntryPattern(
      '^[ \t]*[\#\*]+ *',
      $mode,
      'plugin_mediasyntax_listblock'
    );
    // a list block continues as long as the following lines start with one or more * or # signs
    $this->Lexer->addPattern(
      '\n[ \t]*[\#\*\-]+ *',
      'plugin_mediasyntax_listblock'
    );
  }

  function postConnect()
  {
    $this->Lexer->addExitPattern(
      '\n',
      'plugin_mediasyntax_listblock'
    );
  }

  function handle($match, $state, $pos, Doku_Handler $handler)
  {
    switch ($state)
    {
      case DOKU_LEXER_ENTER:
        $ReWriter = new Doku_Handler_Mediasyntax_List($handler->getCallWriter());
        $handler->setCallWriter($ReWriter);
        $handler->addCall('list_open', array($match), $pos);
        break;
      case DOKU_LEXER_EXIT:
        $handler->addCall('list_close', array(), $pos);
        $handler->getCallWriter()->process();
        $ReWriter = $handler->getCallWriter();
        $handler->setCallWriter($ReWriter->getCallWriter());
        break;
      case DOKU_LEXER_MATCHED:
        $handler->addCall('list_item', array($match), $pos);
        break;
      case DOKU_LEXER_UNMATCHED:
        $handler->addCall('cdata', array($match), $pos);
        break;
    }
    return true;
  }

  function render($mode, Doku_Renderer $renderer, $data)
  {
    return true;
  }
}

/* ----- Mediasyntax List Call Writer ----- */

class Doku_Handler_Mediasyntax_List extends \dokuwiki\Parsing\Handler\Lists
{
  function interpretSyntax($match, &$type)
  {
    $pos=strpos($match,"*");
    if ($pos===false) $type="o";
    else $type="u";
    $level = strlen(trim($match));  // Mediasyntax
    return $level;
  }
}

//Setup VIM: ex: et ts=4 enc=utf-8 :
