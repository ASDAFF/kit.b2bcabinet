<?
if( !defined( "B_PROLOG_INCLUDED" ) || B_PROLOG_INCLUDED !== true )
	die();

if( !CModule::IncludeModule( 'sale' ) )
	return;

use Bitrix\Sale\BusinessValue, Bitrix\Sale\OrderStatus, Bitrix\Sale\DeliveryStatus, Bitrix\Main\Localization\Loc, Bitrix\Sale\Internals\BusinessValueTable, Bitrix\Sale\Internals\BusinessValuePersonDomainTable;

WizardServices::IncludeServiceLang( "step1.php", 'ru' );

$moduleID = 'sotbit.b2bcabinet';
$paysystem = $wizard->GetDefaultVar( "paysystem" );

$arPersonTypeNames = array();
$dbPerson = CSalePersonType::GetList( array(), array(
		"LID" => WIZARD_SITE_ID
) );

while ( $arPerson = $dbPerson->Fetch() )
{
	$arPersonTypeNames[$arPerson["ID"]] = $arPerson["NAME"];
}

$arGeneralInfo["personType"]["ip"] = array_search( GetMessage( "SALE_WIZARD_PERSON_1" ), $arPersonTypeNames );
$arGeneralInfo["personType"]["ur"] = array_search( GetMessage( "SALE_WIZARD_PERSON_2" ), $arPersonTypeNames );

if( $bRus || COption::GetOptionString( $moduleID, "wizard_installed", "N", WIZARD_SITE_ID ) != "Y" || WIZARD_INSTALL_DEMO_DATA )
{
	$personType = $wizard->GetDefaultVar( "personType" );
	$paysystem = $wizard->GetDefaultVar( "paysystem" );

	if( $shopLocalization == "ru" )
	{
		if( CSaleLang::GetByID( WIZARD_SITE_ID ) )
			CSaleLang::Update( WIZARD_SITE_ID, array(
					"LID" => WIZARD_SITE_ID,
					"CURRENCY" => "RUB"
			) );
		else
			CSaleLang::Add( array(
					"LID" => WIZARD_SITE_ID,
					"CURRENCY" => "RUB"
			) );

		$shopLocation = $wizard->GetDefaultVar( "shopLocation" );
		COption::SetOptionString( $moduleID, "shopLocation", $shopLocation, false, WIZARD_SITE_ID );
		$shopOfName = $wizard->GetDefaultVar( "shopOfName" );
		COption::SetOptionString( $moduleID, "shopOfName", $shopOfName, false, WIZARD_SITE_ID );
		$shopAdr = $wizard->GetDefaultVar( "shopAdr" );
		COption::SetOptionString( $moduleID, "shopAdr", $shopAdr, false, WIZARD_SITE_ID );

		$shopINN = $wizard->GetDefaultVar( "shopINN" );
		COption::SetOptionString( $moduleID, "shopINN", $shopINN, false, WIZARD_SITE_ID );
		$shopKPP = $wizard->GetDefaultVar( "shopKPP" );
		COption::SetOptionString( $moduleID, "shopKPP", $shopKPP, false, WIZARD_SITE_ID );
		$shopNS = $wizard->GetDefaultVar( "shopNS" );
		COption::SetOptionString( $moduleID, "shopNS", $shopNS, false, WIZARD_SITE_ID );
		$shopBANK = $wizard->GetDefaultVar( "shopBANK" );
		COption::SetOptionString( $moduleID, "shopBANK", $shopBANK, false, WIZARD_SITE_ID );
		$shopBANKREKV = $wizard->GetDefaultVar( "shopBANKREKV" );
		COption::SetOptionString( $moduleID, "shopBANKREKV", $shopBANKREKV, false, WIZARD_SITE_ID );
		$shopKS = $wizard->GetDefaultVar( "shopKS" );
		COption::SetOptionString( $moduleID, "shopKS", $shopKS, false, WIZARD_SITE_ID );
		$siteStamp = $wizard->GetDefaultVar( "siteStamp" );
		if( $siteStamp == "" )
			$siteStamp = COption::GetOptionString( $moduleID, "siteStamp", "", WIZARD_SITE_ID );
	}
	elseif( $shopLocalization == "ua" )
	{
		if( CSaleLang::GetByID( WIZARD_SITE_ID ) )
			CSaleLang::Update( WIZARD_SITE_ID, array(
					"LID" => WIZARD_SITE_ID,
					"CURRENCY" => "UAH"
			) );
		else
			CSaleLang::Add( array(
					"LID" => WIZARD_SITE_ID,
					"CURRENCY" => "UAH"
			) );

		$shopLocation = $wizard->GetDefaultVar( "shopLocation_ua" );
		COption::SetOptionString( $moduleID, "shopLocation_ua", $shopLocation, false, WIZARD_SITE_ID );
		$shopOfName = $wizard->GetDefaultVar( "shopOfName_ua" );
		COption::SetOptionString( $moduleID, "shopOfName_ua", $shopOfName, false, WIZARD_SITE_ID );
		$shopAdr = $wizard->GetDefaultVar( "shopAdr_ua" );
		COption::SetOptionString( $moduleID, "shopAdr_ua", $shopAdr, false, WIZARD_SITE_ID );

		$shopEGRPU_ua = $wizard->GetDefaultVar( "shopEGRPU_ua" );
		COption::SetOptionString( $moduleID, "shopEGRPU_ua", $shopEGRPU_ua, false, WIZARD_SITE_ID );
		$shopINN_ua = $wizard->GetDefaultVar( "shopINN_ua" );
		COption::SetOptionString( $moduleID, "shopINN_ua", $shopINN_ua, false, WIZARD_SITE_ID );
		$shopNDS_ua = $wizard->GetDefaultVar( "shopNDS_ua" );
		COption::SetOptionString( $moduleID, "shopNDS_ua", $shopNDS_ua, false, WIZARD_SITE_ID );
		$shopNS_ua = $wizard->GetDefaultVar( "shopNS_ua" );
		COption::SetOptionString( $moduleID, "shopNS_ua", $shopNS_ua, false, WIZARD_SITE_ID );
		$shopBank_ua = $wizard->GetDefaultVar( "shopBank_ua" );
		COption::SetOptionString( $moduleID, "shopBank_ua", $shopBank_ua, false, WIZARD_SITE_ID );
		$shopMFO_ua = $wizard->GetDefaultVar( "shopMFO_ua" );
		COption::SetOptionString( $moduleID, "shopMFO_ua", $shopMFO_ua, false, WIZARD_SITE_ID );
		$shopPlace_ua = $wizard->GetDefaultVar( "shopPlace_ua" );
		COption::SetOptionString( $moduleID, "shopPlace_ua", $shopPlace_ua, false, WIZARD_SITE_ID );
		$shopFIO_ua = $wizard->GetDefaultVar( "shopFIO_ua" );
		COption::SetOptionString( $moduleID, "shopFIO_ua", $shopFIO_ua, false, WIZARD_SITE_ID );
		$shopTax_ua = $wizard->GetDefaultVar( "shopTax_ua" );
		COption::SetOptionString( $moduleID, "shopTax_ua", $shopTax_ua, false, WIZARD_SITE_ID );
	}

	$siteTelephone = $wizard->GetDefaultVar( "siteTelephone" );
	COption::SetOptionString( $moduleID, "siteTelephone", $siteTelephone, false, WIZARD_SITE_ID );
	$shopEmail = $wizard->GetDefaultVar( "shopEmail" );
	COption::SetOptionString( $moduleID, "shopEmail", $shopEmail, false, WIZARD_SITE_ID );
	$siteName = $wizard->GetDefaultVar( "siteName" );
	COption::SetOptionString( $moduleID, "siteName", $siteName, false, WIZARD_SITE_ID );

	$arPaySystems = Array();
	if( $paysystem["cash"] == "Y" )
	{
		$arPaySystems[] = array(
				'PAYSYSTEM' => array(
						"NAME" => GetMessage( "SALE_WIZARD_PS_CASH" ),
						"PSA_NAME" => GetMessage( "SALE_WIZARD_PS_CASH" ),
						"SORT" => 80,
						"ACTIVE" => "Y",
						"DESCRIPTION" => GetMessage( "SALE_WIZARD_PS_CASH_DESCR" ),
						"ACTION_FILE" => "cash",
						"RESULT_FILE" => "",
						"NEW_WINDOW" => "N",
						"PARAMS" => "",
						"HAVE_PAYMENT" => "Y",
						"HAVE_ACTION" => "N",
						"HAVE_RESULT" => "N",
						"HAVE_PREPAY" => "N",
						"HAVE_RESULT_RECEIVE" => "N"
				),
				'PERSON_TYPE' => array(
						$arGeneralInfo["personType"]["ip"]
				)
		);

	}
	if( $paysystem["collect"] == "Y" )
	{
		$arPaySystems[] = array(
				'PAYSYSTEM' => array(
						"NAME" => GetMessage( "SALE_WIZARD_PS_COLLECT" ),
						"SORT" => 110,
						"ACTIVE" => "Y",
						"DESCRIPTION" => GetMessage( "SALE_WIZARD_PS_COLLECT_DESCR" ),
						"PSA_NAME" => GetMessage( "SALE_WIZARD_PS_COLLECT" ),
						"ACTION_FILE" => "cashondeliverycalc",
						"RESULT_FILE" => "",
						"NEW_WINDOW" => "N",
						"HAVE_PAYMENT" => "Y",
						"HAVE_ACTION" => "N",
						"HAVE_RESULT" => "N",
						"HAVE_PREPAY" => "N",
						"HAVE_RESULT_RECEIVE" => "N"
				),
				'PERSON_TYPE' => array(
						$arGeneralInfo["personType"]["ip"],
						$arGeneralInfo["personType"]["ur"]
				)
		);
	}
	if( $personType["ip"] == "Y" && $shopLocalization != "ua" )
	{
		if( $bRus )
		{
			$arPaySystems[] = array(
					'PAYSYSTEM' => array(
							"NAME" => GetMessage( "SALE_WIZARD_YMoney" ),
							"SORT" => 50,
							"DESCRIPTION" => GetMessage( "SALE_WIZARD_YMoney_DESC" ),
							"PSA_NAME" => GetMessage( "SALE_WIZARD_YMoney" ),
							"ACTION_FILE" => "yandex",
							"RESULT_FILE" => "",
							"NEW_WINDOW" => "N",
							"PS_MODE" => "PC",
							"HAVE_PAYMENT" => "Y",
							"HAVE_ACTION" => "N",
							"HAVE_RESULT" => "N",
							"HAVE_PREPAY" => "N",
							"HAVE_RESULT_RECEIVE" => "Y"
					),
					'PERSON_TYPE' => array(
							$arGeneralInfo["personType"]["ip"]
					),
					"BIZVAL" => array(
							'' => array(
									"PAYMENT_ID" => array(
											"TYPE" => "PAYMENT",
											"VALUE" => "ID"
									),
									"PAYMENT_DATE_INSERT" => array(
											"TYPE" => "PAYMENT",
											"VALUE" => "DATE_BILL"
									),
									"PAYMENT_SHOULD_PAY" => array(
											"TYPE" => "PAYMENT",
											"VALUE" => "SUM"
									),
									"PS_IS_TEST" => array(
											"VALUE" => "Y"
									),
									"PS_CHANGE_STATUS_PAY" => array(
											"VALUE" => "Y"
									),
									"YANDEX_SHOP_ID" => array(
											"TYPE" => "",
											"VALUE" => ""
									),
									"YANDEX_SCID" => array(
											"TYPE" => "",
											"VALUE" => ""
									),
									"YANDEX_SHOP_KEY" => array(
											"TYPE" => "",
											"VALUE" => ""
									)
							)
					)
			);

			$logo = $_SERVER["DOCUMENT_ROOT"] . WIZARD_SERVICE_RELATIVE_PATH . "/images/yandex_cards.png";
			$arPicture = CFile::MakeFileArray( $logo );
			$arPaySystems[] = array(
					'PAYSYSTEM' => array(
							"NAME" => GetMessage( "SALE_WIZARD_YCards" ),
							"SORT" => 60,
							"DESCRIPTION" => GetMessage( "SALE_WIZARD_YCards_DESC" ),
							"PSA_NAME" => GetMessage( "SALE_WIZARD_YCards" ),
							"ACTION_FILE" => "yandex",
							"RESULT_FILE" => "",
							"NEW_WINDOW" => "N",
							"HAVE_PAYMENT" => "Y",
							"HAVE_ACTION" => "N",
							"HAVE_RESULT" => "N",
							"HAVE_PREPAY" => "N",
							"HAVE_RESULT_RECEIVE" => "Y",
							"PS_MODE" => "AC",
							"LOGOTIP" => $arPicture
					),
					"BIZVAL" => array(
							'' => array(
									"PAYMENT_ID" => array(
											"TYPE" => "ORDER",
											"VALUE" => "ID"
									),
									"PAYMENT_DATE_INSERT" => array(
											"TYPE" => "PAYMENT",
											"VALUE" => "DATE_BILL"
									),
									"PAYMENT_SHOULD_PAY" => array(
											"TYPE" => "PAYMENT",
											"VALUE" => "SUM"
									),
									"PS_IS_TEST" => array(
											"VALUE" => "Y"
									),
									"PS_CHANGE_STATUS_PAY" => array(
											"VALUE" => "Y"
									),
									"YANDEX_SHOP_ID" => array(
											"TYPE" => "",
											"VALUE" => ""
									),
									"YANDEX_SCID" => array(
											"TYPE" => "",
											"VALUE" => ""
									),
									"YANDEX_SHOP_KEY" => array(
											"TYPE" => "",
											"VALUE" => ""
									)
							)
					),
					"PERSON_TYPE" => array(
							$arGeneralInfo["personType"]["ip"]
					)
			);
			$logo = $_SERVER["DOCUMENT_ROOT"] . WIZARD_SERVICE_RELATIVE_PATH . "/images/yandex_terminals.png";
			$arPicture = CFile::MakeFileArray( $logo );
			$arPaySystems[] = array(
					'PAYSYSTEM' => array(
							"NAME" => GetMessage( "SALE_WIZARD_YTerminals" ),
							"SORT" => 70,
							"DESCRIPTION" => GetMessage( "SALE_WIZARD_YTerminals_DESC" ),
							"PSA_NAME" => GetMessage( "SALE_WIZARD_YTerminals" ),
							"ACTION_FILE" => "yandex",
							"RESULT_FILE" => "",
							"NEW_WINDOW" => "N",
							"HAVE_PAYMENT" => "Y",
							"HAVE_ACTION" => "N",
							"HAVE_RESULT" => "N",
							"HAVE_PREPAY" => "N",
							"HAVE_RESULT_RECEIVE" => "Y",
							"LOGOTIP" => $arPicture
					),
					"BIZVAL" => array(
							'' => array(
									"PAYMENT_ID" => array(
											"TYPE" => "ORDER",
											"VALUE" => "ID"
									),
									"PAYMENT_DATE_INSERT" => array(
											"TYPE" => "PAYMENT",
											"VALUE" => "DATE_BILL"
									),
									"PAYMENT_SHOULD_PAY" => array(
											"TYPE" => "PAYMENT",
											"VALUE" => "SUM"
									),
									"PS_IS_TEST" => array(
											"VALUE" => "Y"
									),
									"PS_CHANGE_STATUS_PAY" => array(
											"VALUE" => "Y"
									),
									"YANDEX_SHOP_ID" => array(
											"TYPE" => "",
											"VALUE" => ""
									),
									"YANDEX_SCID" => array(
											"TYPE" => "",
											"VALUE" => ""
									),
									"YANDEX_SHOP_KEY" => array(
											"TYPE" => "",
											"VALUE" => ""
									)
							)
					),
					"PERSON_TYPE" => array(
							$arGeneralInfo["personType"]["ip"]
					)
			);
			$arPaySystems[] = array(
					'PAYSYSTEM' => array(
							"NAME" => GetMessage( "SALE_WIZARD_PS_WM" ),
							"SORT" => 90,
							"ACTIVE" => "N",
							"DESCRIPTION" => GetMessage( "SALE_WIZARD_PS_WM_DESCR" ),
							"PSA_NAME" => GetMessage( "SALE_WIZARD_PS_WM" ),
							"ACTION_FILE" => "webmoney",
							"RESULT_FILE" => "",
							"NEW_WINDOW" => "Y",
							"PARAMS" => "",
							"HAVE_PAYMENT" => "Y",
							"HAVE_ACTION" => "N",
							"HAVE_RESULT" => "Y",
							"HAVE_PREPAY" => "N",
							"HAVE_RESULT_RECEIVE" => "N"
					),
					"PERSON_TYPE" => array(
							$arGeneralInfo["personType"]["ip"]
					)
			);

			if( $paysystem["sber"] == "Y" )
			{
				$arPaySystems[] = array(
						'PAYSYSTEM' => array(
								"NAME" => GetMessage( "SALE_WIZARD_PS_SB" ),
								"SORT" => 110,
								"DESCRIPTION" => GetMessage( "SALE_WIZARD_PS_SB_DESCR" ),
								"PSA_NAME" => GetMessage( "SALE_WIZARD_PS_SB" ),
								"ACTION_FILE" => "sberbank",
								"RESULT_FILE" => "",
								"NEW_WINDOW" => "Y",
								"HAVE_PAYMENT" => "Y",
								"HAVE_ACTION" => "N",
								"HAVE_RESULT" => "N",
								"HAVE_PREPAY" => "N",
								"HAVE_RESULT_RECEIVE" => "N"
						),
						"PERSON_TYPE" => array(
								$arGeneralInfo["personType"]["ip"]
						),
						"BIZVAL" => array(
								'' => array(
										"SELLER_COMPANY_NAME" => array(
												"TYPE" => "",
												"VALUE" => $shopOfName
										),
										"SELLER_COMPANY_INN" => array(
												"TYPE" => "",
												"VALUE" => $shopINN
										),
										"SELLER_COMPANY_KPP" => array(
												"TYPE" => "",
												"VALUE" => $shopKPP
										),
										"SELLER_COMPANY_BANK_ACCOUNT" => array(
												"TYPE" => "",
												"VALUE" => $shopNS
										),
										"SELLER_COMPANY_BANK_NAME" => array(
												"TYPE" => "",
												"VALUE" => $shopBANK
										),
										"SELLER_COMPANY_BANK_BIC" => array(
												"TYPE" => "",
												"VALUE" => $shopBANKREKV
										),
										"SELLER_COMPANY_BANK_ACCOUNT_CORR" => array(
												"TYPE" => "",
												"VALUE" => $shopKS
										),
										"PAYMENT_ID" => array(
												"TYPE" => "PAYMENT",
												"VALUE" => "ACCOUNT_NUMBER"
										),
										"PAYMENT_DATE_INSERT" => array(
												"TYPE" => "PAYMENT",
												"VALUE" => "DATE_INSERT_DATE"
										),
										"BUYER_PERSON_FIO" => array(
												"TYPE" => "PROPERTY",
												"VALUE" => "FIO"
										),
										"BUYER_PERSON_ZIP" => array(
												"TYPE" => "PROPERTY",
												"VALUE" => "ZIP"
										),
										"BUYER_PERSON_COUNTRY" => array(
												"TYPE" => "PROPERTY",
												"VALUE" => "LOCATION_COUNTRY"
										),
										"BUYER_PERSON_REGION" => array(
												"TYPE" => "PROPERTY",
												"VALUE" => "LOCATION_REGION"
										),
										"BUYER_PERSON_CITY" => array(
												"TYPE" => "PROPERTY",
												"VALUE" => "LOCATION_CITY"
										),
										"BUYER_PERSON_ADDRESS_FACT" => array(
												"TYPE" => "PROPERTY",
												"VALUE" => "ADDRESS"
										),
										"PAYMENT_SHOULD_PAY" => array(
												"TYPE" => "PAYMENT",
												"VALUE" => "SUM"
										)
								)
						)
				);
			}
		}
		else
		{
			$arPaySystems[] = array(
					'PAYSYSTEM' => array(
							"NAME" => "PayPal",
							"SORT" => 90,
							"DESCRIPTION" => "",
							"PSA_NAME" => "PayPal",
							"ACTION_FILE" => "paypal",
							"RESULT_FILE" => "",
							"NEW_WINDOW" => "N",
							"HAVE_PAYMENT" => "Y",
							"HAVE_ACTION" => "N",
							"HAVE_RESULT" => "N",
							"HAVE_PREPAY" => "N",
							"HAVE_RESULT_RECEIVE" => "Y"
					),
					"BIZVAL" => array(
							'' => array(
									"PAYMENT_ID" => array(
											"TYPE" => "PAYMENT",
											"VALUE" => "ID"
									),
									"PAYMENT_DATE_INSERT" => array(
											"TYPE" => "PAYMENT",
											"VALUE" => "DATE_BILL_DATE"
									),
									"PAYMENT_SHOULD_PAY" => array(
											"TYPE" => "PAYMENT",
											"VALUE" => "SUM"
									),
									"PAYMENT_CURRENCY" => array(
											"TYPE" => "PAYMENT",
											"VALUE" => "CURRENCY"
									)
							)
					),
					"PERSON_TYPE" => array(
							$arGeneralInfo["personType"]["ip"]
					)
			);
		}
	}

	if( $personType["ur"] == "Y" && $paysystem["sb"] == "Y" && $shopLocalization != "ua" )
	{
		$arPaySystems[] = array(
				'PAYSYSTEM' => array(
						"NAME" => GetMessage( "SALE_WIZARD_PS_SOTBIT_BILL" ),
						"SORT" => 100,
						"DESCRIPTION" => "",
						"PSA_NAME" => GetMessage( "SALE_WIZARD_PS_SOTBIT_BILL" ),
						"ACTION_FILE" => "billsotbit",
						"RESULT_FILE" => "",
						"NEW_WINDOW" => "Y",
						"HAVE_PAYMENT" => "Y",
						"HAVE_ACTION" => "N",
						"HAVE_RESULT" => "N",
						"HAVE_PREPAY" => "N",
						"HAVE_RESULT_RECEIVE" => "N"
				),
				"PERSON_TYPE" => array(
						$arGeneralInfo["personType"]["ur"]
				),
				"BIZVAL" => array(
						$arGeneralInfo["personType"]["ur"] => array(
								"PAYMENT_DATE_INSERT_SOTBIT" => Array(
										"TYPE" => "PAYMENT",
										"VALUE" => "DATE_BILL_DATE"
								),
								"SELLER_COMPANY_NAME_SOTBIT" => Array(
										"TYPE" => "VALUE",
										"VALUE" => $shopOfName
								),
								"SELLER_COMPANY_ADDRESS_SOTBIT" => Array(
										"TYPE" => "VALUE",
										"VALUE" => $shopAdr
								),
								"SELLER_COMPANY_PHONE_SOTBIT" => Array(
										"TYPE" => "VALUE",
										"VALUE" => $siteTelephone
								),
								"SELLER_COMPANY_INN_SOTBIT" => Array(
										"TYPE" => "VALUE",
										"VALUE" => $shopINN
								),
								"SELLER_COMPANY_KPP_SOTBIT" => Array(
										"TYPE" => "VALUE",
										"VALUE" => $shopKPP
								),
								"SELLER_COMPANY_BANK_ACCOUNT_SOTBIT" => Array(
										"TYPE" => "VALUE",
										"VALUE" => $shopNS
								),
								"SELLER_COMPANY_BANK_ACCOUNT_CORR_SOTBIT" => Array(
										"TYPE" => "VALUE",
										"VALUE" => $shopKS
								),
								"SELLER_COMPANY_BANK_BIC_SOTBIT" => Array(
										"TYPE" => "VALUE",
										"VALUE" => $shopBANKREKV
								),
								"BUYER_PERSON_COMPANY_NAME_SOTBIT" => Array(
										"TYPE" => "PROPERTY",
										"VALUE" => "COMPANY_NAME"
								),
								"BUYER_PERSON_COMPANY_INN_SOTBIT" => Array(
										"TYPE" => "PROPERTY",
										"VALUE" => "INN"
								),
								"BUYER_PERSON_COMPANY_ADDRESS_SOTBIT" => Array(
										"TYPE" => "PROPERTY",
										"VALUE" => "COMPANY_ADR"
								),
								"BUYER_PERSON_COMPANY_PHONE_SOTBIT" => Array(
										"TYPE" => "PROPERTY",
										"VALUE" => "PHONE"
								),
								"BUYER_PERSON_COMPANY_FAX_SOTBIT" => Array(
										"TYPE" => "PROPERTY",
										"VALUE" => "FAX"
								),
								"BUYER_PERSON_COMPANY_NAME_CONTACT_SOTBIT" => Array(
										"TYPE" => "PROPERTY",
										"VALUE" => "CONTACT_PERSON"
								),
								"BILL_PATH_TO_STAMP_SOTBIT" => Array(
										"TYPE" => "FILE",
										"VALUE" => $siteStamp
								)
						)
				)
		);
	}

	if( $personType["ip"] == "Y" && $paysystem["sb"] == "Y" && $shopLocalization != "ua" )
	{
		$arPaySystems[] = array(
				'PAYSYSTEM' => array(
						"NAME" => GetMessage( "SALE_WIZARD_PS_SOTBIT_BILL" ),
						"SORT" => 100,
						"DESCRIPTION" => "",
						"PSA_NAME" => GetMessage( "SALE_WIZARD_PS_SOTBIT_BILL" ),
						"ACTION_FILE" => "billsotbit",
						"RESULT_FILE" => "",
						"NEW_WINDOW" => "Y",
						"HAVE_PAYMENT" => "Y",
						"HAVE_ACTION" => "N",
						"HAVE_RESULT" => "N",
						"HAVE_PREPAY" => "N",
						"HAVE_RESULT_RECEIVE" => "N"
				),
				"PERSON_TYPE" => array(
						$arGeneralInfo["personType"]["ip"]
				),
				"BIZVAL" => array(
						$arGeneralInfo["personType"]["ip"] => array(
								"PAYMENT_DATE_INSERT_SOTBIT" => Array(
										"TYPE" => "PAYMENT",
										"VALUE" => "DATE_BILL_DATE"
								),
								"SELLER_COMPANY_NAME_SOTBIT" => Array(
										"TYPE" => "VALUE",
										"VALUE" => $shopOfName
								),
								"SELLER_COMPANY_ADDRESS_SOTBIT" => Array(
										"TYPE" => "VALUE",
										"VALUE" => $shopAdr
								),
								"SELLER_COMPANY_PHONE_SOTBIT" => Array(
										"TYPE" => "VALUE",
										"VALUE" => $siteTelephone
								),
								"SELLER_COMPANY_INN_SOTBIT" => Array(
										"TYPE" => "VALUE",
										"VALUE" => $shopINN
								),
								"SELLER_COMPANY_KPP_SOTBIT" => Array(
										"TYPE" => "VALUE",
										"VALUE" => $shopKPP
								),
								"SELLER_COMPANY_BANK_ACCOUNT_SOTBIT" => Array(
										"TYPE" => "VALUE",
										"VALUE" => $shopNS
								),
								"SELLER_COMPANY_BANK_ACCOUNT_CORR_SOTBIT" => Array(
										"TYPE" => "VALUE",
										"VALUE" => $shopKS
								),
								"SELLER_COMPANY_BANK_BIC_SOTBIT" => Array(
										"TYPE" => "VALUE",
										"VALUE" => $shopBANKREKV
								),
								"BUYER_PERSON_COMPANY_NAME_SOTBIT" => Array(
										"TYPE" => "PROPERTY",
										"VALUE" => "COMPANY_NAME"
								),
								"BUYER_PERSON_COMPANY_INN_SOTBIT" => Array(
										"TYPE" => "PROPERTY",
										"VALUE" => "INN"
								),
								"BUYER_PERSON_COMPANY_ADDRESS_SOTBIT" => Array(
										"TYPE" => "PROPERTY",
										"VALUE" => "COMPANY_ADR"
								),
								"BUYER_PERSON_COMPANY_PHONE_SOTBIT" => Array(
										"TYPE" => "PROPERTY",
										"VALUE" => "PHONE"
								),
								"BUYER_PERSON_COMPANY_FAX_SOTBIT" => Array(
										"TYPE" => "PROPERTY",
										"VALUE" => "FAX"
								),
								"BUYER_PERSON_COMPANY_NAME_CONTACT_SOTBIT" => Array(
										"TYPE" => "PROPERTY",
										"VALUE" => "CONTACT_PERSON"
								),
								"BILL_PATH_TO_STAMP_SOTBIT" => Array(
										"TYPE" => "FILE",
										"VALUE" => $siteStamp
								)
						)
				)
		);
	}

	if( $personType["ur"] == "Y" && $paysystem["bill"] == "Y" && $shopLocalization != "ua" )
	{
		$arPaySystems[] = array(
				'PAYSYSTEM' => array(
						"NAME" => GetMessage( "SALE_WIZARD_PS_BILL" ),
						"SORT" => 100,
						"DESCRIPTION" => "",
						"PSA_NAME" => GetMessage( "SALE_WIZARD_PS_BILL" ),
						"ACTION_FILE" => "bill",
						"RESULT_FILE" => "",
						"NEW_WINDOW" => "Y",
						"HAVE_PAYMENT" => "Y",
						"HAVE_ACTION" => "N",
						"HAVE_RESULT" => "N",
						"HAVE_PREPAY" => "N",
						"HAVE_RESULT_RECEIVE" => "N"
				),
				"PERSON_TYPE" => array(
						$arGeneralInfo["personType"]["ur"]
				),
				"BIZVAL" => array(
						$arGeneralInfo["personType"]["ur"] => array(
								"PAYMENT_DATE_INSERT" => Array(
										"TYPE" => "PAYMENT",
										"VALUE" => "DATE_BILL_DATE"
								),
								"SELLER_COMPANY_NAME" => Array(
										"TYPE" => "VALUE",
										"VALUE" => $shopOfName
								),
								"SELLER_COMPANY_ADDRESS" => Array(
										"TYPE" => "VALUE",
										"VALUE" => $shopAdr
								),
								"SELLER_COMPANY_PHONE" => Array(
										"TYPE" => "VALUE",
										"VALUE" => $siteTelephone
								),
								"SELLER_COMPANY_INN" => Array(
										"TYPE" => "VALUE",
										"VALUE" => $shopINN
								),
								"SELLER_COMPANY_KPP" => Array(
										"TYPE" => "VALUE",
										"VALUE" => $shopKPP
								),
								"SELLER_COMPANY_BANK_ACCOUNT" => Array(
										"TYPE" => "VALUE",
										"VALUE" => $shopNS
								),
								"SELLER_COMPANY_BANK_ACCOUNT_CORR" => Array(
										"TYPE" => "VALUE",
										"VALUE" => $shopKS
								),
								"SELLER_COMPANY_BANK_BIC" => Array(
										"TYPE" => "VALUE",
										"VALUE" => $shopBANKREKV
								),
								"BUYER_PERSON_COMPANY_NAME" => Array(
										"TYPE" => "PROPERTY",
										"VALUE" => "COMPANY_NAME"
								),
								"BUYER_PERSON_COMPANY_INN" => Array(
										"TYPE" => "PROPERTY",
										"VALUE" => "INN"
								),
								"BUYER_PERSON_COMPANY_ADDRESS" => Array(
										"TYPE" => "PROPERTY",
										"VALUE" => "COMPANY_ADR"
								),
								"BUYER_PERSON_COMPANY_PHONE" => Array(
										"TYPE" => "PROPERTY",
										"VALUE" => "PHONE"
								),
								"BUYER_PERSON_COMPANY_FAX" => Array(
										"TYPE" => "PROPERTY",
										"VALUE" => "FAX"
								),
								"BUYER_PERSON_COMPANY_NAME_CONTACT" => Array(
										"TYPE" => "PROPERTY",
										"VALUE" => "CONTACT_PERSON"
								),
								"BILL_PATH_TO_STAMP" => Array(
										"TYPE" => "FILE",
										"VALUE" => $siteStamp
								)
						)
				)
		);
//		if( $moduleID == 'sotbit.b2bcabinet' )
//		{
			$last_key = end( array_keys( $arPaySystems ) );
			$logo = $_SERVER["DOCUMENT_ROOT"] . WIZARD_SERVICE_RELATIVE_PATH . "/images/bill.png";
			$arPicture = CFile::MakeFileArray( $logo );
			$arPaySystems[$last_key]['PAYSYSTEM']['LOGOTIP'] = $arPicture;
//		}
	}

	if( $personType["ip"] == "Y" && $paysystem["bill"] == "Y" && $shopLocalization != "ua" )
	{
		$arPaySystems[] = array(
				'PAYSYSTEM' => array(
						"NAME" => GetMessage( "SALE_WIZARD_PS_BILL" ),
						"SORT" => 100,
						"DESCRIPTION" => "",
						"PSA_NAME" => GetMessage( "SALE_WIZARD_PS_BILL" ),
						"ACTION_FILE" => "bill",
						"RESULT_FILE" => "",
						"NEW_WINDOW" => "Y",
						"HAVE_PAYMENT" => "Y",
						"HAVE_ACTION" => "N",
						"HAVE_RESULT" => "N",
						"HAVE_PREPAY" => "N",
						"HAVE_RESULT_RECEIVE" => "N"
				),
				"PERSON_TYPE" => array(
						$arGeneralInfo["personType"]["ip"]
				),
				"BIZVAL" => array(
						'' => array(
								"PAYMENT_DATE_INSERT" => Array(
										"TYPE" => "PAYMENT",
										"VALUE" => "DATE_BILL_DATE"
								),
								"SELLER_COMPANY_NAME" => Array(
										"TYPE" => "",
										"VALUE" => $shopOfName
								),
								"SELLER_COMPANY_ADDRESS" => Array(
										"TYPE" => "",
										"VALUE" => $shopAdr
								),
								"SELLER_COMPANY_PHONE" => Array(
										"TYPE" => "",
										"VALUE" => $siteTelephone
								),
								"SELLER_COMPANY_INN" => Array(
										"TYPE" => "",
										"VALUE" => $shopINN
								),
								"SELLER_COMPANY_KPP" => Array(
										"TYPE" => "",
										"VALUE" => $shopKPP
								),
								"SELLER_COMPANY_BANK_ACCOUNT" => Array(
										"TYPE" => "",
										"VALUE" => $shopNS
								),
								"SELLER_COMPANY_BANK_ACCOUNT_CORR" => Array(
										"TYPE" => "",
										"VALUE" => $shopKS
								),
								"SELLER_COMPANY_BANK_BIC" => Array(
										"TYPE" => "",
										"VALUE" => $shopBANKREKV
								),
								"BUYER_PERSON_COMPANY_NAME" => Array(
										"TYPE" => "PROPERTY",
										"VALUE" => "COMPANY_NAME"
								),
								"BUYER_PERSON_COMPANY_INN" => Array(
										"TYPE" => "PROPERTY",
										"VALUE" => "INN"
								),
								"BUYER_PERSON_COMPANY_ADDRESS" => Array(
										"TYPE" => "PROPERTY",
										"VALUE" => "COMPANY_ADR"
								),
								"BUYER_PERSON_COMPANY_PHONE" => Array(
										"TYPE" => "PROPERTY",
										"VALUE" => "PHONE"
								),
								"BUYER_PERSON_COMPANY_FAX" => Array(
										"TYPE" => "PROPERTY",
										"VALUE" => "FAX"
								),
								"BUYER_PERSON_COMPANY_NAME_CONTACT" => Array(
										"TYPE" => "PROPERTY",
										"VALUE" => "CONTACT_PERSON"
								),
								"BILL_PATH_TO_STAMP" => Array(
										"TYPE" => "",
										"VALUE" => $siteStamp
								)
						)
				)
		);
//		if( $moduleID == 'sotbit.b2bcabinet' )
//		{
			$last_key = end( array_keys( $arPaySystems ) );
			$logo = $_SERVER["DOCUMENT_ROOT"] . WIZARD_SERVICE_RELATIVE_PATH . "/images/bill.png";
			$arPicture = CFile::MakeFileArray( $logo );
			$arPaySystems[$last_key]['PAYSYSTEM']['LOGOTIP'] = $arPicture;
//		}
	}

	// Ukraine
	if( $shopLocalization == "ua" )
	{
		// oshadbank
		if( ($personType["ip"] == "Y" || $personType["ip_ua"] == "Y") && $paysystem["oshad"] == "Y" )
		{
			$arPaySystems[] = array(
					'PAYSYSTEM' => array(
							"NAME" => GetMessage( "SALE_WIZARD_PS_OS" ),
							"SORT" => 90,
							"DESCRIPTION" => GetMessage( "SALE_WIZARD_PS_OS_DESCR" ),
							"PSA_NAME" => GetMessage( "SALE_WIZARD_PS_OS" ),
							"ACTION_FILE" => "/bitrix/modules/sale/payment/oshadbank",
							"RESULT_FILE" => "",
							"NEW_WINDOW" => "Y",
							"HAVE_PAYMENT" => "Y",
							"HAVE_ACTION" => "N",
							"HAVE_RESULT" => "N",
							"HAVE_PREPAY" => "N",
							"HAVE_RESULT_RECEIVE" => "N"
					),
					"PERSON_TYPE" => array(
							$arGeneralInfo["personType"]["ip"],
							$arGeneralInfo["personType"]["ip_ua"]
					),
					"BIZVAL" => array(
							'' => array(
									"RECIPIENT_NAME" => array(
											"TYPE" => "",
											"VALUE" => $shopOfName
									),
									"RECIPIENT_ID" => array(
											"TYPE" => "",
											"VALUE" => $shopEGRPU_ua
									),
									"RECIPIENT_NUMBER" => array(
											"TYPE" => "",
											"VALUE" => $shopNS_ua
									),
									"RECIPIENT_BANK" => array(
											"TYPE" => "",
											"VALUE" => $shopBank_ua
									),
									"RECIPIENT_CODE_BANK" => array(
											"TYPE" => "",
											"VALUE" => $shopMFO_ua
									),
									"PAYER_FIO" => array(
											"TYPE" => "PROPERTY",
											"VALUE" => "FIO"
									),
									"PAYER_ADRES" => array(
											"TYPE" => "PROPERTY",
											"VALUE" => "ADDRESS"
									),
									"ORDER_ID" => array(
											"TYPE" => "ORDER",
											"VALUE" => "ID"
									),
									"DATE_INSERT" => array(
											"TYPE" => "ORDER",
											"VALUE" => "DATE_INSERT_DATE"
									),
									"PAYER_CONTACT_PERSON" => array(
											"TYPE" => "PROPERTY",
											"VALUE" => "FIO"
									),
									"PAYER_INDEX" => array(
											"TYPE" => "PROPERTY",
											"VALUE" => "ZIP"
									),
									"PAYER_COUNTRY" => array(
											"TYPE" => "PROPERTY",
											"VALUE" => "LOCATION_COUNTRY"
									),
									"PAYER_TOWN" => array(
											"TYPE" => "PROPERTY",
											"VALUE" => "LOCATION_CITY"
									),
									"SHOULD_PAY" => array(
											"TYPE" => "ORDER",
											"VALUE" => "PRICE"
									)
							)
					)
			);
		}
		if( $personType["ip"] == "Y" )
		{
			$arPaySystems[] = array(
					'PAYSYSTEM' => array(
							"NAME" => GetMessage( "SALE_WIZARD_YMoney" ),
							"SORT" => 60,
							"DESCRIPTION" => GetMessage( "SALE_WIZARD_YMoney_DESC" ),
							"PSA_NAME" => GetMessage( "SALE_WIZARD_YMoney" ),
							"ACTION_FILE" => "yandex",
							"RESULT_FILE" => "",
							"NEW_WINDOW" => "N",
							"PS_MODE" => "PC",
							"HAVE_PAYMENT" => "Y",
							"HAVE_ACTION" => "N",
							"HAVE_RESULT" => "N",
							"HAVE_PREPAY" => "N",
							"HAVE_RESULT_RECEIVE" => "Y"
					),
					"PERSON_TYPE" => array(
							$arGeneralInfo["personType"]["ip"]
					),
					"PARAMS" => array(
							'' => array(
									"PAYMENT_ID" => array(
											"TYPE" => "PAYMENT",
											"VALUE" => "ID"
									),
									"PAYMENT_DATE_INSERT" => array(
											"TYPE" => "PAYMENT",
											"VALUE" => "DATE_BILL"
									),
									"PAYMENT_SHOULD_PAY" => array(
											"TYPE" => "PAYMENT",
											"VALUE" => "SUM"
									)
							)
					)
			);
			$arPaySystems[] = array(
					'PAYSYSTEM' => array(
							"NAME" => GetMessage( "SALE_WIZARD_YCards" ),
							"SORT" => 70,
							"DESCRIPTION" => GetMessage( "SALE_WIZARD_YCards_DESC" ),
							"PSA_NAME" => GetMessage( "SALE_WIZARD_YCards" ),
							"ACTION_FILE" => "yandex",
							"RESULT_FILE" => "",
							"NEW_WINDOW" => "N",
							"PS_MODE" => "AC",
							"HAVE_PAYMENT" => "Y",
							"HAVE_ACTION" => "N",
							"HAVE_RESULT" => "N",
							"HAVE_PREPAY" => "N",
							"HAVE_RESULT_RECEIVE" => "Y"
					),
					"PERSON_TYPE" => array(
							$arGeneralInfo["personType"]["ip"]
					),
					"BIZVAL" => array(
							'' => array(
									"PAYMENT_ID" => array(
											"TYPE" => "PAYMENT",
											"VALUE" => "ID"
									),
									"PAYMENT_DATE_INSERT" => array(
											"TYPE" => "PAYMENT",
											"VALUE" => "DATE_BILL"
									),
									"PAYMENT_SHOULD_PAY" => array(
											"TYPE" => "PAYMENT",
											"VALUE" => "SUM"
									)
							)
					)
			);
//			if( $moduleID == 'sotbit.b2bcabinet' )
//			{
				$last_key = end( array_keys( $arPaySystems ) );
				$logo = $_SERVER["DOCUMENT_ROOT"] . WIZARD_SERVICE_RELATIVE_PATH . "/images/card.png";
				$arPicture = CFile::MakeFileArray( $logo );
				$arPaySystems[$last_key]['PAYSYSTEM']['LOGOTIP'] = $arPicture;
//			}
			$arPaySystems[] = array(
					'PAYSYSTEM' => array(
							"NAME" => GetMessage( "SALE_WIZARD_YTerminals" ),
							"SORT" => 80,
							"DESCRIPTION" => GetMessage( "SALE_WIZARD_YTerminals_DESC" ),
							"PSA_NAME" => GetMessage( "SALE_WIZARD_YTerminals" ),
							"ACTION_FILE" => "yandex",
							"RESULT_FILE" => "",
							"NEW_WINDOW" => "N",
							"HAVE_PAYMENT" => "Y",
							"HAVE_ACTION" => "N",
							"HAVE_RESULT" => "N",
							"HAVE_PREPAY" => "N",
							"HAVE_RESULT_RECEIVE" => "Y",
							"PS_MODE" => "GP"
					),
					"PERSON_TYPE" => array(
							$arGeneralInfo["personType"]["ip"]
					),
					"BIZVAL" => array(
							'' => array(
									"PAYMENT_ID" => array(
											"TYPE" => "PAYMENT",
											"VALUE" => "ID"
									),
									"PAYMENT_DATE_INSERT" => array(
											"TYPE" => "PAYMENT",
											"VALUE" => "DATE_BILL"
									),
									"PAYMENT_SHOULD_PAY" => array(
											"TYPE" => "PAYMENT",
											"VALUE" => "SUM"
									)
							)
					)
			);
//			if( $moduleID == 'sotbit.b2bcabinet' )
//			{
				$last_key = end( array_keys( $arPaySystems ) );
				$logo = $_SERVER["DOCUMENT_ROOT"] . WIZARD_SERVICE_RELATIVE_PATH . "/images/card.png";
				$arPicture = CFile::MakeFileArray( $logo );
				$arPaySystems[$last_key]['PAYSYSTEM']['LOGOTIP'] = $arPicture;
//			}
		}
		// bill
		if( $paysystem["bill"] == "Y" )
		{
			$arPaySystem['PAYSYSTEM'] = array(
					"NAME" => GetMessage( "SALE_WIZARD_PS_BILL" ),
					"PSA_NAME" => GetMessage( "SALE_WIZARD_PS_BILL" ),
					"ACTION_FILE" => "billua",
					"RESULT_FILE" => "",
					"NEW_WINDOW" => "Y",
					"HAVE_PAYMENT" => "Y",
					"HAVE_ACTION" => "N",
					"HAVE_RESULT" => "N",
					"HAVE_PREPAY" => "N",
					"HAVE_RESULT_RECEIVE" => "N"
			);

			$arPaySystem['PERSON_TYPE'] = array();
			$arPaySystem['BIZVAL'] = array();

			if( $personType["ur"] == "Y" )
			{
				$arPaySystem['PERSON_TYPE'][] = $arGeneralInfo["personType"]["ur"];
				$arPaySystem['BIZVAL'][$arGeneralInfo["personType"]["ur"]] = array(
						"PAYMENT_DATE_INSERT" => array(
								"TYPE" => "ORDER",
								"VALUE" => "DATE_INSERT_DATE"
						),
						"SELLER_COMPANY_NAME" => array(
								"TYPE" => "",
								"VALUE" => $shopOfName
						),
						"SELLER_COMPANY_ADDRESS" => array(
								"TYPE" => "",
								"VALUE" => $shopAdr
						),
						"SELLER_COMPANY_PHONE" => array(
								"TYPE" => "",
								"VALUE" => $siteTelephone
						),
						"SELLER_COMPANY_IPN" => array(
								"TYPE" => "",
								"VALUE" => $shopINN_ua
						),
						"SELLER_COMPANY_EDRPOY" => array(
								"TYPE" => "",
								"VALUE" => $shopEGRPU_ua
						),
						"SELLER_COMPANY_BANK_ACCOUNT" => array(
								"TYPE" => "",
								"VALUE" => $shopNS_ua
						),
						"SELLER_COMPANY_BANK_NAME" => array(
								"TYPE" => "",
								"VALUE" => $shopBank_ua
						),
						"SELLER_COMPANY_MFO" => array(
								"TYPE" => "",
								"VALUE" => $shopMFO_ua
						),
						"SELLER_COMPANY_PDV" => array(
								"TYPE" => "",
								"VALUE" => $shopNDS_ua
						),
						"PAYMENT_ID" => array(
								"TYPE" => "ORDER",
								"VALUE" => "ID"
						),
						"SELLER_COMPANY_SYS" => array(
								"TYPE" => "",
								"VALUE" => $shopTax_ua
						),
						"BUYER_PERSON_COMPANY_NAME" => array(
								"TYPE" => "PROPERTY",
								"VALUE" => "COMPANY_NAME"
						),
						"BUYER_PERSON_COMPANY_ADDRESS" => array(
								"TYPE" => "PROPERTY",
								"VALUE" => "COMPANY_ADR"
						),
						"BUYER_PERSON_COMPANY_PHONE" => array(
								"TYPE" => "PROPERTY",
								"VALUE" => "PHONE"
						),
						"BUYER_PERSON_COMPANY_FAX" => array(
								"TYPE" => "PROPERTY",
								"VALUE" => "FAX"
						),
						"BILLUA_PATH_TO_STAMP" => array(
								"TYPE" => "",
								"VALUE" => $siteStamp
						)
				);
			}

			if( $personType["ip"] == "Y" )
			{
				$arPaySystem['PERSON_TYPE'][] = $arGeneralInfo["personType"]["ip"];
				$arPaySystem['BIZVAL'][$arGeneralInfo["personType"]["ip"]] = array(
						"PAYMENT_DATE_INSERT" => array(
								"TYPE" => "ORDER",
								"VALUE" => "DATE_INSERT_DATE"
						),
						"SELLER_COMPANY_NAME" => array(
								"TYPE" => "",
								"VALUE" => $shopOfName
						),
						"SELLER_COMPANY_ADDRESS" => array(
								"TYPE" => "",
								"VALUE" => $shopAdr
						),
						"SELLER_COMPANY_PHONE" => array(
								"TYPE" => "",
								"VALUE" => $siteTelephone
						),
						"SELLER_COMPANY_IPN" => array(
								"TYPE" => "",
								"VALUE" => $shopINN_ua
						),
						"SELLER_COMPANY_EDRPOY" => array(
								"TYPE" => "",
								"VALUE" => $shopEGRPU_ua
						),
						"SELLER_COMPANY_BANK_ACCOUNT" => array(
								"TYPE" => "",
								"VALUE" => $shopNS_ua
						),
						"SELLER_COMPANY_BANK_NAME" => array(
								"TYPE" => "",
								"VALUE" => $shopBank_ua
						),
						"SELLER_COMPANY_MFO" => array(
								"TYPE" => "",
								"VALUE" => $shopMFO_ua
						),
						"SELLER_COMPANY_PDV" => array(
								"TYPE" => "",
								"VALUE" => $shopNDS_ua
						),
						"PAYMENT_ID" => array(
								"TYPE" => "ORDER",
								"VALUE" => "ID"
						),
						"SELLER_COMPANY_SYS" => array(
								"TYPE" => "",
								"VALUE" => $shopTax_ua
						),
						"BUYER_PERSON_COMPANY_NAME" => array(
								"TYPE" => "PROPERTY",
								"VALUE" => "FIO"
						),
						"BUYER_PERSON_COMPANY_ADDRESS" => array(
								"TYPE" => "PROPERTY",
								"VALUE" => "ADDRESS"
						),
						"BUYER_PERSON_COMPANY_PHONE" => array(
								"TYPE" => "PROPERTY",
								"VALUE" => "PHONE"
						),
						"BUYER_PERSON_COMPANY_FAX" => array(
								"TYPE" => "PROPERTY",
								"VALUE" => "FAX"
						),
						"BILLUA_PATH_TO_STAMP" => array(
								"TYPE" => "",
								"VALUE" => $siteStamp
						)
				);
			}

			if( $personType["ip_ua"] == "Y" )
			{
				$arPaySystem['PERSON_TYPE'][] = $arGeneralInfo["personType"]["ip_ua"];
				$arPaySystem['BIZVAL'][$arGeneralInfo["personType"]["ip_ua"]] = array(
						"PAYMENT_DATE_INSERT" => array(
								"TYPE" => "ORDER",
								"VALUE" => "DATE_INSERT_DATE"
						),
						"SELLER_COMPANY_NAME" => array(
								"TYPE" => "",
								"VALUE" => $shopOfName
						),
						"SELLER_COMPANY_ADDRESS" => array(
								"TYPE" => "",
								"VALUE" => $shopAdr
						),
						"SELLER_COMPANY_PHONE" => array(
								"TYPE" => "",
								"VALUE" => $siteTelephone
						),
						"SELLER_COMPANY_IPN" => array(
								"TYPE" => "",
								"VALUE" => $shopINN_ua
						),
						"SELLER_COMPANY_EDRPOY" => array(
								"TYPE" => "",
								"VALUE" => $shopEGRPU_ua
						),
						"SELLER_COMPANY_BANK_ACCOUNT" => array(
								"TYPE" => "",
								"VALUE" => $shopNS_ua
						),
						"SELLER_COMPANY_BANK_NAME" => array(
								"TYPE" => "",
								"VALUE" => $shopBank_ua
						),
						"SELLER_COMPANY_MFO" => array(
								"TYPE" => "",
								"VALUE" => $shopMFO_ua
						),
						"SELLER_COMPANY_PDV" => array(
								"TYPE" => "",
								"VALUE" => $shopNDS_ua
						),
						"PAYMENT_ID" => array(
								"TYPE" => "ORDER",
								"VALUE" => "ID"
						),
						"SELLER_COMPANY_SYS" => array(
								"TYPE" => "",
								"VALUE" => $shopTax_ua
						),
						"BUYER_PERSON_COMPANY_NAME" => array(
								"TYPE" => "PROPERTY",
								"VALUE" => "FIO"
						),
						"BUYER_PERSON_COMPANY_ADDRESS" => array(
								"TYPE" => "PROPERTY",
								"VALUE" => "COMPANY_ADR"
						),
						"BUYER_PERSON_COMPANY_PHONE" => array(
								"TYPE" => "PROPERTY",
								"VALUE" => "PHONE"
						),
						"BUYER_PERSON_COMPANY_FAX" => array(
								"TYPE" => "PROPERTY",
								"VALUE" => "FAX"
						),
						"BILLUA_PATH_TO_STAMP" => array(
								"TYPE" => "",
								"VALUE" => $siteStamp
						)
				);
			}

			$arPaySystems[] = $arPaySystem;
		}
	}
	// }

	foreach( $arPaySystems as $arPaySystem )
	{
		$updateFields = array();

		$val = $arPaySystem['PAYSYSTEM'];
		if( array_key_exists( 'LOGOTIP', $val ) && is_array( $val['LOGOTIP'] ) )
		{
			$updateFields['LOGOTIP'] = $val['LOGOTIP'];
			unset( $val['LOGOTIP'] );
		}

		$dbRes = \Bitrix\Sale\PaySystem\Manager::getList( array(
				'select' => array(
						"ID",
						"NAME"
				),
				'filter' => array(
						"NAME" => $val["NAME"]
				)
		) );
		$tmpPaySystem = $dbRes->fetch();
		if( !$tmpPaySystem )
		{
			$resultAdd = \Bitrix\Sale\Internals\PaySystemActionTable::add( $val );
			if( $resultAdd->isSuccess() )
			{
				$id = $resultAdd->getId();

				if( array_key_exists( 'BIZVAL', $arPaySystem ) && $arPaySystem['BIZVAL'] )
				{
					$arGeneralInfo["paySystem"][$arPaySystem["ACTION_FILE"]] = $id;
					foreach( $arPaySystem['BIZVAL'] as $personType => $codes )
					{
						foreach( $codes as $code => $map )
							\Bitrix\Sale\BusinessValue::setMapping( $code, 'PAYSYSTEM_' . $id, $personType, array(
									'PROVIDER_KEY' => $map['TYPE'],
									'PROVIDER_VALUE' => $map['VALUE']
							), true );
					}
				}

				if( $arPaySystem['PERSON_TYPE'] )
				{
					$params = array(
							'filter' => array(
									"SERVICE_ID" => $id,
									"SERVICE_TYPE" => \Bitrix\Sale\Services\PaySystem\Restrictions\Manager::SERVICE_TYPE_PAYMENT,
									"=CLASS_NAME" => '\Bitrix\Sale\Services\PaySystem\Restrictions\PersonType'
							)
					);

					$dbRes = \Bitrix\Sale\Internals\ServiceRestrictionTable::getList( $params );
					if( !$dbRes->fetch() )
					{
						$fields = array(
								"SERVICE_ID" => $id,
								"SERVICE_TYPE" => \Bitrix\Sale\Services\PaySystem\Restrictions\Manager::SERVICE_TYPE_PAYMENT,
								"SORT" => 100,
								"PARAMS" => array(
										'PERSON_TYPE_ID' => $arPaySystem['PERSON_TYPE']
								)
						);
						\Bitrix\Sale\Services\PaySystem\Restrictions\PersonType::save( $fields );
					}
				}

				$updateFields['PARAMS'] = serialize( array(
						'BX_PAY_SYSTEM_ID' => $id
				) );
				$updateFields['PAY_SYSTEM_ID'] = $id;

				$image = '/bitrix/modules/sale/install/images/sale_payments/' . $val['ACTION_FILE'] . '.png';
				if( (!array_key_exists( 'LOGOTIP', $updateFields ) || !is_array( $updateFields['LOGOTIP'] )) && \Bitrix\Main\IO\File::isFileExists( $_SERVER['DOCUMENT_ROOT'] . $image ) )
				{
					$updateFields['LOGOTIP'] = CFile::MakeFileArray( $image );
					$updateFields['LOGOTIP']['MODULE_ID'] = "sale";
				}

				CFile::SaveForDB( $updateFields, 'LOGOTIP', 'sale/paysystem/logotip' );
				\Bitrix\Sale\Internals\PaySystemActionTable::update( $id, $updateFields );
			}
		}
		else
		{
			$flag = false;

			$params = array(
					'filter' => array(
							"SERVICE_ID" => $tmpPaySystem['ID'],
							"SERVICE_TYPE" => \Bitrix\Sale\Services\PaySystem\Restrictions\Manager::SERVICE_TYPE_PAYMENT,
							"=CLASS_NAME" => '\Bitrix\Sale\Services\PaySystem\Restrictions\PersonType'
					)
			);

			$dbRes = \Bitrix\Sale\Internals\ServiceRestrictionTable::getList( $params );
			$restriction = $dbRes->fetch();

			if( $restriction )
			{
				foreach( $restriction['PARAMS']['PERSON_TYPE_ID'] as $personTypeId )
				{
					if( array_search( $personTypeId, $arPaySystem['PERSON_TYPE'] ) === false )
					{
						$arPaySystem['PERSON_TYPE'][] = $personTypeId;
						$flag = true;
					}
				}

				$restrictionId = $restriction['ID'];
			}

			if( $flag )
			{
				$fields = array(
						"SERVICE_ID" => $restrictionId,
						"SERVICE_TYPE" => \Bitrix\Sale\Services\PaySystem\Restrictions\Manager::SERVICE_TYPE_PAYMENT,
						"SORT" => 100,
						"PARAMS" => array(
								'PERSON_TYPE_ID' => $arPaySystem['PERSON_TYPE']
						)
				);

				\Bitrix\Sale\Services\PaySystem\Restrictions\PersonType::save( $fields, $restrictionId );
			}
		}
	}
}
?>