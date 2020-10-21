<?php
/**
 * Include Component of mediasyntax plugin: displays a wiki page within another
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Esther Brunner <wikidesign@gmail.com>
 * @author     Christopher Smith <chris@jalakai.co.uk>
 * @author     Gina Häußge, Michael Klier <dokuwiki@chimeric.de>
 * @author     Thorsten Staerk <dev@staerk.de>
 */
class syntax_plugin_mediasyntax_include extends DokuWiki_Syntax_Plugin
{

    var $helper = null;

    function getType() { return 'substition'; }
    function getSort() { return 303; }
    function getPType() { return 'block'; }

    function connectTo($mode)
    {
        $this->Lexer->addSpecialPattern("{{.+?}}", $mode, 'plugin_mediasyntax_include');
    }

    function handle($match, $state, $pos, Doku_Handler $handler)
    {

        $match = substr($match, 2, -2); // strip markup
        list($match, $flags) = explode('&', $match, 2);
        // break the pattern up into its parts
        list($page, $sect) = preg_split('/#/u', $match, 2);
        $mode="page";
        return array($mode, $page, cleanID($sect), explode('&', $flags));
    }

    function render($format, Doku_Renderer $renderer, $data)
    {
        return false;
    }
}
// vim:ts=4:sw=4:et:enc=utf-8:
