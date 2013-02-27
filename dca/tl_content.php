<?php

/**
 * TabControl
 * 
 * @copyright  Christian Barkowsky 2012-2013, Jean-Bernard Valentaten 2009-2012
 * @package    tabControl
 * @author     Christian Barkowsky <http://www.christianbarkowsky.de>
 * @license    LGPL
 */


// Palettes
$GLOBALS['TL_DCA']['tl_content']['palettes']['__selector__'][] = 'tabType';
$GLOBALS['TL_DCA']['tl_content']['palettes']['tabcontrol'] = '{type_legend},type,tabType';
$GLOBALS['TL_DCA']['tl_content']['palettes']['tabcontroltab'] = '{type_legend},type,headline,tabType;{tab_legend},tabControlCookies,tab_tabs,tabBehaviour,tabClasses;{tabcontrol_autoplay_legend:hide},tab_autoplay_autoSlide,tab_autoplay_delay,tab_autoplay_fade;{template_legend:hide},tab_template;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_content']['palettes']['tabcontrolstart'] = '{type_legend},type,tabType;{tab_legend},tabClasses;{template_legend:hide},tab_template_start;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_content']['palettes']['tabcontrolstop'] = '{type_legend},type,tabType;{template_legend:hide},tab_template_stop;{protected_legend:hide},protected;{expert_legend:hide},guests';
$GLOBALS['TL_DCA']['tl_content']['palettes']['tabcontrol_end'] = '{type_legend},type,tabType;{template_legend:hide},tab_template_end;{protected_legend:hide},protected;{expert_legend:hide},guests';


// Fields
$GLOBALS['TL_DCA']['tl_content']['fields']['tabType'] = array
(
    'label' => &$GLOBALS['TL_LANG']['tl_content']['tabType'],
    'default' => 'tab',
    'exclude' => true,
    'inputType' => 'radio',
    'options' => array('tabcontroltab', 'tabcontrolstart', 'tabcontrolstop', 'tabcontrol_end'),
    'reference' => &$GLOBALS['TL_LANG']['tl_content']['tabControl'],
    'eval' => array('helpwizard' => true, 'submitOnChange' => true)
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
	'inputType' => 'text',
	'eval' => array('mandatory' => true, 'nospace' => true, 'rgxp' => 'digit', 'tl_class' => 'w50')
);

$GLOBALS['TL_DCA']['tl_content']['fields']['tabControlCookies'] = array
(
	'label' => &$GLOBALS['TL_LANG']['tl_content']['tabControlCookies'],
	'inputType' => 'text',
	'eval' => array('maxlength'=>64),
	'sql' => "varchar(64) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_content']['fields']['tab_tabs'] = array
(
	'label' => &$GLOBALS['TL_LANG']['tl_content']['tab_tabs'],
	'exclude' => true,
	'inputType' => 'multiColumnWizard',
	'eval' => array
	(
		'columnFields' => array
		(
			'tab_tabs_name' => array
			(
				'label' 		=> &$GLOBALS['TL_LANG']['tl_content']['tab_tabs_name'],
				'inputType' 		=> 'text',
				'eval'                  => array('mandatory'=>true, 'style'=>'width:400px', 'allowHtml'=>true)
			),
			'tab_tabs_cookies_value' => array
			(
				'label' 		=> &$GLOBALS['TL_LANG']['tl_content']['tab_tabs_cookies_value'],
				'inputType' 		=> 'text',
				'eval'                  => array('style'=>'width:75px')
			),
			'tab_tabs_default' => array
			(
				'label'                 => &$GLOBALS['TL_LANG']['tl_content']['tab_tabs_default'],
				'exclude'               => true,
				'inputType'             => 'checkbox',
				'eval'                  => array('style'=>'width:40px')
 
			)
		)
	),
	'sql' => "blob NULL"
);

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



class tl_content_tabcontrol extends Backend
{

	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}
	
	
	/**
	 * Return all tabcontrol templates as array
	 */
	public function getTabcontrolTemplates(DataContainer $dc)
	{
		// Only look for a theme in the articles module (see #4808)
		if (Input::get('do') == 'article')
		{
			$intPid = $dc->activeRecord->pid;

			if (Input::get('act') == 'overrideAll')
			{
				$intPid = Input::get('id');
			}

			// Get the page ID
			$objArticle = $this->Database->prepare("SELECT pid FROM tl_article WHERE id=?")
										 ->limit(1)
										 ->execute($intPid);

			// Inherit the page settings
			$objPage = $this->getPageDetails($objArticle->pid);

			// Get the theme ID
			$objLayout = LayoutModel::findByPk($objPage->layout);

			if ($objLayout === null)
			{
				return array();
			}
		}
		
		$templateSnip = '';
		
		switch($dc->activeRecord->tabType)
		{		
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
		return $this->getTemplateGroup('ce_tabcontrol_' . $templateSnip, $objLayout->pid);
	}
}

?>
