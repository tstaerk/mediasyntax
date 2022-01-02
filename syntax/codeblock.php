<?php
/**
 * Mediasyntax Plugin, preformatted block component: Mediawiki style preformatted text
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Esther Brunner <wikidesign@gmail.com>
 */
class syntax_plugin_mediasyntax_codeblock extends DokuWiki_Syntax_Plugin
{

  function getType() { return 'formatting'; }

  function getSort()
  {
    /*
      This must be higher prioritized than e.g. listblock.
      If it is not, then the listblock will "steal" the \n at its end-of-line.
      Then, a codeblock directly under a listblock will not trigger the \n .* regex.
    */
    return 9;
  }

  function connectTo($mode)
  {
    $this->Lexer->addEntryPattern   (
      '\n(?= .*?)',
      $mode,
      'plugin_mediasyntax_codeblock');
  }

  function postConnect()
  {
    $this->Lexer->addExitPattern
    (
      '(?=\n[^ ].*?)',
      'plugin_mediasyntax_codeblock'
    );
  }

  function handle($match, $state, $pos, Doku_Handler $handler)
  {
        // $match2 = $match, but cut one blank at the beginning of every line.
        for ($i=1;$i<strlen($match);$i++)
        {
          if ($match[$i-1] == "\n" && $match[$i] == " ") {;}
          else $match2.=$match[$i];
        }
        switch ($state)
        {
            case DOKU_LEXER_ENTER :
                return array($state, $match2);
            case DOKU_LEXER_MATCHED :
                return array($state, $match2);
            case DOKU_LEXER_UNMATCHED :
                return array($state, $match2);
            case DOKU_LEXER_EXIT :
                return array($state, $match2);
            case DOKU_LEXER_SPECIAL :
                //break;
        }
        return false;
/*    if ($state == DOKU_LEXER_UNMATCHED)
    {
       return array($state, $match2);
    }
    return false;
*/
  }

  function render($mode, Doku_Renderer $renderer, $data)
  {
      if($mode == 'xhtml')
      {
          list($state,$match) = $data;
          $match=$data[1];
          $state=$data[0];
          switch ($state)
          {
                case DOKU_LEXER_ENTER :
                    //$renderer->doc .= "enter$match";
                case DOKU_LEXER_UNMATCHED :
                    //$renderer->doc .= "<pre>$match</pre>";
                case DOKU_LEXER_EXIT :
                    if ($match != "") $renderer->doc .= "<pre>$match</pre>";
          }
      }
      return true;
  }
}

//Setup VIM: ex: et ts=4 enc=utf-8 :
