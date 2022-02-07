<?
IncludeModuleLangFile( __FILE__ );

class CModuleOptions {
    
    private $module_id = '';
	private $arTabs = [];
	private $arGroups = [];
	private $arOptions = [];
	private $need_access_tab = false;
    public $arCurOptionValues = [];

	public function CModuleOptions($module_id, $arTabs, $arGroups, $arOptions, $need_access_tab = false) {
		$this->module_id = $module_id;
		$this->siteId = false;
		$this->arTabs = $arTabs;
		$this->arGroups = $arGroups;
		$this->arOptions = $arOptions;
		$this->need_access_tab = $need_access_tab;
		
		if($_REQUEST['update']=='Y'&& check_bitrix_sessid()) {
			$this->SaveOptions();
			
			if($this->need_access_tab) {
				$this->SaveGroupRight();
			}
		}
		
		$this->siteId = htmlspecialchars($_REQUEST['site']);
		$this->GetCurOptionValues();
	}

	private function SaveOptions() {
		global $APPLICATION;
        
        $this->siteId = htmlspecialchars($_REQUEST['site']);
		
		$CONS_RIGHT = $APPLICATION->GetGroupRight( $this->module_id );
		
		if($CONS_RIGHT <= "R") {
			echo CAdminMessage::ShowMessage( GetMessage( $this->module_id.'_ERROR_RIGTH' ) );
			return false;
		}
		
		foreach( $this->arOptions as $opt => $arOptParams ) {
			if($arOptParams['TYPE']!='CUSTOM') {
				$val = $_REQUEST[$opt];
				
				if($arOptParams['TYPE'] == 'CHECKBOX' && $val != 'Y')
					$val = 'N';
				elseif(is_array( $val ))
					$val = serialize( $val );
				
				if($arOptParams['TYPE']=='SELECT' && $arOptParams['OTHER']=='Y') {
					COption::SetOptionString( $this->module_id, $opt.'_OTHER', $_REQUEST[$opt.'_OTHER'], '', $this->siteId);
				}
				
				COption::SetOptionString( $this->module_id, $opt, $val, '', $this->siteId);
			}
		}
	}

	private function SaveGroupRight() {
		CMain::DelGroupRight( $this->module_id );
		$GROUP = $_REQUEST['GROUPS'];
		$RIGHT = $_REQUEST['RIGHTS'];
		
		foreach( $GROUP as $k => $v ) {
			if($k==0) {
				COption::SetOptionString( $this->module_id, 'GROUP_DEFAULT_RIGHT', $RIGHT[0], 'Right for groups by default' );
			} else {
				CMain::SetGroupRight( $this->module_id, $GROUP[$k], $RIGHT[$k] );
			}
		}
	}

	private function GetCurOptionValues() {
		foreach( $this->arOptions as $opt => $arOptParams ) {
			if($arOptParams['TYPE']!='CUSTOM') {
				$this->arCurOptionValues[$opt] = COption::GetOptionString( $this->module_id, $opt, $arOptParams['DEFAULT'], $this->siteId );
				
				if(in_array( $arOptParams['TYPE'], ['MSELECT'] ))
					$this->arCurOptionValues[$opt] = unserialize( $this->arCurOptionValues[$opt] );
			}
		}
	}

	public function ShowHTML() {
		global $APPLICATION;
		$arP = [];
		
		foreach( $this->arGroups as $group_id => $group_params )
			$arP[$group_params['TAB']][$group_id] = [];
		
		if(is_array( $this->arOptions )) {
			foreach( $this->arOptions as $option => $arOptParams ) {
				$val = $this->arCurOptionValues[$option];
				
				if($arOptParams['SORT']<0||!isset( $arOptParams['SORT'] ))
					$arOptParams['SORT'] = 0;
				
				$label = (isset( $arOptParams['TITLE'] )&&$arOptParams['TITLE']!='') ? $arOptParams['TITLE'] : '';
				$opt = htmlspecialchars( $option );
				$label .= ':';
				
				switch($arOptParams['TYPE']) {
					case 'CHECKBOX' :
						$input = '<input type="checkbox" name="'.$opt.'" id="'.$opt.'" value="Y"'.($val=='Y' ? ' checked' : '').' '.($arOptParams['REFRESH']=='Y' ? 'onclick="document.forms[\''.$this->module_id.'\'].submit();"' : '').' />';
						break;
						
					case 'TEXT' :
						// $val = base64_decode($val);
						if(!isset( $arOptParams['COLS'] ))
							$arOptParams['COLS'] = 25;
						if(!isset( $arOptParams['ROWS'] ))
							$arOptParams['ROWS'] = 5;
						$input = '<textarea cols="'.$arOptParams['COLS'].'" rows="'.$arOptParams['ROWS'].'" name="'.$opt.'">'.htmlspecialchars($val,null,LANG_CHARSET).'</textarea>';
						if($arOptParams['REFRESH']=='Y')
							$input .= '<input type="submit" name="refresh" value="OK" />';
						break;
						
					case 'SELECT' :
						$input = SelectBoxFromArray( $opt, $arOptParams['VALUES'], $val, '', ($arOptParams['OTHER']=='Y')?'onchange="SelectOther(this.value,\''.$option.'\')"':'', ($arOptParams['REFRESH']=='Y' ? true : false), ($arOptParams['REFRESH']=='Y' ? $this->module_id : '') );
						if($arOptParams['REFRESH']=='Y')
							$input .= '<input type="submit" name="refresh" value="OK" />';
						if($arOptParams['OTHER']=='Y') {
							$value = COption::GetOptionString( $this->module_id, $opt.'_OTHER','' );
							$input .='<br><input type="text" value="'.$value.'" name="'.$opt.'_OTHER" id="'.$opt.'_OTHER" ';
							
							if($val != 'other') {
								$input .='disabled';
							}
							
							$input .= '>';
						}
						break;
						
					case 'MSELECT' :
						$selHTML = '';
						
						if($arOptParams['WIDTH']) {
							$selHTML = 'style="width: '.$arOptParams['WIDTH'].'px"';
						}
						
						if(empty( $arOptParams['VALUES'] )) {
							$arOptParams['VALUES'] = array ();
						}
						
						$input = SelectBoxMFromArray( $opt.'[]', $arOptParams['VALUES'], $val, '', false, $arOptParams['SIZE'], $selHTML );
						
						if($arOptParams['REFRESH']=='Y')
							$input .= '<input type="submit" name="refresh" value="OK" />';
						
						break;
						
					case 'COLORPICKER' :
						if(!isset( $arOptParams['FIELD_SIZE'] ))
							$arOptParams['FIELD_SIZE'] = 25;
						
						ob_start();
						echo '<input id="__CP_PARAM_'.$opt.'" name="'.$opt.'" size="'.$arOptParams['FIELD_SIZE'].'" value="'.htmlspecialchars( $val ).'" type="text" style="float: left;" '.($arOptParams['FIELD_READONLY']=='Y' ? 'readonly' : '').' />
								<script>
									function onSelect_'.$opt.'(color, objColorPicker)
									{
										var oInput = BX("__CP_PARAM_'.$opt.'");
										oInput.value = color;
									}
								</script>';
						$APPLICATION->IncludeComponent( 'bitrix:main.colorpicker', '', Array (
								'SHOW_BUTTON' => 'Y',
								'ID' => $opt,
								'NAME' => GetMessage( "sns.tools1c_choice_color" ),
								'ONSELECT' => 'onSelect_'.$opt
						), false );
						$input = ob_get_clean();
						if($arOptParams['REFRESH']=='Y')
							$input .= '<input type="submit" name="refresh" value="OK" />';
						break;
					case 'FILE' :
						if(!isset( $arOptParams['FIELD_SIZE'] ))
							$arOptParams['FIELD_SIZE'] = 25;
						if(!isset( $arOptParams['BUTTON_TEXT'] ))
							$arOptParams['BUTTON_TEXT'] = '...';
						CAdminFileDialog::ShowScript( Array (
								'event' => 'BX_FD_'.$opt,
								'arResultDest' => Array (
										'FUNCTION_NAME' => 'BX_FD_ONRESULT_'.$opt
								),
								'arPath' => Array (),
								'select' => 'F',
								'operation' => 'O',
								'showUploadTab' => true,
								'showAddToMenuTab' => false,
								'fileFilter' => '',
								'allowAllFiles' => true,
								'SaveConfig' => true
						) );
						$input = '<input id="__FD_PARAM_'.$opt.'" name="'.$opt.'" size="'.$arOptParams['FIELD_SIZE'].'" value="'.htmlspecialchars( $val ).'" type="text" style="float: left;" '.($arOptParams['FIELD_READONLY']=='Y' ? 'readonly' : '').' />
									<input value="'.$arOptParams['BUTTON_TEXT'].'" type="button" onclick="window.BX_FD_'.$opt.'();" />
									<script>
										setTimeout(function(){
											if (BX("bx_fd_input_'.strtolower( $opt ).'"))
												BX("bx_fd_input_'.strtolower( $opt ).'").onclick = window.BX_FD_'.$opt.';
										}, 200);
										window.BX_FD_ONRESULT_'.$opt.' = function(filename, filepath)
										{
											var oInput = BX("__FD_PARAM_'.$opt.'");
											if (typeof filename == "object")
												oInput.value = filename.src;
											else
												oInput.value = (filepath + "/" + filename).replace(/\/\//ig, \'/\');
										}
									</script>';
						if($arOptParams['REFRESH']=='Y')
							$input .= '<input type="submit" name="refresh" value="OK" />';
						break;
						
					case 'CUSTOM' :
						$input = $arOptParams['VALUE'];
						break;

					case 'MULTI':
						break;

					default :
						if(!isset( $arOptParams['SIZE'] ))
							$arOptParams['SIZE'] = 25;
						if(!isset( $arOptParams['MAXLENGTH'] ))
							$arOptParams['MAXLENGTH'] = 255;
						if(!isset( $arOptParams['MULTI'] ))
							$arOptParams['MULTI'] = 'N';
						if($arOptParams['MULTI']=='N') {
							$input = '<input type="'.($arOptParams['TYPE']=='INT' ? 'number' : 'text').'" size="'.$arOptParams['SIZE'].'"';
							if(isset($arOptParams['MIN']))
								$input .='min="'.$arOptParams['MIN'].'"';
							if(isset($arOptParams['MAX']))
								$input .='max="'.$arOptParams['MAX'].'"';
							
							$input .=' maxlength="'.$arOptParams['MAXLENGTH'].'" value="'.htmlspecialchars( $val ).'" name="'.htmlspecialchars( $option ).'" />';
						} else {
							if(isset( $val )&&!empty( $val ))
								$massive = unserialize( $val );
							else
								$massive = array ();
							
							$input = "<script type=\"text/javascript\">
										function ".htmlspecialchars( $option )."(){
										var div = document.createElement(\"div\");
div.innerHTML = \"<input type='".($arOptParams['TYPE']=='INT' ? 'number' : 'text')."' size='".$arOptParams['SIZE']."' maxlength='".$arOptParams['MAXLENGTH']."' name='".htmlspecialchars( $option )."[]' />\";
document.getElementById('".htmlspecialchars( $option )."').appendChild(div);
												}
										</script>

										<span name='".htmlspecialchars( $option )."' id='".htmlspecialchars( $option )."'>";
							if(isset( $massive )&&is_array( $massive )&&(!empty( $massive[0] ))) {
								foreach( $massive as $element )
									if(!empty( $element ))
										$input .= "<input type='".($arOptParams['TYPE']=='INT' ? 'number' : 'text')."' size='".$arOptParams['SIZE']."' maxlength='".$arOptParams['MAXLENGTH']."' name='".htmlspecialchars( $option )."[]' value='".$element."' /><br />";
							} else
								$input .= "<input type='".($arOptParams['TYPE']=='INT' ? 'number' : 'text')."' size='".$arOptParams['SIZE']."' maxlength='".$arOptParams['MAXLENGTH']."' name='".htmlspecialchars( $option )."[]' value='' /><br />";
							$input .= "</span>
													<input type='button' value='+' onclick=\"".htmlspecialchars( $option )."()\">";
						}
						if($arOptParams['REFRESH']=='Y')
							$input .= '<input type="submit" name="refresh" value="OK" />';
						break;
				}
				
				$notes = '';
				
				if(isset( $arOptParams['NOTES'] )&&$arOptParams['NOTES']!='')
					$notes = '<tr><td align="center" colspan="2">
										<div align="center" class="adm-info-message-wrap">
											<div class="adm-info-message">
												'.$arOptParams['NOTES'].'
											</div>
										</div>
									</td></tr>';
					
				$arP[$this->arGroups[$arOptParams['GROUP']]['TAB']][$arOptParams['GROUP']]['OPTIONS'][] = $label!='' ? '<tr><td width="50%">'.$label.'</td><td width="50%">'.$input.'</td></tr>'.$notes.' ' : '<tr><td colspan="2" >'.$input.'</td></tr>'.$notes.' ';
				$arP[$this->arGroups[$arOptParams['GROUP']]['TAB']][$arOptParams['GROUP']]['OPTIONS_SORT'][] = $arOptParams['SORT'];
			}
			
			$tabControl = new CAdminTabControl( 'tabControl', $this->arTabs );
			$tabControl->Begin();
			echo '<form name="'.$this->module_id.'" method="POST" action="'.$APPLICATION->GetCurPage().'?mid='.$this->module_id.'&lang='.LANGUAGE_ID.'&site='.htmlspecialchars($_REQUEST['site']).'" enctype="multipart/form-data">'.bitrix_sessid_post();
			
			foreach( $arP as $tab => $groups ) {
				$tabControl->BeginNextTab();
				foreach( $groups as $group_id => $group ) {
					if(sizeof( $group['OPTIONS_SORT'] )>0) {
						echo '<tr class="heading"><td colspan="2">'.$this->arGroups[$group_id]['TITLE'].'</td></tr>';
						array_multisort( $group['OPTIONS_SORT'], $group['OPTIONS'] );
						
						foreach( $group['OPTIONS'] as $opt )
							echo $opt;
					}
				}
			}
			
			if($this->need_access_tab) {
				$tabControl->BeginNextTab();
				$module_id = $this->module_id;
				require_once ($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/admin/group_rights.php");
			}
			
			$tabControl->Buttons();
			echo '<input type="hidden" value="'.$_REQUEST["tabControl_active_tab"].'" name="tabControl_active_tab" id="tabControl_active_tab"><input type="hidden" name="update" value="Y" />
					<input type="submit" name="save" value="'.GetMessage( "MAIN_SAVE" ).'" />
					</form>';
			bitrix_sessid_post();
			$tabControl->End();
		}
		
		echo '<script>
            function SelectOther(value,id)
            {
                if(value=="other")
                {
                    document.getElementById(id+"_OTHER").removeAttribute("disabled");
                }
                else
                {
                    document.getElementById(id+"_OTHER").setAttribute("disabled","true");
                    document.getElementById(id+"_OTHER").setAttribute("value","");
                    document.getElementById(id+"_OTHER").value="";
                }
            }
        </script>';
	}
}
?>