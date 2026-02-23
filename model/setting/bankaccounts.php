<?php
class ModelSettingBankaccounts extends Model {
	public function refreshAccounts() {
		// rebuild bank accounts for module Bank Accounts
		
		$method_data = array();
		$sort_order = array();
		
		$this->load->model('setting/extension');
		$this->load->model('setting/setting');
		$this->language->load('module/bank_accounts');
		
		$results = $this->model_setting_extension->getInstalled('payment');
		
		foreach ($results as $key => $code) {
			if (substr($code , 0, 4) == 'trf_') {
				$setting = $this->model_setting_setting->getSetting($code);
				if (isset($setting[$code . '_status'])) {
					$method_data[$code]['image'] = $setting[$code . '_image'];
					$method_data[$code]['title'] = $setting[$code . '_title'];
					if ($code == 'trf_cod') {
						$method_data[$code]['info'] = sprintf($this->language->get('text_info_cod'), $setting[$code . '_area'], $setting[$code . '_hours'], $setting[$code . '_phone']);
					} else {
						$method_data[$code]['info'] = sprintf($this->language->get('text_info_bank'), $setting[$code . '_accountno'], $setting[$code . '_accountname']);
						$method_data[$code]['accountno'] = $setting[$code . '_accountno'];
						$method_data[$code]['accountname'] = $setting[$code . '_accountname'];						
					}
					$sort_order[$code] = $setting[$code . '_sort_order'];
				}
			}
		}
					
		array_multisort($sort_order, SORT_ASC, $method_data);	
		
		$setting = array();
		$setting['accounts'] = $method_data;
		$this->model_setting_setting->editSetting('bank_accounts_common', $setting);

	}
}
?>
