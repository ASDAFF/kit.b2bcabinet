<?

use Bitrix\Main\Localization\CultureTable;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/wizard.php");
require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/install/wizard_sol/wizard.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/update_client.php");
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/update_client_partner.php");

class SelectSiteStep extends CSelectSiteWizardStep
{
    function InitStep()
    {
        $this->SetStepID("select_site");
        $this->SetTitle(GetMessage("SELECT_SITE_TITLE"));

        $this->SetNextStep("site_install_param");
        $this->SetNextCaption(GetMessage("NEXT_BUTTON"));
    }

    function OnPostForm()
    {
        $wizard =& $this->GetWizard();

        $proactive = COption::GetOptionString("statistic", "DEFENCE_ON", "N");
        if ($proactive == "Y")
        {
            COption::SetOptionString("statistic", "DEFENCE_ON", "N");
            $wizard->SetVar("proactive", "Y");
        }
        else
        {
            $wizard->SetVar("proactive", "N");
        }

        if ($wizard->IsNextButtonClick())
        {
            $siteID = '';
            $siteID = $wizard->GetVar("wizSiteID");

            if($wizard->GetVar('wizSiteID') == 'Y'
                && (empty($wizard->GetVar('siteNewID')) || empty($wizard->GetVar('siteFolder')))
            )
            {
                if(empty($wizard->GetVar('siteNewID')))
                {
                    $this->SetError(GetMessage("wiz_settings_new_site_id_error"));
                }

                if(empty($wizard->GetVar('siteFolder')))
                {
                    $this->SetError(GetMessage("wiz_settings_new_site_folder_error"));
                }

                $siteID = $wizard->GetVar('siteNewID');

                $wizard->SetVar('wizSiteID', $siteID);
            }

            if(empty($this->GetErrors())) {
                if(empty($siteID))
                    $this->SetError(GetMessage("wiz_site_error"));
                else {
                    $wizard->SetVar("siteID", "b2bcabinet");
                }

                $siteDir = $this->getSiteDir($siteID);

                define('WIZARD_SITE_ID', $siteID);
                define('WIZARD_SITE_DIR', $siteDir);

                $methodInstall = $wizard->GetVar("wizInstallMethod");

                if(empty($methodInstall))
                    $this->SetError(GetMessage("wiz_settings_method_error"));
                else
                    $wizard->SetVar("method_install", $methodInstall);

                if(empty($this->GetErrors())
                    && $wizard->GetVar('wizSiteID') == 'Y'
                    && !empty($wizard->GetVar('siteNewID'))
                    && !empty($wizard->GetVar('siteFolder'))
                ) {
                    $this->createNewSite();
                }
            }
        }
    }

    function ShowStep() {
        $wizard =& $this->GetWizard();

        $resSites = Bitrix\Main\SiteTable::getList();

        $arSites = array();

        while($site = $resSites->fetch())
        {
            $arSites[] = $site;

            if($site['DEF'] == 'Y')
                $wizard->SetDefaultVar('wizSiteID', $site['LID']);
        }


        $this->content .= '<link rel="stylesheet" href="'.$wizard->GetPath().'/css/b2bcabinet.css">';

        $this->content .= '<div class="wizard-input-form" id="site_select">';

        foreach ($arSites as $site)
        {
            $this->content .=
                '<div class="wizard-catalog-form-item">'.
                $this->ShowRadioField("wizSiteID", $site['LID'], array("id" => $site['LID'], "checked" => "checked"))
." <label for=".$site['LID'].">". '[' . $site['LID'] . '] ' . $site["NAME"]."</label>
				</div>";
        }
/*
        $this->content .=
            '<div class="wizard-catalog-form-item">'.
            $this->ShowRadioField("wizSiteID", 'Y', array("id" => 'createSiteY'))
            .' <label>'.GetMessage("WIZARD_METHOD_INSTALL_SELECT_NEW").'</label><p>'.GetMessage("WIZARD_METHOD_INSTALL_SELECT_NEW_SITE_ID").' '.$this->ShowInputField("text", "siteNewID", array("size" => 2, "maxlength" => 2, "id" => "siteNewID")).', '.GetMessage("WIZARD_METHOD_INSTALL_SELECT_NEW_SITE_DIR").' '.$this->ShowInputField("text", "siteFolder", array("id" => "siteFolder")).'.</p></div>';

        $this->content .= "</div>";
*/
        $arMethod = array(
            'AS_SITE' => getMessage('WIZARD_METHOD_INSTALL_AS_SITE'),
            'AS_TEMPLATE' => getMessage('WIZARD_METHOD_INSTALL_AS_TEMPLATE'),
        );

        $this->content .= '<div class="install_mode"><div class="wizard-catalog-title">'.GetMessage("SELECT_TEMPLATE_TITLE").'</div>';
        $this->content .= '<div class="wizard-input-form">';

        foreach ($arMethod as $key => $method)
        {
            $this->content .=
                '<div class="wizard-catalog-form-item">'.
                $this->ShowRadioField("wizInstallMethod", $key, array("id" => $key, 'checked' => 'checked'))
                ." <label for=".$key.">". $method ."</label>
				</div>";

        }
        $this->content .= "</div>";
        /*
        $this->content .= "<div class='inst-cont-title'>".GetMessage("WIZARD_METHOD_INSTALL_SELECT_SITE_NOTE")."</div></div>
            <script>
                let btnNewSite = document.getElementById('site_select');
                btnNewSite.addEventListener('click', function(event) {     
                    let radioBlock = document.querySelector('.install_mode');
                    if(event.target.type == 'radio') {                    
                        if(event.target.id == 'createSiteY' ) {
                            radioBlock.style.display = 'none';
                        } else {
                            radioBlock.style.display = 'block';
                        }
                    }
                });
            </script>
        ";
        */
    }

    function createNewSite() {
        $wizard =& $this->GetWizard();

        $res = false;
        $site_id = $wizard->GetVar("siteNewID");

        if($site_id != "")
        {
            $res = CSite::GetList($by="sort", $order="desc", array("LID" => $site_id))->fetch();
            if($res)
                $this->SetError(GetMessage("wiz_settings_new_site_id_exist_error"));
        }

        if($wizard->GetVar("wizSiteID")=="Y")
        {
            if(!$res)
            {
                $culture = CultureTable::getRow(array('filter'=>array(
                    "=FORMAT_DATE" => (LANGUAGE_ID=="en"? "MM/DD/YYYY":"DD.MM.YYYY"),
                    "=FORMAT_DATETIME" => (LANGUAGE_ID=="en"? "MM/DD/YYYY H:MI:SS T":"DD.MM.YYYY HH:MI:SS"),
                    "=FORMAT_NAME" => CSite::GetDefaultNameFormat(),
                    "=CHARSET" => (defined("BX_UTF")? "UTF-8" : (LANGUAGE_ID=="ru"? "windows-1251":"ISO-8859-1")),
                )));

                if($culture)
                {
                    $cultureId = $culture["ID"];
                }
                else
                {
                    $addResult = CultureTable::add(array(
                        "NAME" => $site_id,
                        "CODE" => $site_id,
                        "FORMAT_DATE" => (LANGUAGE_ID=="en"? "MM/DD/YYYY":"DD.MM.YYYY"),
                        "FORMAT_DATETIME" => (LANGUAGE_ID=="en"? "MM/DD/YYYY H:MI:SS T":"DD.MM.YYYY HH:MI:SS"),
                        "FORMAT_NAME" => CSite::GetDefaultNameFormat(),
                        "CHARSET" => (defined("BX_UTF")? "UTF-8" : (LANGUAGE_ID=="ru"? "windows-1251":"ISO-8859-1")),
                    ));
                    $cultureId = $addResult->getId();
                }

                $defSiteName = GetMessage("wiz_site_default_name");

                $arFields = array(
                    "LID" => $site_id,
                    "ACTIVE" => "Y",
                    "SORT" => 100,
                    "DEF" => "N",
                    "NAME" => $defSiteName,
                    "DIR" => $wizard->GetVar("siteFolder"),
                    "SITE_NAME" => $defSiteName,
                    "SERVER_NAME" => $_SERVER["SERVER_NAME"],
                    "EMAIL" => COption::GetOptionString("main", "email_from"),
                    "LANGUAGE_ID" => LANGUAGE_ID,
                    "DOC_ROOT" => "",
                    "CULTURE_ID" => $cultureId,
                );
                $obSite = new CSite;

                $result = $obSite->Add($arFields);
                if (!$result)
                {
                    $this->SetError($obSite->LAST_ERROR);
                }
            }
            $wizard->SetVar("siteCreate", "N");
        }
    }

    function getSiteDir($siteID) {
        $wizard =& $this->GetWizard();

        if($wizard->GetVar('wizSiteID') == 'Y' && !empty($wizard->GetVar('siteFolder'))) {
            return $wizard->GetVar('siteFolder');
        }

        $arSite = \Bitrix\Main\SiteTable::getList(['filter' => ['LID' => $siteID], 'select' => ['DIR']])->fetch();

        if(!empty($arSite)) {
            return $arSite['DIR'];
        } else {
            return '/';
        }
    }
}

class SiteInstallParam extends CWizardStep
{
    function InitStep()
    {
        $wizard =& $this->GetWizard();
        $wizard->solutionName = "kit.b2bcabinet";
        $this->SetStepID("site_install_param");
        $this->SetTitle(GetMessage("WIZARD_INSTALL_DEMO"));

        $this->SetNextStep('data_install');

        if($wizard->GetVar("wizInstallMethod") == 'AS_SITE')
        {
            $this->SetNextStep("site_settings");
            $this->SetNextCaption(GetMessage("NEXT_BUTTON"));
        }
        else
        {
            $this->SetNextStep("data_collection");
            $this->SetNextCaption(GetMessage("NEXT_BUTTON"));
        }

        $this->SetPrevStep("select_site");
        $this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));
    }

    function ShowStep()
    {
        $wizard =& $this->GetWizard();

        $this->content .= '<link rel="stylesheet" href="'.$wizard->GetPath().'/css/b2bcabinet.css">';

        $arAnswer = array(
            'Y' => getMessage('WIZARD_YES'),
            'N' => getMessage('WIZARD_NO'),
        );

        $this->content .= '<div class="wizard-input-form">';
        foreach ($arAnswer as $key => $answer)
        {
            $this->content .= '<div class="wizard-catalog-form-item">';
            $this->content .= $this->ShowRadioField("wizInstallDemo", $key, array("id" => 'wiz_answer_'.$key));
            $this->content .= ' <label for="wiz_answer_'.$key.'">'. $answer .'</label>';
            $this->content .= '</div>';
        }
        $this->content .= "</div>";
    }

    function OnPostForm()
    {
        $wizard =& $this->GetWizard();

        if ($wizard->IsNextButtonClick())
        {
            $demoInstall = $wizard->GetVar("wizInstallDemo");
            if (empty($demoInstall))
                $this->SetError(GetMessage("wiz_settings_demo_error"));
            else
                $wizard->SetVar("installDemoData", $demoInstall);
        }
    }
}

class SiteSettingsStep extends CSiteSettingsWizardStep
{
    function InitStep()
    {
        $wizard =& $this->GetWizard();
        $this->SetStepID("site_settings");
        $this->SetTitle(GetMessage("WIZ_STEP_SITE_SET"));

        $this->SetNextStep("catalog_settings");
        $this->SetNextCaption(GetMessage("NEXT_BUTTON"));

        $this->SetPrevStep("site_install_param");
        $this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));


        $siteID = $wizard->GetVar("wizSiteID");
        $isWizardInstalled = COption::GetOptionString("kit.b2bcabinet", "wizard_installed", "N", $siteID) == "Y";

        $wizard->SetDefaultVars(Array(
            "siteLogo" => file_exists(WIZARD_SITE_PATH."include/logo.png") ? WIZARD_SITE_DIR."include/logo.png" : ($isWizardInstalled ? "" : "/bitrix/wizards/kit/b2bcabinet/site/templates/b2bcabinet/assets/images/B2B_logo.png"),
            "siteLogoRetina" => file_exists(WIZARD_SITE_PATH."include/logo.png") ? WIZARD_SITE_DIR."include/logo.png" : ($isWizardInstalled ? "" : "/bitrix/wizards/kit/b2bcabinet/site/templates/b2bcabinet/assets/images/B2B_logo.png"),
            "siteLogoMobile" => file_exists(WIZARD_SITE_PATH."include/logo.png") ? WIZARD_SITE_DIR."include/logo.png" : ($isWizardInstalled ? "" : "/bitrix/wizards/kit/b2bcabinet/site/templates/b2bcabinet/assets/images/B2B_logo.png"),
            "siteLogoMobileRetina" => file_exists(WIZARD_SITE_PATH."include/logo.png") ? WIZARD_SITE_DIR."include/logo.png" : ($isWizardInstalled ? "" : "/bitrix/wizards/kit/b2bcabinet/site/templates/b2bcabinet/assets/images/B2B_logo.png"),
        ));

        $wizard->SetDefaultVars(
            Array(
                "siteName" => GetMessage("WIZ_COMPANY_NAME_DEF"),
                "siteSchedule" => GetMessage("WIZ_COMPANY_SCHEDULE_DEF"),
                "siteTelephone" => GetMessage("WIZ_COMPANY_TELEPHONE_DEF"),
                "siteCopy" => GetMessage("WIZ_COMPANY_COPY_DEF"),
                "shopEmail" => COption::GetOptionString("b2bcabinet", "shopEmail", "sale@".$_SERVER["SERVER_NAME"], $siteID),
                "siteMetaDescription" => GetMessage("wiz_site_desc"),
                "siteMetaKeywords" => GetMessage("wiz_keywords"),
            )
        );

    }

    function ShowStep()
    {
        $wizard =& $this->GetWizard();

        $this->content .= '<div class="wizard-input-form">';

        $this->content .= '
		<div class="wizard-input-form-block">
			<label for="siteName" class="wizard-input-title">'.GetMessage("WIZ_COMPANY_NAME").'</label>
			'.$this->ShowInputField('text', 'siteName', array("id" => "siteName", "class" => "wizard-field")).'
		</div>';
//logo --
        $siteLogo = $wizard->GetVar("siteLogo", true);
        //$this->content .= "<div style='margin: 5px 0 5px 0;'>".CFile::ShowImage($siteLogo, 0, 0, "border=0 id=\"site-logo-image\"".($wizard->GetVar("useSiteLogo", true) != "Y" ? " class=\"disabled\"" : ""), "", true)."</div>";
        $this->content .= '
		<div class="wizard-input-form-block" style="background-color: #f4f5f6;   width: 571px; padding: 10px">
			<label for="siteLogo">'.GetMessage("WIZ_COMPANY_LOGO").'</label><br/>';
        $this->content .= CFile::ShowImage($siteLogo, 215, 50, "border=0 vspace=15");
        $this->content .= "<br/>".$this->ShowFileField("siteLogo", Array("show_file_info" => "N", "id" => "siteLogo")).
            '</div>';

        $siteLogoRetina = $wizard->GetVar("siteLogoRetina", true);
        $this->content .= '
		<div class="wizard-input-form-block"  style="background-color: #f4f5f6;   width: 571px; padding: 10px">
			<label for="siteLogoRetina">'.GetMessage("WIZ_COMPANY_LOGO_RETINA").'</label><br/>'.
            CFile::ShowImage($siteLogoRetina, 430, 100, "border=0 vspace=15").'<br/>'.
            $this->ShowFileField("siteLogoRetina", Array("show_file_info" => "N", "id" => "siteLogoRetina")).
            '</div>';

        $siteLogoMobile = $wizard->GetVar("siteLogoMobile", true);
        $this->content .= '
		<div class="wizard-input-form-block"  style="background-color: #f4f5f6;   width: 571px; padding: 10px">
			<label for="siteLogoMobile">'.GetMessage("WIZ_COMPANY_LOGO_MOBILE").'</label><br/>'.
            CFile::ShowImage($siteLogoMobile, 225, 40, "border=0 vspace=15").'<br/>'.
            $this->ShowFileField("siteLogoMobile", Array("show_file_info" => "N", "id" => "siteLogoMobile")).
            '</div>';

        $siteLogoMobileRetina = $wizard->GetVar("siteLogoMobileRetina", true);
        $this->content .= '
		<div class="wizard-input-form-block"  style="background-color: #f4f5f6;   width: 571px; padding: 10px">
			<label for="siteLogoMobileRetina">'.GetMessage("WIZ_COMPANY_LOGO_MOBILE_RETINA").'</label><br/>'.
            CFile::ShowImage($siteLogoMobileRetina, 450, 80, "border=0 vspace=15").'<br/>'.
            $this->ShowFileField("siteLogoMobileRetina", Array("show_file_info" => "N", "id" => "siteLogoMobileRetina")).
            '</div>';
//-- logo
        $this->content .= '
		<div class="wizard-input-form-block">
			<label for="siteTelephone" class="wizard-input-title">'.GetMessage("WIZ_COMPANY_TELEPHONE").'</label>
			'.$this->ShowInputField('text', 'siteTelephone', array("id" => "siteTelephone", "class" => "wizard-field")).'
		</div>';

        if(LANGUAGE_ID != "ru")
        {
            $this->content .= '<div class="wizard-input-form-block">
				<label for="shopEmail" class="wizard-input-title">'.GetMessage("WIZ_SHOP_EMAIL").'</label>
				'.$this->ShowInputField('text', 'shopEmail', array("id" => "shopEmail", "class" => "wizard-field")).'
			</div>';
        }
        $this->content .= '
		<div class="wizard-input-form-block">
			<label for="siteSchedule" class="wizard-input-title">'.GetMessage("WIZ_COMPANY_SCHEDULE").'</label>
			'.$this->ShowInputField('textarea', 'siteSchedule', array("rows"=>"3", "id" => "siteSchedule", "class" => "wizard-field")).'
		</div>';
        $this->content .= '
		<div class="wizard-input-form-block">
			<label for="siteCopy" class="wizard-input-title">'.GetMessage("WIZ_COMPANY_COPY").'</label>
			'.$this->ShowInputField('textarea', 'siteCopy', array("rows"=>"3", "id" => "siteCopy", "class" => "wizard-field")).'
		</div>';

        $firstStep = COption::GetOptionString("main", "wizard_first" . substr($wizard->GetID(), 7)  . "_" . $wizard->GetVar("wizSiteID"), false, $wizard->GetVar("wizSiteID"));
        $styleMeta = 'style="display:block"';
        if($firstStep == "Y") $styleMeta = 'style="display:none"';

        $this->content .= '
		<div  id="bx_metadata" '.$styleMeta.'>
			<div class="wizard-input-form-block">
				<div class="wizard-metadata-title">'.GetMessage("wiz_meta_data").'</div>
				<label for="siteMetaDescription" class="wizard-input-title">'.GetMessage("wiz_meta_description").'</label>
				'.$this->ShowInputField("textarea", "siteMetaDescription", Array("id" => "siteMetaDescription", "rows"=>"3", "class" => "wizard-field")).'
			</div>';
        $this->content .= '
			<div class="wizard-input-form-block">
				<label for="siteMetaKeywords" class="wizard-input-title">'.GetMessage("wiz_meta_keywords").'</label><br>
				'.$this->ShowInputField('text', 'siteMetaKeywords', array("id" => "siteMetaKeywords", "class" => "wizard-field")).'
			</div>
		</div>';

        $this->content .= '</div>';
    }

    function OnPostForm()
    {
        $wizard =& $this->GetWizard();
        $res = $this->SaveFile("siteLogo", Array("extensions" => "gif,jpg,jpeg,png", "max_height" => 150, "max_width" => 500, "make_preview" => "Y"));
        $res = $this->SaveFile("siteLogoRetina", Array("extensions" => "gif,jpg,jpeg,png", "max_height" => 150, "max_width" => 500, "make_preview" => "Y"));
        $res = $this->SaveFile("siteLogoMobile", Array("extensions" => "gif,jpg,jpeg,png", "max_height" => 150, "max_width" => 500, "make_preview" => "Y"));
        $res = $this->SaveFile("siteLogoMobileRetina", Array("extensions" => "gif,jpg,jpeg,png", "max_height" => 150, "max_width" => 500, "make_preview" => "Y"));
    }
}

class CatalogSettings extends CWizardStep
{
    function InitStep()
    {
        $wizard =& $this->GetWizard();
        $this->SetStepID("catalog_settings");
        $this->SetTitle(GetMessage("WIZ_STEP_CT"));

        $this->SetNextStep("shop_settings");
        $this->SetNextCaption(GetMessage("NEXT_BUTTON"));

        $this->SetPrevStep("site_settings");
        $this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));


        $siteID = $wizard->GetVar("wizSiteID");

        $subscribe = COption::GetOptionString("sale", "subscribe_prod", "");
        $arSubscribe = unserialize($subscribe);

        $wizard->SetDefaultVars(
            Array(
                "catalogSubscribe" => (isset($arSubscribe[$siteID])) ? ($arSubscribe[$siteID]['use'] == "Y" ? "Y" : false) : "Y",
                "catalogView" => COption::GetOptionString("b2bcabinet", "catalogView", "list", $siteID),
                "useStoreControl" => COption::GetOptionString("catalog", "default_use_store_control", "Y"),
                "productReserveCondition" => COption::GetOptionString("sale", "product_reserve_condition", "P")
            )
        );
    }

    function ShowStep()
    {
        $wizard =& $this->GetWizard();

        $this->content .= '
			<div class="wizard-input-form-block">
				<div class="wizard-catalog-title">'.GetMessage("WIZ_CATALOG_USE_STORE_CONTROL").'</div>
				<div>
					<div class="wizard-catalog-form-item">
						'.$this->ShowCheckboxField("useStoreControl", "Y", array("id" => "use-store-control"))
            .'<label for="use-store-control">'.GetMessage("WIZ_STORE_CONTROL").'</label>
					</div>';

        $arConditions = array(
            "O" => GetMessage("SALE_PRODUCT_RESERVE_1_ORDER"),
            "P" => GetMessage("SALE_PRODUCT_RESERVE_2_PAYMENT"),
            "D" => GetMessage("SALE_PRODUCT_RESERVE_3_DELIVERY"),
            "S" => GetMessage("SALE_PRODUCT_RESERVE_4_DEDUCTION")
        );

        $this->content .= '
			<div class="wizard-catalog-form-item">'
            .$this->ShowSelectField("productReserveCondition", $arConditions).
            '<label>'.GetMessage("SALE_PRODUCT_RESERVE_CONDITION").'</label>
			</div>';
        $this->content .= '</div>
			</div>';
    }

    function OnPostForm()
    {
        $wizard =& $this->GetWizard();
    }
}

class ShopSettings extends CWizardStep
{
    function InitStep()
    {
        $wizard =& $this->GetWizard();
        $this->SetStepID("shop_settings");
        $this->SetTitle(GetMessage("WIZ_STEP_SS"));

        $this->SetNextStep("person_type");
        $this->SetNextCaption(GetMessage("NEXT_BUTTON"));

        $this->SetPrevStep("catalog_settings");
        $this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));

        $siteID = $wizard->GetVar("wizSiteID");

        $wizard->SetDefaultVars(
            Array(
                "shopLocalization" => COption::GetOptionString("b2bcabinet", "shopLocalization", "ru", $siteID),
                "shopEmail" => COption::GetOptionString("b2bcabinet", "shopEmail", "sale@".$_SERVER["SERVER_NAME"], $siteID),
                "shopOfName" => COption::GetOptionString("b2bcabinet", "shopOfName", GetMessage("WIZ_SHOP_OF_NAME_DEF"), $siteID),
                "shopLocation" => COption::GetOptionString("b2bcabinet", "shopLocation", GetMessage("WIZ_SHOP_LOCATION_DEF"), $siteID),
                //"shopZip" => 101000,
                "shopAdr" => COption::GetOptionString("b2bcabinet", "shopAdr", GetMessage("WIZ_SHOP_ADR_DEF"), $siteID),
                "shopINN" => COption::GetOptionString("b2bcabinet", "shopINN", "1234567890", $siteID),
                "shopKPP" => COption::GetOptionString("b2bcabinet", "shopKPP", "123456789", $siteID),
                "shopNS" => COption::GetOptionString("b2bcabinet", "shopNS", "0000 0000 0000 0000 0000", $siteID),
                "shopBANK" => COption::GetOptionString("b2bcabinet", "shopBANK", GetMessage("WIZ_SHOP_BANK_DEF"), $siteID),
                "shopBANKREKV" => COption::GetOptionString("b2bcabinet", "shopBANKREKV", GetMessage("WIZ_SHOP_BANKREKV_DEF"), $siteID),
                "shopKS" => COption::GetOptionString("b2bcabinet", "shopKS", "30101 810 4 0000 0000225", $siteID),
//                "siteStamp" => COption::GetOptionString("b2bcabinet", "siteStamp", $siteStamp, $siteID),

                "shopCompany_ua" => COption::GetOptionString("eshop", "shopCompany_ua", "", $siteID),
                "shopOfName_ua" => COption::GetOptionString("b2bcabinet", "shopOfName_ua", GetMessage("WIZ_SHOP_OF_NAME_DEF_UA"), $siteID),
                "shopLocation_ua" => COption::GetOptionString("b2bcabinet", "shopLocation_ua", GetMessage("WIZ_SHOP_LOCATION_DEF_UA"), $siteID),
                "shopAdr_ua" => COption::GetOptionString("b2bcabinet", "shopAdr_ua", GetMessage("WIZ_SHOP_ADR_DEF_UA"), $siteID),
                "shopEGRPU_ua" =>  COption::GetOptionString("b2bcabinet", "shopEGRPU_ua", "", $siteID),
                "shopINN_ua" =>  COption::GetOptionString("b2bcabinet", "shopINN_ua", "", $siteID),
                "shopNDS_ua" =>  COption::GetOptionString("b2bcabinet", "shopNDS_ua", "", $siteID),
                "shopNS_ua" =>  COption::GetOptionString("b2bcabinet", "shopNS_ua", "", $siteID),
                "shopBank_ua" =>  COption::GetOptionString("b2bcabinet", "shopBank_ua", "", $siteID),
                "shopMFO_ua" =>  COption::GetOptionString("b2bcabinet", "shopMFO_ua", "", $siteID),
                "shopPlace_ua" =>  COption::GetOptionString("b2bcabinet", "shopPlace_ua", "", $siteID),
                "shopFIO_ua" =>  COption::GetOptionString("b2bcabinet", "shopFIO_ua", "", $siteID),
                "shopTax_ua" =>  COption::GetOptionString("b2bcabinet", "shopTax_ua", "", $siteID),

                "installPriceBASE" => COption::GetOptionString("b2bcabinet", "installPriceBASE", "Y", $siteID),
            )
        );
    }

    function ShowStep()
    {
        $wizard =& $this->GetWizard();
//        $siteStamp = $wizard->GetVar("siteStamp", true);
        $firstStep = COption::GetOptionString("main", "wizard_first" . substr($wizard->GetID(), 7)  . "_" . $wizard->GetVar("wizSiteID"), false, $wizard->GetVar("wizSiteID"));

        if (!CModule::IncludeModule("catalog"))
        {
            $this->content .= "<p style='color:red'>".GetMessage("WIZ_NO_MODULE_CATALOG")."</p>";
            $this->SetNextStep("shop_settings");
        }
        else
        {
            $this->content .=
                '<div class="wizard-catalog-title">'.GetMessage("WIZ_SHOP_LOCALIZATION").'</div>
				<div class="wizard-input-form-block" >'.
                $this->ShowSelectField("shopLocalization", array(
                    "ru" => GetMessage("WIZ_SHOP_LOCALIZATION_RUSSIA"),
                    "ua" => GetMessage("WIZ_SHOP_LOCALIZATION_UKRAINE"),
                    "kz" => GetMessage("WIZ_SHOP_LOCALIZATION_KAZAKHSTAN"),
                    "bl" => GetMessage("WIZ_SHOP_LOCALIZATION_BELORUSSIA")
                ), array("onchange" => "langReload()", "id" => "localization_select","class" => "wizard-field", "style"=>"padding:0 0 0 15px")).'
				</div>';

            $currentLocalization = $wizard->GetVar("shopLocalization");
            if (empty($currentLocalization))
                $currentLocalization = $wizard->GetDefaultVar("shopLocalization");

            $this->content .= '<div class="wizard-catalog-title">'.GetMessage("WIZ_STEP_SS").'</div>
				<div class="wizard-input-form">';

            $this->content .= '
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopEmail">'.GetMessage("WIZ_SHOP_EMAIL").'</label>
					'.$this->ShowInputField('text', 'shopEmail', array("id" => "shopEmail", "class" => "wizard-field")).'
				</div>';

            //ru
            $this->content .= '<div id="ru_bank_details" class="wizard-input-form-block" style="display:'.(($currentLocalization == "ru" || $currentLocalization == "kz" || $currentLocalization == "bl") ? 'block':'none').'">
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopOfName">'.GetMessage("WIZ_SHOP_OF_NAME").'</label>'
                .$this->ShowInputField('text', 'shopOfName', array("id" => "shopOfName", "class" => "wizard-field")).'
				</div>';

            $this->content .= '
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopLocation">'.GetMessage("WIZ_SHOP_LOCATION").'</label>'
                .$this->ShowInputField('text', 'shopLocation', array("id" => "shopLocation", "class" => "wizard-field")).'
				</div>';

            $this->content .= '
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopAdr">'.GetMessage("WIZ_SHOP_ADR").'</label>'
                .$this->ShowInputField('textarea', 'shopAdr', array("rows"=>"3", "id" => "shopAdr", "class" => "wizard-field")).'
				</div>';

            if($firstStep != "Y")
            {
                $this->content .= '
					<div class="wizard-catalog-title">'.GetMessage("WIZ_SHOP_BANK_TITLE").'</div>
					<table class="wizard-input-table">
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_INN").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopINN', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_KPP").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopKPP', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_NS").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopNS', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_BANK").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopBANK', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_BANKREKV").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopBANKREKV', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_KS").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopKS', array("class" => "wizard-field")).'</td>
						</tr>
					</table>
				</div><!--ru-->
				';
            }
            $this->content .= '<div id="ua_bank_details" class="wizard-input-form-block" style="display:'.(($currentLocalization == "ua") ? 'block':'none').'">
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopOfName_ua">'.GetMessage("WIZ_SHOP_OF_NAME").'</label>'
                .$this->ShowInputField('text', 'shopOfName_ua', array("id" => "shopOfName_ua", "class" => "wizard-field")).'
					<p style="color:grey; margin: 3px 0 7px;">'.GetMessage("WIZ_SHOP_OF_NAME_DESCR_UA").'</p>
				</div>';

            $this->content .= '<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopLocation_ua">'.GetMessage("WIZ_SHOP_LOCATION").'</label>'
                .$this->ShowInputField('text', 'shopLocation_ua', array("id" => "shopLocation_ua", "class" => "wizard-field")).'
					<p style="color:grey; margin: 3px 0 7px;">'.GetMessage("WIZ_SHOP_LOCATION_DESCR_UA").'</p>
				</div>';


            $this->content .= '
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopAdr_ua">'.GetMessage("WIZ_SHOP_ADR").'</label>'.
                $this->ShowInputField('textarea', 'shopAdr_ua', array("rows"=>"3", "id" => "shopAdr_ua", "class" => "wizard-field")).'
					<p style="color:grey; margin: 3px 0 7px;">'.GetMessage("WIZ_SHOP_ADR_DESCR_UA").'</p>
				</div>';

            if($firstStep != "Y")
            {
                $this->content .= '
					<div class="wizard-catalog-title">'.GetMessage("WIZ_SHOP_RECV_UA").'</div>
					<p>'.GetMessage("WIZ_SHOP_RECV_UA_DESC").'</p>
					<table class="wizard-input-table">
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_EGRPU_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopEGRPU_ua', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_INN_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopINN_ua', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_NDS_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopNDS_ua', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_NS_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopNS_ua', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_BANK_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopBank_ua', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_MFO_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopMFO_ua', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_PLACE_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopPlace_ua', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_FIO_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopFIO_ua', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.GetMessage("WIZ_SHOP_TAX_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopTax_ua', array("class" => "wizard-field")).'</td>
						</tr>
					</table>
				</div>
				';
            }

            $this->content .= '</div>';
        }
    }

    function OnPostForm()
    {
        $wizard =& $this->GetWizard();
    }

}

class PersonType extends CWizardStep
{
    function InitStep()
    {
        $wizard =& $this->GetWizard();
        $this->SetStepID("person_type");
        $this->SetTitle(GetMessage("WIZ_STEP_PT"));

        $this->SetNextStep("pay_system");
        $this->SetNextCaption(GetMessage("NEXT_BUTTON"));

        $this->SetPrevStep("shop_settings");
        $this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));


        $shopLocalization = $wizard->GetVar("shopLocalization", true);
        $siteID = $wizard->GetVar("wizSiteID");

        if ($shopLocalization == "ua")
            $wizard->SetDefaultVars(
                Array(
                    "personType" => Array(
                        "ip" => "Y",
                        "ip_ua" => "Y",
                        "ur" => "Y",
                    )
                )
            );
        else
            $wizard->SetDefaultVars(
                Array(
                    "personType" => Array(
                        "ip" =>  COption::GetOptionString("b2bcabinet", "personTypeIp", "Y", $siteID),
                        "ur" => COption::GetOptionString("b2bcabinet", "personTypeUr", "Y", $siteID),
                    )
                )
            );
    }

    function ShowStep()
    {

        $wizard =& $this->GetWizard();
        $shopLocalization = $wizard->GetVar("shopLocalization", true);

        $this->content .= '<div class="wizard-input-form">';
        $this->content .= '
		<div class="wizard-input-form-block">
			<div style="padding-top:15px">
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">
					<div class="wizard-catalog-form-item">
						'.$this->ShowCheckboxField('personType[ip]', 'Y', (array("id" => "personTypeI"))).
            ' <label for="personTypeI">'.GetMessage("WIZ_PERSON_TYPE_FIZ").'</label><br />
					</div>
					<div class="wizard-catalog-form-item">
						'.$this->ShowCheckboxField('personType[ur]', 'Y', (array("id" => "personTypeU"))).
            ' <label for="personTypeU">'.GetMessage("WIZ_PERSON_TYPE_UR").'</label><br />
					</div>';
        if ($shopLocalization == "ua")
            $this->content .=
                '<div class="wizard-catalog-form-item">'
                .$this->ShowCheckboxField('personType[ip_ua]', 'Y', (array("id" => "personTypeIua"))).
                ' <label for="personTypeFua">'.GetMessage("WIZ_PERSON_TYPE_FIZ_UA").'</label>
					</div>';
        $this->content .= '
				</div>
			</div>
			<div class="wizard-catalog-form-item">'.GetMessage("WIZ_PERSON_TYPE").'<div>
		</div>';
        $this->content .= '</div>';
    }

    function OnPostForm()
    {
        $wizard = &$this->GetWizard();
        $personType = $wizard->GetVar("personType");

        if (empty($personType["ip"]) && empty($personType["ur"]))
            $this->SetError(GetMessage('WIZ_NO_PT'));
    }

}

class PaySystem extends CWizardStep
{
    function InitStep()
    {
        $wizard =& $this->GetWizard();
        $this->SetStepID("pay_system");
        $this->SetTitle(GetMessage("WIZ_STEP_PS"));

        $this->SetNextStep("data_collection");
        $this->SetNextCaption(GetMessage("NEXT_BUTTON"));

        $this->SetPrevStep("person_type");
        $this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));


        if(LANGUAGE_ID == "ru")
        {
            $shopLocalization = $wizard->GetVar("shopLocalization", true);

            if ($shopLocalization == "ua")
                $wizard->SetDefaultVars(
                    Array(
                        "paysystem" => Array(
                            "cash" => "Y",
                            "oshad" => "Y",
                            "bill" => "Y",
                        ),
                        "delivery" => Array(
                            "courier" => "Y",
                            "self" => "Y",
                        )
                    )
                );
            else
                $wizard->SetDefaultVars(
                    Array(
                        "paysystem" => Array(
                            "cash" => "Y",
                            "sber" => "Y",
                            "bill" => "Y",
                            "collect" => "Y"  //cash on delivery
                        ),
                        "delivery" => Array(
                            "courier" => "Y",
                            "self" => "Y",
                            "rus_post" => "N",
                            "ua_post" => "N",
                            "kaz_post" => "N"
                        )
                    )
                );
        }
        else
        {
            $wizard->SetDefaultVars(
                Array(
                    "paysystem" => Array(
                        "cash" => "Y",
                        "paypal" => "Y",
                    ),
                    "delivery" => Array(
                        "courier" => "Y",
                        "self" => "Y",
                        "dhl" => "Y",
                        "ups" => "Y",
                    )
                )
            );
        }
    }

    function OnPostForm()
    {
        $wizard = &$this->GetWizard();
        $paysystem = $wizard->GetVar("paysystem");

        if (
            empty($paysystem["cash"]) && empty($paysystem["sber"])  && empty($paysystem["bill"])
            && empty($paysystem["paypal"])
            && empty($paysystem["oshad"])
            && empty($paysystem["collect"])
        )
        {
            $this->SetError(GetMessage('WIZ_NO_PS'));
        }
    }

    function ShowStep()
    {

        $wizard =& $this->GetWizard();
        $shopLocalization = $wizard->GetVar("shopLocalization", true);

        $personType = $wizard->GetVar("personType");

        $arAutoDeliveries = array();
        if (CModule::IncludeModule("sale"))
        {
            $dbRes = \Bitrix\Sale\Delivery\Services\Table::getList(array(
                'filter' => array(
                    '=CLASS_NAME' => array(
                        '\Sale\Handlers\Delivery\SpsrHandler',
                        '\Bitrix\Sale\Delivery\Services\Automatic',
                        '\Sale\Handlers\Delivery\AdditionalHandler'
                    )
                ),
                'select' => array('ID', 'CODE', 'ACTIVE', 'CLASS_NAME')
            ));

            while($dlv = $dbRes->fetch())
            {
                if($dlv['CLASS_NAME'] == '\Sale\Handlers\Delivery\SpsrHandler')
                {
                    $arAutoDeliveries['spsr'] = $dlv['ACTIVE'];
                }
                elseif($dlv['CLASS_NAME'] == '\Sale\Handlers\Delivery\AdditionalHandler' && $dlv['CONFIG']['MAIN']['SERVICE_TYPE'] == 'RUSPOST')
                {
                    $arAutoDeliveries['ruspost'] = $dlv['ACTIVE'];
                }
                elseif(!empty($dlv['CODE']))
                {
                    $arAutoDeliveries[$dlv['CODE']] = $dlv['ACTIVE'];
                }
            }
        }

        $siteID = WizardServices::GetCurrentSiteID($wizard->GetVar("wizSiteID"));
        $this->content .= '<div class="wizard-input-form">';
        $this->content .= '
		<div class="wizard-input-form-block">
			<div class="wizard-catalog-title">'.GetMessage("WIZ_PAY_SYSTEM_TITLE").'</div>
			<div>
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">
					<div class="wizard-catalog-form-item">
						'.$this->ShowCheckboxField('paysystem[cash]', 'Y', (array("id" => "paysystemC"))).
            ' <label for="paysystemC">'.GetMessage("WIZ_PAY_SYSTEM_C").'</label>
					</div>';

        if(LANGUAGE_ID == "ru")
        {
            if($shopLocalization == "ua" && ($personType["fiz"] == "Y" || $personType["fiz_ua"] == "Y"))
                $this->content .=
                    '<div class="wizard-catalog-form-item">'.
                    $this->ShowCheckboxField('paysystem[oshad]', 'Y', (array("id" => "paysystemO"))).
                    ' <label for="paysystemS">'.GetMessage("WIZ_PAY_SYSTEM_O").'</label>
							</div>';
            if ($shopLocalization == "ru")
            {
                if ($personType["fiz"] == "Y")
                    $this->content .=
                        '<div class="wizard-catalog-form-item">'.
                        $this->ShowCheckboxField('paysystem[sber]', 'Y', (array("id" => "paysystemS"))).
                        ' <label for="paysystemS">'.GetMessage("WIZ_PAY_SYSTEM_S").'</label>
								</div>';
                if ($personType["fiz"] == "Y" || $personType["ur"] == "Y")
                    $this->content .=
                        '<div class="wizard-catalog-form-item">'.
                        $this->ShowCheckboxField('paysystem[collect]', 'Y', (array("id" => "paysystemCOL"))).
                        ' <label for="paysystemCOL">'.GetMessage("WIZ_PAY_SYSTEM_COL").'</label>
								</div>';
            }
            if($personType["ur"] == "Y")
            {
                $this->content .=
                    '<div class="wizard-catalog-form-item">'.
                    $this->ShowCheckboxField('paysystem[bill]', 'Y', (array("id" => "paysystemB"))).
                    ' <label for="paysystemB">';
                if ($shopLocalization == "ua")
                    $this->content .= GetMessage("WIZ_PAY_SYSTEM_B_UA");
                else
                    $this->content .= GetMessage("WIZ_PAY_SYSTEM_B");
                $this->content .= '</label>
							</div>';
            }
        }
        else
        {
            $this->content .=
                '<div class="wizard-catalog-form-item">'.
                $this->ShowCheckboxField('paysystem[paypal]', 'Y', (array("id" => "paysystemP"))).
                ' <label for="paysystemP">PayPal</label>
						</div>';
        }
        $this->content .= '</div>
			</div>
			<div class="wizard-catalog-form-item">'.GetMessage("WIZ_PAY_SYSTEM").'</div>
		</div>';

        if (
            LANGUAGE_ID != "ru" ||
            LANGUAGE_ID == "ru" &&
            (
                COption::GetOptionString("eshop", "wizard_installed", "N", $siteID) != "Y"
                || $shopLocalization == "ru" && ($arAutoDeliveries["rus_post"] != "Y")
                || $shopLocalization == "ua" && ($arAutoDeliveries["ua_post"] != "Y")
                || $shopLocalization == "kz" && ($arAutoDeliveries["kaz_post"] != "Y")
            )
        )
        {
            $deliveryNotes = array();
            $deliveryContent = '<div class="wizard-input-form-field wizard-input-form-field-checkbox">';

            if(COption::GetOptionString("b2bcabinet", "wizard_installed", "N", $siteID) != "Y")
            {
                $deliveryContent .= '<div class="wizard-catalog-form-item">
					'.$this->ShowCheckboxField('delivery[courier]', 'Y', (array("id" => "deliveryC"))).
                    ' <label for="deliveryC">'.GetMessage("WIZ_DELIVERY_C").'</label>
				</div>
				<div class="wizard-catalog-form-item">
					'.$this->ShowCheckboxField('delivery[self]', 'Y', (array("id" => "deliveryS"))).
                    ' <label for="deliveryS">'.GetMessage("WIZ_DELIVERY_S").'</label>
				</div>';
            }

            if(LANGUAGE_ID == "ru")
            {
                if ($shopLocalization == "ru")
                {
                    if ($arAutoDeliveries["ruspost"] != "Y")
                    {
                        \Bitrix\Sale\Delivery\Services\Manager::getHandlersList();
                        $res = \Sale\Handlers\Delivery\AdditionalHandler::getSupportedServicesList();

                        if(!empty($res['NOTES']) && is_array($res['NOTES']))
                        {
                            $deliveryNotes = $res['NOTES'];
                        }
                        else
                        {
                            $deliveryContent .= '
								<div class="wizard-catalog-form-item">'.
                                $this->ShowCheckboxField('delivery[ruspost]', 'Y', (array("id" => "deliveryR"))).
                                ' <label for="deliveryR">'.GetMessage("WIZ_DELIVERY_R").'</label>
								</div>';
                        }
                    }

                    if ($arAutoDeliveries["rus_post"] != "Y")
                    {
                        $deliveryContent .=
                            '<div class="wizard-catalog-form-item">'.
                            $this->ShowCheckboxField('delivery[rus_post]', 'Y', (array("id" => "deliveryR2"))).
                            ' <label for="deliveryR2">'.GetMessage("WIZ_DELIVERY_R2").'</label>
							</div>';
                    }

                    if ($arAutoDeliveries["rus_post_first"] != "Y")
                    {
                        $deliveryContent .=
                            '<div class="wizard-catalog-form-item">'.
                            $this->ShowCheckboxField('delivery[rus_post_first]', 'Y', (array("id" => "deliveryRF"))).
                            ' <label for="deliveryRF">'.GetMessage("WIZ_DELIVERY_RF").'</label>
							</div>';
                    }
                }
                elseif ($shopLocalization == "ua")
                {
                    if ($arAutoDeliveries["ua_post"] != "Y")
                        $deliveryContent .=
                            '<div class="wizard-catalog-form-item">'.
                            $this->ShowCheckboxField('delivery[ua_post]', 'Y', (array("id" => "deliveryU"))).
                            ' <label for="deliveryU">'.GetMessage("WIZ_DELIVERY_UA").'</label>
							</div>';
                }
                elseif ($shopLocalization == "kz")
                {
                    if ($arAutoDeliveries["kaz_post"] != "Y")
                        $deliveryContent .=
                            '<div class="wizard-catalog-form-item">'.
                            $this->ShowCheckboxField('delivery[kaz_post]', 'Y', (array("id" => "deliveryK"))).
                            ' <label for="deliveryK">'.GetMessage("WIZ_DELIVERY_KZ").'</label>
							</div>';
                }
            }
            else
            {
                $deliveryContent .=
                    '<div class="wizard-catalog-form-item">'.
                    $this->ShowCheckboxField('delivery[dhl]', 'Y', (array("id" => "deliveryD"))).
                    ' <label for="deliveryD">DHL</label>
					</div>';
                $deliveryContent .=
                    '<div class="wizard-catalog-form-item">'.
                    $this->ShowCheckboxField('delivery[ups]', 'Y', (array("id" => "deliveryU"))).
                    ' <label for="deliveryU">UPS</label>
					</div>
				</div>';
            }

            if(!empty($deliveryNotes))
            {
                $deliveryContent ='
					<link rel="stylesheet" type="text/css" href="/bitrix/wizards/bitrix/eshop/css/style.css">
					<div class="eshop-wizard-info-note-wrap">
						<div class="eshop-wizard-info-note">
							'.implode("<br>\n", $deliveryNotes).'
						</div>
					</div>'.
                    $deliveryContent;
            }

            $this->content  .=
                '<div class="wizard-input-form-block">
					<div class="wizard-catalog-title">'.GetMessage("WIZ_DELIVERY_TITLE").'</div>
					<div>'.
                $deliveryContent.
                '</div>
					<div class="wizard-catalog-form-item">'.GetMessage("WIZ_DELIVERY").'</div>
				</div>';						;
        }

        $this->content .= '
		<div>
			<div class="wizard-catalog-title">'.GetMessage("WIZ_LOCATION_TITLE").'</div>
			<div>
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">';
        if(in_array(LANGUAGE_ID, array("ru", "ua")))
        {
            $this->content .=
                '<div class="wizard-catalog-form-item">'.
                $this->ShowRadioField("locations_csv", "loc_ussr.csv", array("id" => "loc_ussr", "checked" => "checked"))
                ." <label for=\"loc_ussr\">".GetMessage('WSL_STEP2_GFILE_USSR')."</label>
				</div>";
            $this->content .=
                '<div class="wizard-catalog-form-item">'.
                $this->ShowRadioField("locations_csv", "loc_ua.csv", array("id" => "loc_ua"))
                ." <label for=\"loc_ua\">".GetMessage('WSL_STEP2_GFILE_UA')."</label>
				</div>";
            $this->content .=
                '<div class="wizard-catalog-form-item">'.
                $this->ShowRadioField("locations_csv", "loc_kz.csv", array("id" => "loc_kz"))
                ." <label for=\"loc_kz\">".GetMessage('WSL_STEP2_GFILE_KZ')."</label>
				</div>";
        }
        $this->content .=
            '<div class="wizard-catalog-form-item">'.
            $this->ShowRadioField("locations_csv", "loc_usa.csv", array("id" => "loc_usa"))
            ." <label for=\"loc_usa\">".GetMessage('WSL_STEP2_GFILE_USA')."</label>
			</div>";
        $this->content .=
            '<div class="wizard-catalog-form-item">'.
            $this->ShowRadioField("locations_csv", "loc_cntr.csv", array("id" => "loc_cntr"))
            ." <label for=\"loc_cntr\">".GetMessage('WSL_STEP2_GFILE_CNTR')."</label>
			</div>";
        $this->content .=
            '<div class="wizard-catalog-form-item">'.
            $this->ShowRadioField("locations_csv", "", array("id" => "none"))
            ." <label for=\"none\">".GetMessage('WSL_STEP2_GFILE_NONE')."</label>
			</div>";

        $this->content .= '
				</div>
			</div>
		</div>';

        $this->content .= '<div class="wizard-catalog-form-item">'.GetMessage("WIZ_DELIVERY_HINT").'</div>';

        $this->content .= '</div>';
    }
}


class DataCollection extends CWizardStep
{
    function InitStep()
    {
        $wizard =& $this->GetWizard();
        $this->SetStepID("data_collection");
        $this->SetTitle(GetMessage("WIZ_STEP_DC"));

        $this->SetNextStep("data_install");
        $this->SetNextCaption(GetMessage("INSTALL_BUTTON"));

        if($wizard->GetVar("wizInstallMethod") == 'AS_SITE')
        {
            $this->SetPrevStep("pay_system");
            $this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));
        }
        else
        {
            $this->SetPrevStep("site_install_param");
            $this->SetPrevCaption(GetMessage("PREVIOUS_BUTTON"));
        }
    }
}


class DataInstallStep extends CDataInstallWizardStep
{
    function CorrectServices(&$arServices)
    {
        $wizard =& $this->GetWizard();

        $siteId = $wizard->GetVar('wizSiteID');
        $siteDir = $this->getSiteDir($siteId);

        define('WIZARD_SITE_ID', $siteId);
        define('WIZARD_SITE_DIR', $siteDir);

        if($wizard->GetVar("installDemoData") != "Y")
        {
            unset($arServices['iblock']);
        }

        if($_SESSION["BX_ESHOP_LOCATION"] == "Y")
            $this->repeatCurrentService = true;
        else
            $this->repeatCurrentService = false;
    }

    function ShowStep() {
        $wizard =& $this->GetWizard();
        parent::ShowStep();
        $this->content .= '<link rel="stylesheet" href="'.$wizard->GetPath().'/css/b2bcabinet.css">';
    }

    function getSiteDir($siteID) {
        $wizard =& $this->GetWizard();

        if($wizard->GetVar('wizSiteID') == 'Y' && !empty($wizard->GetVar('siteFolder'))) {
            return $wizard->GetVar('siteFolder');
        }

        $arSite = \Bitrix\Main\SiteTable::getList(['filter' => ['LID' => $siteID], 'select' => ['DIR']])->fetch();

        if(!empty($arSite)) {
            return $arSite['DIR'];
        } else {
            return '/';
        }
    }
}

class FinishStep extends CFinishWizardStep
{
    function InitStep()
    {
        $this->SetStepID("finish");
        $this->SetNextStep("finish");
        $this->SetTitle(GetMessage("FINISH_STEP_TITLE"));
        $this->SetNextCaption(GetMessage("wiz_go"));
    }

    function ShowStep()
    {

        $wizard =& $this->GetWizard();
        $this->content .= '<link rel="stylesheet" href="'.$wizard->GetPath().'/css/b2bcabinet.css">';
        if ($wizard->GetVar("proactive") == "Y")
            COption::SetOptionString("statistic", "DEFENCE_ON", "Y");

//        $siteID = WizardServices::GetCurrentSiteID($wizard->GetVar("siteID"));
//        $rsSites = CSite::GetByID($siteID);
//        $siteDir = "/";
//        if ($arSite = $rsSites->Fetch())
//            $siteDir = $arSite["DIR"];

//        $wizard->SetFormActionScript(str_replace("//", "/", $siteDir."/?finish"));
        $wizard->SetFormActionScript("/?finish");

        $this->CreateNewIndex();

//        COption::SetOptionString("main", "wizard_solution", $wizard->solutionName, false, $siteID);

        $this->content .=
            '<table class="wizard-completion-table">
				<tr>
					<td class="wizard-completion-cell">'
            .GetMessage("FINISH_STEP_CONTENT").
            '</td>
				</tr>
			</table>';

        if ($wizard->GetVar("installDemoData") == "Y")
            $this->content .= GetMessage("FINISH_STEP_REINDEX");
    }

}