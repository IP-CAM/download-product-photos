<?php
/*
* Download Product Photos
--------------------------
Copyright 2017 Josan Iracheta

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), 
to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, 
and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND 
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*/

class ModelModuleDownloadProductPhotos extends Model{
	
	public function install(){
		
		$this->load->model('setting/setting');
		//set value to false to allow the user to enable the module within the module admin dashboard
		$this->model_setting_setting->editSetting('download_product_photos', false);
		
	}
	
	public function uninstall(){
		
		$this->load->model('setting/setting');
		$this->model_setting_setting->deleteSetting('download_product_photos');
		
	}
	
	//enable or disable the module
	public function toggle($toggle_val){
		
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('download_product_photos', $toggle_val);
		
		return true;
		
	}
	
	public function get_setting(){
		
		$this->load->model('setting/setting');
		return $this->model_setting_setting->getSetting('download_product_photos');
		
	}
	
}
?>