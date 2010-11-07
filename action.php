<?php
/**
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Thorsten Staerk <dokuwiki@staerk.de>, Esther Brunner <wikidesign@gmail.com>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'action.php');

class action_plugin_mediasyntax extends DokuWiki_Action_Plugin 
{

  /**
   * register the eventhandlers
   */
  function register(&$contr)
  {
    $contr->register_hook('TOOLBAR_DEFINE',
                          'AFTER',
                          $this,
                          'define_toolbar',
                           array());
  }

  /**
   * modifiy the toolbar JS defines
   *
   * @author  Esther Brunner  <wikidesign@gmail.com>
   */
  function define_toolbar(&$event, $param)
  {      
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
      elseif ($event->data[$i]['icon'] == 'italic.png')
      {
        $event->data[$i]['open']  = "''";
        $event->data[$i]['close']  = "''";
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
