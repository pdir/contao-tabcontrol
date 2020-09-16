<?php

/**
 * TabControl
 *
 * @copyright  Christian Barkowsky 2012-2020, Jean-Bernard Valentaten 2009-2012
 * @package    tabControl
 * @author     Christian Barkowsky <http://christianbarkowsky.de>
 * @license    LGPL
 */

namespace Contao;

use Contao\Backend;
use Contao\Controller;
use Contao\Database;
use Contao\DataContainer;
use Contao\Input;
use Contao\LayoutModel;
use Contao\StringUtil;

// Palettes
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'tabType';
$GLOBALS['TL_DCA']['tl_content']['palettes']['tabcontrol'] = '{type_legend},type,tabType';
$GLOBALS['TL_DCA']['tl_content']['palettes']['tabcontroltab'] = '{type_legend},type,headline,tabType;{tab_legend},tabControlCookies,tab_tabs,tabBehaviour,tabClasses,tab_remember;{tabcontrol_autoplay_legend:hide},tab_autoplay_autoSlide,tab_autoplay_delay,tab_autoplay_fade;{template_legend:hide},tab_template;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes']['tabcontrolstart'] = '{type_legend},type,tabType;{tab_legend},tabClasses;{template_legend:hide},tab_template_start;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes']['tabcontrolstop'] = '{type_legend},type,tabType;{template_legend:hide},tab_template_stop;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';
$GLOBALS['TL_DCA']['tl_content']['palettes']['tabcontrol_end'] = '{type_legend},type,tabType;{template_legend:hide},tab_template_end;{protected_legend:hide},protected;{expert_legend:hide},guests;{invisible_legend:hide},invisible,start,stop';

// Fields
$GLOBALS['TL_DCA']['tl_content']['fields']['tabType'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_content']['tabType'],
    'default' => 'tab',
    'exclude' => true,
    'inputType' => 'radio',
    'options' => array('tabcontroltab', 'tabcontrolstart', 'tabcontrolstop', 'tabcontrol_end'),
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['tabControl'],
    'eval' => [
        'helpwizard' => true,
        'submitOnChange' => true,
        'tl_class' => 'clr'
    ]
);

$GLOBALS['TL_DCA']['tl_content']['fields']['tabClasses'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_content']['tabClasses'],
    'exclude' => true,
    'search' => true,
    'inputType' => 'text',
    'eval' => array('multiple' => true, 'size' => 2, 'rgxp' => 'alnum', 'tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['tabBehaviour'] = array
(
    'label' => $GLOBALS['TL_LANG']['tl_content']['tabBehaviour'],
    'exclude' => true,
    'search' => false,
    'inputType' => 'select',
    'options' => array('click', 'mouseover'),
    'default' => 'click',
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['tabControl'],
    'eval' => array('helpwizard' => true, 'tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['tab_autoplay_autoSlide'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_content']['tabControl']['tab_autoplay_autoSlide'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => array('tl_class' => 'w50 m12')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['tab_autoplay_fade'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_content']['tabControl']['tab_autoplay_fade'],
    'exclude' => true,
    'inputType' => 'checkbox',
    'eval' => array('tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['tab_autoplay_delay'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_content']['tabControl']['tab_autoplay_delay'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array('mandatory' => true, 'nospace' => true, 'rgxp' => 'digit', 'tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['tabControlCookies'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_content']['tabControlCookies'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array('maxlength'=>128),
    'save_callback' => array
    (
        array('tl_content_tabcontrol', 'generateCookiesName')
    ),
    'sql' => "varchar(128) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['tab_tabs'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_content']['tab_tabs'],
    'exclude' => true,
    'inputType' => 'multiColumnWizard',
    'eval' => [
        'columnFields' => [
            'tab_tabs_name' => [
                'label' => &$GLOBALS['TL_LANG']['tl_content']['tab_tabs_name'],
                'inputType' => 'text',
                'eval' => ['mandatory' => true, 'style' => 'width:400px', 'allowHtml' => true]
            ],
            'tab_tabs_cookies_value' => [
                'label' => &$GLOBALS['TL_LANG']['tl_content']['tab_tabs_cookies_value'],
                'inputType' => 'text',
                'eval' => ['style' => 'width:75px']
            ],
            'tab_tabs_default' => [
                'label' => &$GLOBALS['TL_LANG']['tl_content']['tab_tabs_default'],
                'exclude' => true,
                'inputType' => 'checkbox',
                'eval' => ['style' => 'width:40px']
            ]
        ]
    ],
    'sql' => "blob NULL"
];

$GLOBALS['TL_DCA']['tl_content']['fields']['tab_template'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['tab_template'],
    'default'                 => 'ce_tabcontrol_tab',
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'        => array('tl_content_tabcontrol', 'getTabcontrolTemplates'),
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "varchar(64) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['tab_template_start'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['tab_template_start'],
    'default'                 => 'ce_tabcontrol_start',
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'        => array('tl_content_tabcontrol', 'getTabcontrolTemplates'),
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "varchar(64) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['tab_template_stop'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['tab_template_stop'],
    'default'                 => 'ce_tabcontrol_stop',
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'        => array('tl_content_tabcontrol', 'getTabcontrolTemplates'),
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "varchar(64) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['tab_template_end'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['tab_template_end'],
    'default'                 => 'ce_tabcontrol_end',
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'        => array('tl_content_tabcontrol', 'getTabcontrolTemplates'),
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "varchar(64) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['tab_remember'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_content']['tab_remember'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'clr'),
    'sql'                     => "char(1) NOT NULL default ''"
);

/**
 * Class tl_content_tabcontrol
 */
class tl_content_tabcontrol extends Backend
{

    /**
     * tl_content_tabcontrol constructor.
     */
    public function __construct()
    {
        parent::__construct();

        System::importStatic('BackendUser', 'User');
    }

    /**
     * Return all tabcontrol templates as array
     *
     * @param DataContainer $dc
     * @return array
     */
    public function getTabcontrolTemplates(DataContainer $dc)
    {
        // Only look for a theme in the articles module (see #4808)
        if (Input::get('do') == 'article') {
            $intPid = $dc->activeRecord->pid;

            if (Input::get('act') == 'overrideAll') {
                $intPid = Input::get('id');
            }

            // Get the page ID
            $objArticle = Database::getInstance()
                ->prepare("SELECT pid FROM tl_article WHERE id=?")
                ->limit(1)
                ->execute($intPid);

            // Inherit the page settings
            $objPage = Controller::getPageDetails($objArticle->pid);

            // Get the theme ID
            $objLayout = LayoutModel::findByPk($objPage->layout);

            if ($objLayout === null) {
                return [];
            }
        }

        $templateSnip = '';

        switch($dc->activeRecord->tabType) {
            case 'tabcontrolstart':
                $templateSnip = 'start';
                break;

            case 'tabcontrolstop':
                $templateSnip = 'stop';
                break;

            case 'tabcontrol_end':
                $templateSnip = 'end';
                break;

            case 'tabcontroltab':
            default:
                $templateSnip = 'tab';
                break;
        }

        // Return all gallery templates
        return $this->getTemplateGroup('ce_tabcontrol_' . $templateSnip, [$objLayout->pid]);
    }

    /**
     * Auto-generate the cookie name
     * @param $varValue
     * @param DataContainer $dc
     * @return string
     * @throws Exception
     */
    public function generateCookiesName($varValue, DataContainer $dc)
    {
        $autoAlias = false;

        // Generate alias if there is none
        if ($varValue == '') {
            $autoAlias = true;
            $varValue = StringUtil::standardize(StringUtil::restoreBasicEntities('tabControllCookie-' . $dc->activeRecord->id));
        }

        $objAlias = Database::getInstance()
            ->prepare("SELECT id FROM tl_content WHERE tabControlCookies=?")
            ->execute($varValue);

        // Check whether the cookies name alias exists
        if ($objAlias->numRows > 1 && !$autoAlias) {
            throw new \Exception(sprintf($GLOBALS['TL_LANG']['ERR']['aliasExists'], $varValue));
        }

        // Add ID to cookies name
        if ($objAlias->numRows && $autoAlias) {
            $varValue .= '-' . $dc->id;
        }

        return $varValue;
    }
}
