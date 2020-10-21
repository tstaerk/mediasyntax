<?php
/**
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Thorsten Staerk <dokuwiki@staerk.de>, Esther Brunner <wikidesign@gmail.com>
 */



class action_plugin_mediasyntax extends DokuWiki_Action_Plugin
{
    var $supportedModes = array('xhtml', 'i');
    var $helper = null;

    function action_plugin_mediasyntax()
    {
        $this->helper = plugin_load('helper', 'mediasyntax');
    }

    /**
     * register the eventhandlers
     */
    function register(Doku_Event_Handler $controller)
    {
        $controller->register_hook('TOOLBAR_DEFINE',
                          'AFTER',
                          $this,
                          'define_toolbar',
                           array());
        $controller->register_hook('PARSER_CACHE_USE','BEFORE', $this, '_cache_prepare');
        $controller->register_hook('HTML_EDITFORM_OUTPUT', 'BEFORE', $this, 'handle_form');
        $controller->register_hook('HTML_CONFLICTFORM_OUTPUT', 'BEFORE', $this, 'handle_form');
        $controller->register_hook('HTML_DRAFTFORM_OUTPUT', 'BEFORE', $this, 'handle_form');
        $controller->register_hook('ACTION_SHOW_REDIRECT', 'BEFORE', $this, 'handle_redirect');
        $controller->register_hook('PARSER_HANDLER_DONE', 'BEFORE', $this, 'handle_parser');
        $controller->register_hook('PARSER_METADATA_RENDER', 'AFTER', $this, 'handle_metadata');
    }

    /**
     * Used for debugging purposes only
     */
    function handle_metadata(Doku_Event $event, $param)
    {
	dbglog("entering function ".__FUNCTION__);
        //$event->data contains things like creator, last change etc.
    }

    /**
     * Supplies the current section level to the include syntax plugin
     *
     * @author Michael Klier <chi@chimeric.de>
     */
    function handle_parser(Doku_Event $event, $param)
    {
        global $ID;

        // check for stored toplevel ID in helper plugin
        // if it's missing lets see if we have to do anything at all
        if(!isset($this->helper->toplevel_id))
        {
            $ins =& $event->data->calls;
            $num = count($ins);
            for($i=0; $i<$num; $i++)
            {
                if(($ins[$i][0] == 'plugin'))
                {
                    switch($ins[$i][1][0])
                    {
                        case 'mediasyntax_include':
                            if(!isset($this->helper->toplevel_id))
                            {
                                $this->helper->toplevel_id = $ID;
                            }
                            $this->helper->parse_instructions($ID, $ins);
                            break;
                        // some plugins already close open sections
                        // so we need to make sure we don't close them twice
                        case 'box':
                            $this->helper->sec_close = false;
                            break;
                    }
                }
            }
        }
    }

    /**
     * Add a hidden input to the form to preserve the redirect_id
     */
    function handle_form(Doku_Event $event, $param)
    {
        if (array_key_exists('redirect_id', $_REQUEST))
        {
            $event->data->addHidden('redirect_id', cleanID($_REQUEST['redirect_id']));
        }
    }

    /**
     * Modify the data for the redirect when there is a redirect_id set
     */
    function handle_redirect(Doku_Event $event, $param)
    {
        if (array_key_exists('redirect_id', $_REQUEST))
        {
            $event->data['id'] = cleanID($_REQUEST['redirect_id']);
            $event->data['title'] = '';
        }
    }

    /**
     * prepare the cache object for default _useCache action
     */
    function _cache_prepare(Doku_Event $event, $param)
    {
        global $ID;
        global $INFO;
        global $conf;

        $cache =& $event->data;

        // we're only interested in instructions of the current page
        // without the ID check we'd get the cache objects for included pages as well
        if(!isset($cache->page) || ($cache->page != $ID)) return;
        if(!isset($cache->mode) || !in_array($cache->mode, $this->supportedModes)) return;

        if(!empty($INFO['userinfo']))
        {
            $include_key = $INFO['userinfo']['name'] . '|' . implode('|', $INFO['userinfo']['grps']);
        }
        else
        {
            $include_key = '@ALL';
        }

        $depends = p_get_metadata($ID, 'plugin_mediasyntax');

        if($conf['allowdebug'])
        {
            dbglog('---- PLUGIN INCLUDE INCLUDE KEY START ----');
            dbglog($include_key);
            dbglog('---- PLUGIN INCLUDE INCLUDE KEY END ----');
            dbglog('---- PLUGIN INCLUDE CACHE DEPENDS START ----');
            dbglog($depends);
            dbglog('---- PLUGIN INCLUDE CACHE DEPENDS END ----');
        }

        $cache->depends['purge'] = true; // kill some performance
        if(is_array($depends))
        {
            $pages = array();
            if(!isset($depends['keys'][$include_key]))
            {
                $cache->depends['purge'] = true; // include key not set - request purge
            }
            else
            {
                $pages = $depends['pages'];
            }
        }
        else
        {
            // nothing to do for us
            return;
        }

        // add plugin.info.txt to depends for nicer upgrades
        $cache->depends['files'][] = dirname(__FILE__) . '/plugin.info.txt';

        $key = '';
        foreach($pages as $page)
        {
            $page = cleanID($this->helper->_apply_macro($page));
            resolve_pageid(getNS($ID), $page, $exists);
            $file = wikiFN($page);
            if(!in_array($cache->depends['files'], array($file)) && @file_exists($file))
            {
                $cache->depends['files'][] = $file;
                $key .= '#' . $page . '|ACL' . auth_quickaclcheck($page);
            }
        }

        // empty $key implies no includes, so nothing to do
        if(empty($key)) return;

        // mark the cache as being modified by the include plugin
        $cache->include = true;

        // set new cache key & cache name
        // now also dependent on included page ids and their ACL_READ status
        $cache->key .= $key;
        $cache->cache = getCacheName($cache->key, $cache->ext);
    }

    /**
     * modifiy the toolbar JS defines
     *
     * @author  Esther Brunner  <wikidesign@gmail.com>
     */
    function define_toolbar(Doku_Event $event, $param)
    {
        dbglog("entering function ".__FUNCTION__);
        dbglog("event->data follows");
        dbglog($event->data);
        dbglog("event->data ends");
        array_splice($event->data, 5,3);
        $c = count($event->data);
        for ($i = 0; $i <= $c; $i++)
        {
            if ($event->data[$i]['icon'] == 'ol.png')
            {
                $event->data[$i]['open']  = "# ";
            }
            elseif ($event->data[$i]['icon'] == 'h.png')
            {
                $event->data[$i]['list'][0]['open'] = "= ";
                $event->data[$i]['list'][0]['close']  = " =\\n";
                $event->data[$i]['list'][1]['open'] = "== ";
                $event->data[$i]['list'][1]['close']  = " ==\\n";
            }
            elseif ($event->data[$i]['icon'] == 'ul.png')
            {
                $event->data[$i]['open']  = "* ";
            }
            elseif ($event->data[$i]['icon'] == 'ol.png')
            {
                $event->data[$i]['open']  = "# ";
            }
            elseif ($event->data[$i]['icon'] == 'italic.png')
            {
                $event->data[$i]['open']  = "''";
                $event->data[$i]['close']  = "''";
            }
            elseif ($event->data[$i]['icon'] == 'mono.png')
            {
                $event->data[$i]['open']  = "<tt>";
                $event->data[$i]['close']  = "</tt>";
                $event->data[$i]['block']  = 1;
            }
            elseif ($event->data[$i]['icon'] == 'bold.png')
            {
                $event->data[$i]['open']  = "'''";
                $event->data[$i]['close']  = "'''";
            }
       }
       return true;
    }
}

//Setup VIM: ex: et ts=4 enc=utf-8 :
