<?php
/**
 * Media Component of mediasyntax plugin: displays media, e.g. an image in a page
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Esther Brunner <wikidesign@gmail.com>
 * @author     Christopher Smith <chris@jalakai.co.uk>
 * @author     Gina Häußge, Michael Klier <dokuwiki@chimeric.de>
 * @author     Thorsten Staerk <dev@staerk.de>
 */

// to show an image we need to insert an html syntax like
// <img src="/dokuwiki/lib/exe/fetch.php?w=200&amp;tok=234ffe&amp;media=test.jpg" class="media" alt="" width="200" />
class syntax_plugin_mediasyntax_media extends DokuWiki_Syntax_Plugin
{
  function getType() { return 'substition'; }

  function getSort() { return 99; }

  function getPType() { return 'block'; }

  function connectTo($mode)
  {
    $this->Lexer->addSpecialPattern("\[\[Image:.+?\]\]", $mode, 'plugin_mediasyntax_media');
    $this->Lexer->addSpecialPattern("\[\[File:.+?\]\]", $mode, 'plugin_mediasyntax_media');
  }

  function handle($match, $state, $pos, Doku_Handler $handler)
  // This first gets called with $state=1 and $match is the entryPattern that matched.
  // Then it (the function handle) gets called with $state=3 and $match is the text
  // between the entryPattern and the exitPattern.
  // Then it gets called with $state=4 and $match is the exitPattern.
  // What this delivers is what is handed over as $data to the function render.
  {
    return array($match, $state, $pos);
  }

  function render($mode, Doku_Renderer $renderer, $data)
  // $data is the return value of handle
  // $data[0] is always $match
  // $data[1] is always $state
  // $data[3] is always $pos
  {
    $match=$data[0];  // e.g. [[Image:foo.png|50px]]
    $end=preg_replace("/^\[\[Image:|^\[\[File:/","",$match);  // e.g. foo.png|50px]]
    $start=preg_replace("/\]\]$/","",$end);  // e.g.. foo.png|50px
    $filename=preg_replace("/\|.*$/","",$start);  // e.g. foo.png
    $extension=preg_replace("/.*\./","",$filename); // e.g. png
    $pipe=preg_replace("/.*\|/","",$start);  // e.g. 50px

    $img_exts = array("png", "jpg", "jpeg", "gif");

    if($mode == 'xhtml')
    {
      if (in_array(strtolower($extension), $img_exts))
      {
        $renderer->doc.= '<img src="'.DOKU_BASE.'lib/exe/fetch.php?media='.$filename.'" width='.$pipe.' />';
      }
      else
      {
        $renderer->doc.= '<a href="'.DOKU_BASE.'lib/exe/fetch.php?media='.$filename.'">File:'.$filename.'</a>';
      }
    }
    return false;
  }

}
// vim:ts=4:sw=4:et:enc=utf-8:
