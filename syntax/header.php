<?php
/**
 * Mediasyntax Plugin, header component: Mediawiki style headers
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Esther Brunner <wikidesign@gmail.com>
 */
class syntax_plugin_mediasyntax_header extends DokuWiki_Syntax_Plugin
{

    function getType()  { return 'container'; }
    function getPType() { return 'block'; }
    function getSort()  { return 49; }

    function getAllowedTypes()
    {
        return array('formatting', 'substition', 'disabled', 'protected');
    }

    function preConnect()
    {
        $this->Lexer->addSpecialPattern(
            '(?m)^[ \t]*=+[^\n]+=+[ \t]*$',
            'base',
            'plugin_mediasyntax_header'
        );
    }

    function handle($match, $state, $pos, Doku_Handler $handler)
    {
        global $conf;

        // get level and title
        $title = trim($match);
        $level = strspn($title, '=');
        if ($level < 1) $level = 1;
        elseif ($level > 5) $level = 5;
        $title = trim($title, '=');
        $title = trim($title);

        if ($handler->getStatus('section')) $handler->addCall('section_close', array(), $pos);

        if ($level <= $conf['maxseclevel'])
        {
            $handler->setStatus('section_edit_start', $pos);
            $handler->setStatus('section_edit_level', $level);
            $handler->setStatus('section_edit_title', $title);
        }

        $handler->addCall('header', array($title, $level, $pos), $pos);

        $handler->addCall('section_open', array($level), $pos);
        $handler->setStatus('section', true);
        return true;
    }

    function render($mode, Doku_Renderer $renderer, $data)
    {
        return true;
    }
}

//Setup VIM: ex: et ts=4 enc=utf-8 :
