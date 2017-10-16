<?php
/**
 * 
 * @Version 1.0.0
 */

if (!defined('MEDIAWIKI')) {
  die(-1);
}
?>

<?php
/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @package MediaWiki
 * @subpackage Skins
 */
class SkinTyrianMediaWiki extends SkinTemplate {

  public $skinname = 'tyrian-mediawiki';
  public $stylename = 'tyrian-mediawiki';
  public $template = 'TyrianMediaWikiTemplate';
  public $useHeadElement = true;

  public function initPage(OutputPage $out) {
    global $wgSiteJS;
    parent::initPage($out);
    $out->addModuleScripts('skins.tyrian-mediawiki');
    $out->addMeta('viewport', 'width=device-width, initial-scale=1.0');
    $out->addMeta('theme-color', '#54487a');
  }

  public function setupSkinUserCss(OutputPage $out) {
    global $wgSiteCSS;
    parent::setupSkinUserCss($out);

    $out->addModuleStyles('skins.tyrian-mediawiki');
  }
}
?>

<?php
class TyrianMediaWikiTemplate extends BaseTemplate {
  public $skin;

  public function execute() {
    global $wgRequest, $wgUser, $wgSitename, $wgSitenameshort, $wgCopyrightLink;
    global $wgCopyright, $wgBootstrap, $wgArticlePath, $wgGoogleAnalyticsID;
    global $wgSiteCSS;
    global $wgEnableUploads;
    global $wgLogo;
    global $wgTOCLocation;
    global $wgNavBarClasses;
    global $wgSubnavBarClasses;

    $this->skin = $this->data['skin'];
    $action = $wgRequest->getText('action');
    $url_prefix = str_replace('$1', '', $wgArticlePath);

    // Suppress warnings to prevent notices about missing indexes in $this->data
    wfSuppressWarnings();
    $this->html('headelement');
    ?>
      <div class="ololo-wiki">
      <?php
        $this->header($wgUser);
        $this->body();
        $this->footer();
        $this->html('bottomscripts'); /* JS call to runBodyOnloadHook */
        $this->html('reporttime');
      ?>
      </div>
    <?php
    echo Html::closeElement('body');
    echo Html::closeElement('html');
    wfRestoreWarnings();
  }

  private function header($wgUser) { 
    global $wgSitename;
    $mainPageUrl = $this->data['nav_urls']['mainpage']['href'];
    ?>
    <header class="wiki-header noprint">
      <div class="site-title">
        <div class="container">
          <div class="row">

            <div class="wiki-title">
              <h1 class="header-menu-toggle">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse" onclick="openSideNav()">
                  <span class="icon-bar">&#9776;</span>
                </a>
              </h1>
              <h1 class="header-logo"><a href="<?php echo $mainPageUrl; ?>"><?php echo $wgSitename; ?></a></h1>
            </div>

            <div class="site-title-buttons">
              <div class="btn-group">
                <div>
                  <form class="navbar-search navbar-form navbar-right" action="<?php $this->text( 'wgScript' ) ?>" id="searchform" role="search">
                    <div class="input-group">
                      <input class="form-control" type="search" name="search" placeholder="Search" title="Search <?php echo $wgSitename; ?> [ctrl-option-f]" accesskey="f" id="searchInput" autocomplete="off">
                      <input type="hidden" name="title" value="Special:Search">
                      <div class="input-group-btn">
                        <input name="go" value="Go" title="Go to a page with this exact name if exists" id="searchGoButton" class="searchButton btn btn-default" type="submit">
                        <input name="fulltext" value="Search" title="Search the pages for this text" id="mw-searchButton" class="searchButton btn btn-default" type="submit">
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>

          </div>
        </div>
      </div>

      <!-- main menu/desktop menu -->
      <nav class="navbar tyrian-navbar navbar-sticky" id="wiki-actions" role="navigation">
        <div class="container">
      	  <div class="row">

            <ul class="nav navbar-nav">
              <li><a href="<?php echo $mainPageUrl; ?>">Home</a></li>
              <li class="dropdown">
              <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-cog"></i> Page Info<span class="caret"></span></a>
                <ul class="dropdown-menu" role="menu">
                  <?php
                    foreach ( $this->getToolbox() as $key => $tbitem) {
                      echo $this->makeListItem( $key, $tbitem );
                    }
                  ?>
                </ul>
              </li>
            </ul>

            <div>
              <?php
              if ($wgUser->isLoggedIn()) {
                  $personal = $this->data['personal_urls'];

                  $name = $wgUser->getName();
                  $user_nav = $this->get_array_links($personal, $user_icon . $name, 'user');
                  ?>
                  <ul class="nav navbar-nav navbar-right">
                      <?php echo $user_nav; ?>
                  </ul>
                  <?php

                  if (count( $this->data['content_actions']) > 0) {
                      $content_nav =
                          $this->get_array_links($this->data['content_actions'], 'Page Actions', 'page');
                      ?>
                      <ul class="nav navbar-nav navbar-right content-actions"><?php echo $content_nav; ?></ul>
                      <?php
                  }
              } else {
                  ?>
                  <ul class="nav navbar-nav navbar-right">
                      <li>
                          <?php echo Linker::linkKnown( SpecialPage::getTitleFor('Userlogin'), wfMsg('login')); ?>
                      </li>
                  </ul>
                  <?php
              }
              ?>
            </div>

          </div>
        </div>
      </nav>

      <!-- side navigation menu for mobile -->
      <nav class="sidenav" id="sidenav">
        <ul class="list-unstyled components">
          <a href="javascript:void(0)" class="closebtn" onclick="closeSideNav()">&times;</a>
          <li><a href="<?php echo $mainPageUrl; ?>">Home</a></li>
          <?php if($wgUser->isLoggedIn()) {
          ?>
            <li>
                <a href="#pageInfo" data-toggle="collapse" aria-expanded="false">
                    <i class="fa fa-cog"></i> Page Info <span class="caret"></span>
                </a>
                <ul class="collapse list-unstyled sidenav-menu-dropdown" id="pageInfo">
                    <?php
                    foreach ( $this->getToolbox() as $key => $tbitem) {
                        echo $this->makeListItem( $key, $tbitem );
                    }
                    ?>
                </ul>
            </li>
            <li>
                <a href="#pageActions" data-toggle="collapse" aria-expanded="false">
                    Page Actions <span class="caret"></span>
                </a>
                <ul class="collapse list-unstyled" id="pageActions">
                    <?php
                    if (count($this->data['content_actions']) > 0) {
                        $menuItemsList = $this->data['content_actions'];
                        $content_nav = $this->get_array_links($menuItemsList, 'Page Actions', 'page');
                        echo $content_nav;
                    }
                    ?>
                </ul>
            </li>
            <li>
                <a href="#userSideMenu" data-toggle="collapse" aria-expanded="false">
                    <?php echo $wgUser->getName(); ?> <span class="caret"></span>
                </a>
                <ul class="collapse list-unstyled" id="userSideMenu">
                    <?php
                    $menuItemsList = $this->data['personal_urls'];
                    $menuTitle = $wgUser->getName();
                    $user_nav = $this->get_array_links($menuItemsList, $menuTitle, 'user');
                    echo $user_nav;
                    ?>
                </ul>
            </li>
          <?php
              } else {
                echo Linker::linkKnown( SpecialPage::getTitleFor('UserLogin'), wfMsg('Login'));
              }
          ?>
          </ul>
      </nav>

    </header>
    <?php
  }

  private function body() {
    ?>
    <div id="wiki-outer-body">
      <div id="wiki-body" class="container">
        <?php if( $this->data['sitenotice'] ) { ?>
          <div id="siteNotice" class="alert-message warning">
            <?php $this->html('sitenotice') ?>
          </div>
        <?php } ?>

        <?php if ( $this->data['undelete'] ): ?>
          <!-- undelete -->
          <div id="contentSub2"><?php $this->html( 'undelete' ) ?></div>
          <!-- /undelete -->
        <?php endif; ?>

        <?php if($this->data['newtalk'] ) { ?>
          <!-- newtalk -->
          <div class="usermessage"><?php $this->html( 'newtalk' )  ?></div>
          <!-- /newtalk -->
        <?php } ?>

        <div class="pagetitle page-header">
          <h1><?php $this->html('title') ?><small><?php $this->html('subtitle') ?></small></h1>
        </div>  

        <div class="body">
          <?php $this->html('bodytext') ?>
        </div>

        <?php if ($this->data['catlinks']) { ?>
          <div class="category-links">
            <!-- catlinks -->
            <?php $this->html( 'catlinks' ); ?>
            <!-- /catlinks -->
          </div>
        <?php } ?>

        <?php if ($this->data['dataAfterContent']) { ?>
          <div class="data-after-content noprint">
          <!-- dataAfterContent -->
          <?php $this->html('dataAfterContent'); ?>
          <!-- /dataAfterContent -->
          </div>
        <?php } ?>

        <?php if ('sidebar' == $wgTOCLocation) { ?>
          </section></section>
        <?php } ?>
      </div>
    </div>
    <?php
  }

  private function footer() {
    $lowerfooterlinks = array('privacy', 'about', 'disclaimer', 'tagline');
    ?>
    <hr/> 

    <div id="footer">
      <footer>
        <div class="container">
          <?php
            if (isset($this->data['lastmod']) && $this->data['lastmod']) { 
              ?>
              <div class="row">
              <span id="lastmod"><?php $this->html('lastmod'); ?></span>
              </div>
              <?php 
            }
          ?>

        <div class="row">
          <div>
            <strong>2012 &ndash; <?php echo date('Y'); ?> by 
            <a href="http://alexeygrigorev.com">Alexey Grigorev</a></strong><br/>
            Powered by <a href="https://www.mediawiki.org">MediaWiki</a>. 
            <a href="https://github.com/alexeygrigorev/TyrianMediawiki-Skin">TyrianMediawiki Skin</a>, 
            with <a href="https://github.com/gentoo/tyrian">Tyrian</a> design by <a href="https://www.gentoo.org/">Gentoo</a>.<br/>
            <small>
            <?php
              foreach ($lowerfooterlinks as $aLink) {
                if (isset($this->data[$aLink]) && $this->data[$aLink]) { 
                  ?>
                  <span id="<?php echo $aLink; ?>"><?php $this->html($aLink); ?></span>
                  <?php 
                }
              }    
            ?>
            </small>
          </div>
        </div></div>
      </footer>
    </div>
    <?php
  }

  /**
   * Render one or more navigation elements by name, automatically reversed
   * when UI is in RTL mode
   */
  private function nav($nav) {
    $output = '';
    foreach ($nav as $topItem) {
      $pageTitle = Title::newFromText($topItem['link'] ?: $topItem['title']);
      if (array_key_exists('sublinks', $topItem)) {
        $output .= '<li class="dropdown">';
        $output .= '<a href="#" class="dropdown-toggle" data-toggle="dropdown">' . $topItem['title'] . ' <b class="caret"></b></a>';
        $output .= '<ul class="dropdown-menu">';

        foreach ( $topItem['sublinks'] as $subLink ) {
          if ( 'divider' == $subLink ) {
            $output .= "<li class='divider'></li>\n";
          } elseif ( $subLink['textonly'] ) {
            $output .= "<li class='nav-header'>{$subLink['title']}</li>\n";
          } else {
            if( $subLink['local'] && $pageTitle = Title::newFromText( $subLink['link'] ) ) {
              $href = $pageTitle->getLocalURL();
            } else {
              $href = $subLink['link'];
            }//end else

            $slug = strtolower( str_replace(' ', '-', preg_replace( '/[^a-zA-Z0-9 ]/', '', trim( strip_tags( $subLink['title'] ) ) ) ) );
            $output .= "<li {$subLink['attributes']}><a href='{$href}' class='{$subLink['class']} {$slug}'>{$subLink['title']}</a>";
          }//end else
        }
        $output .= '</ul>';
        $output .= '</li>';
      } else {
        if( $pageTitle ) {
          $output .= '<li' . ($this->data['title'] == $topItem['title'] ? ' class="active"' : '') . '><a href="' . ( $topItem['external'] ? $topItem['link'] : $pageTitle->getLocalURL() ) . '">' . $topItem['title'] . '</a></li>';
        }//end if
      }//end else
    }//end foreach
    return $output;
  }//end nav

  /**
   * Render one or more navigation elements by name, automatically reversed
   * when UI is in RTL mode
   */
  private function nav_select($nav) {
    $output = '';
    foreach ($nav as $topItem) {
      $pageTitle = Title::newFromText($topItem['link'] ?: $topItem['title']);
      $output .= '<optgroup label="' . strip_tags($topItem['title']) . '">';
      if (array_key_exists( 'sublinks', $topItem)) {
        foreach ($topItem['sublinks'] as $subLink) {
          if ('divider' == $subLink) {
            $output .= "<option value='' disabled='disabled' class='unclickable'>----</option>\n";
          } elseif ($subLink['textonly']) {
            $output .= "<option value='' disabled='disabled' class='unclickable'>{$subLink['title']}</option>\n";
          } else {
            if ($subLink['local'] && $pageTitle == Title::newFromText($subLink['link'])) {
              $href = $pageTitle->getLocalURL();
            } else {
              $href = $subLink['link'];
            }//end else

            $output .= "<option value='{$href}'>{$subLink['title']}</option>";
          }//end else
        }//end foreach
      } elseif ( $pageTitle ) {
        $output .= '<option value="' . $pageTitle->getLocalURL() . '">' . $topItem['title'] . '</option>';
      }//end else
      $output .= '</optgroup>';
    }//end foreach

    return $output;
  }//end nav_select

  private function get_page_links( $source ) {
    $titleBar = $this->getPageRawText( $source );
    $nav = array();
    foreach(explode("\n", $titleBar) as $line) {
      if (trim($line) == '') {
        continue;
      }

      if (preg_match('/^\*\*\s*divider/', $line)) {
        $nav[count( $nav ) - 1]['sublinks'][] = 'divider';
        continue;
      }

      $sub = false;
      $link = false;
      $external = false;

      if (preg_match('/^\*\s*([^\*]*)\[\[:?(.+)\]\]/', $line, $match)) {
        $sub = false;
        $link = true;
      } elseif (preg_match('/^\*\s*([^\*\[]*)\[([^\[ ]+) (.+)\]/', $line, $match)) {
        $sub = false;
        $link = true;
        $external = true;
      } elseif(preg_match('/^\*\*\s*([^\*\[]*)\[([^\[ ]+) (.+)\]/', $line, $match)) {
        $sub = true;
        $link = true;
        $external = true;
      } elseif(preg_match('/\*\*\s*([^\*]*)\[\[:?(.+)\]\]/', $line, $match)) {
        $sub = true;
        $link = true;
      } elseif(preg_match('/\*\*\s*([^\* ]*)(.+)/', $line, $match)) {
        $sub = true;
        $link = false;
      } elseif(preg_match('/^\*\s*(.+)/', $line, $match)) {
        $sub = false;
        $link = false;
      }

      if (strpos( $match[2], '|' ) !== false) {
        $item = explode('|', $match[2]);
        $item = array(
          'title' => $match[1] . $item[1],
          'link' => $item[0],
          'local' => true,
        );
      } else {
        if($external) {
          $item = $match[2];
          $title = $match[1] . $match[3];
        } else {
          $item = $match[1] . $match[2];
          $title = $item;
        }

        if ($link) {
          $item = array('title'=> $title, 'link' => $item, 'local' => !$external , 'external' => $external );
        } else {
          $item = array('title'=> $title, 'link' => $item, 'textonly' => true, 'external' => $external );
        }
      }

      if( $sub ) {
        $nav[count( $nav ) - 1]['sublinks'][] = $item;
      } else {
        $nav[] = $item;
      }
    }

    return $nav;  
  }

  private function get_array_links($menuItemsList, $menuTitle, $menuName) {
    $nav = array();
    $nav[] = array('title' => $menuTitle);
    foreach ($menuItemsList as $key => $item) {
      $link = $this->getStandardizedLink($key, $item);
      $icon = $this->getIconForMenuItem($menuName, $link['title']);
      $link['title'] = $this->toIconizedText($link['title'], $icon);
      $nav[0]['sublinks'][] = $link;
    }

    return $this->nav($nav);
  }

  function getPageRawText($title) {
    global $wgParser, $wgUser;
    $pageTitle = Title::newFromText($title);
    if (!$pageTitle->exists()) {
      return 'Create the page [[Bootstrap:TitleBar]]';
    } else {
      $article = new Article($pageTitle);
      $wgParserOptions = new ParserOptions($wgUser);
      // get the text as static wiki text, but with already expanded templates,
      // which also e.g. to use {{#dpl}} (DPL third party extension) for dynamic menus.
      $parserOutput = $wgParser->preprocess($article->getRawText(), $pageTitle, $wgParserOptions );
      return $parserOutput;
    }
  }

  function includePage($title) {
    global $wgParser, $wgUser;
    $pageTitle = Title::newFromText($title);
    if (!$pageTitle->exists()) {
      echo 'The page [[' . $title . ']] was not found.';
    } else {
      $article = new Article($pageTitle);
      $wgParserOptions = new ParserOptions($wgUser);
      $parserOutput = 
        $wgParser->parse($article->getRawText(), $pageTitle, $wgParserOptions);
      echo $parserOutput->getText();
    }
  }

  function link() { }

  function toIconizedText($text, $icon) {
      return '<i class="fa fa-' . $icon . '"></i> ' . $text;
  }

  function getStandardizedLink($linkName, $linkInfoArray) {
      $link = array(
          'id' => Sanitizer::escapeId($linkName),
          'attributes' => $linkInfoArray['attributes'],
          'link' => htmlspecialchars($linkInfoArray['href'] ),
          'key' => $linkInfoArray['key'],
          'class' => htmlspecialchars($linkInfoArray['class'] ),
          'title' => htmlspecialchars($linkInfoArray['text'] ),
      );
      return $link;
  }

  function getIconForMenuItem($menuName, $menuItemName) {
      if ('page' == $menuName) {
          switch ($menuItemName) {
              case 'Page':
                  $icon = 'file';
                  break;
              case 'Discussion':
                  $icon = 'comment';
                  break;
              case 'Edit':
                  $icon = 'pencil';
                  break;
              case 'History':
                  $icon = 'clock-o';
                  break;
              case 'Delete':
                  $icon = 'remove';
                  break;
              case 'Move':
                  $icon = 'arrows';
                  break;
              case 'Protect':
                  $icon = 'lock';
                  break;
              case 'Watch':
                  $icon = 'eye';
                  break;
              case 'Unwatch':
                  $icon = 'eye-slash';
                  break;
          }
      } elseif ('user' == $menuName) {
          switch ($menuItemName) {
              case 'Talk':
                  $icon = 'comment';
                  break;
              case 'Preferences':
                  $icon = 'cog';
                  break;
              case 'Watchlist':
                  $icon = 'list';
                  break;
              case 'Contributions':
                  $icon = 'list-alt';
                  break;
              case 'Log out':
                  $icon = 'power-off';
                  break;
              default:
                  $icon = 'user';
                  break;
          }
      }

      return $icon;
  }
}
?>