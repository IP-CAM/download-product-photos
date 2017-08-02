<?php
/*
* Download Product Photos - Admin Controller
----------------------------------------------
Copyright 2017 Josan Iracheta

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), 
to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, 
and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND 
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*/

class ControllerModuleDownloadProductPhotos extends Controller{
	
	public function index(){
		
		$this->language->load('module/download_product_photos');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('setting/setting');
		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) { // Start If: Validates and check if data is coming by save (POST) method
		
			$this->model_setting_setting->editSetting('download_product_photos', $this->request->post);      // Parse all the coming data to Setting Model to save it in database.
			$this->session->data['success'] = $this->language->get('text_success'); // To display the success text on data save
			$this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL')); // Redirect to the Module Listing
		}
		
		/*Assign the language data for parsing it to view*/
		$this->data['heading_title'] = $this->language->get('heading_title');
 
		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_content_top'] = $this->language->get('text_content_top');
		$this->data['text_content_bottom'] = $this->language->get('text_content_bottom');      
		$this->data['text_column_left'] = $this->language->get('text_column_left');
		$this->data['text_column_right'] = $this->language->get('text_column_right');
 
		$this->data['entry_code'] = $this->language->get('entry_code');
		$this->data['entry_layout'] = $this->language->get('entry_layout');
		$this->data['entry_position'] = $this->language->get('entry_position');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
 
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_add_module'] = $this->language->get('button_add_module');
		$this->data['button_remove'] = $this->language->get('button_remove');
		
		/*This Block returns the warning if any*/
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}
		/*End Block*/
 
		/*This Block returns the error code if any*/
		if (isset($this->error['code'])) {
			$this->data['error_code'] = $this->error['code'];
		} else {
			$this->data['error_code'] = '';
		}
		/*End Block*/
 
 
		/* Making of Breadcrumbs to be displayed on site*/
		$this->data['breadcrumbs'] = array();
 
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => false
		);
 
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('text_module'),
			'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
 
		$this->data['breadcrumbs'][] = array(
			'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('module/download_product_photos', 'token=' . $this->session->data['token'], 'SSL'),
			'separator' => ' :: '
		);
 
		/* End Breadcrumb Block*/
		
		$this->data['action'] = $this->url->link('module/download_product_photos', 'token=' . $this->session->data['token'], 'SSL'); // URL to be directed when the save button is pressed
 
		$this->data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'); // URL to be redirected when cancel button is pressed
 
		/* This block checks, if the hello world text field is set it parses it to view otherwise get the default hello world text field from the database and parse it*/
 
		if (isset($this->request->post['download_product_photos_text_field'])) {
			$this->data['download_product_photos_text_field'] = $this->request->post['download_product_photos_text_field'];
		} else {
			$this->data['download_product_photos_text_field'] = $this->config->get('download_product_photos_text_field');
		}   
		/* End Block*/
		
		$this->data['modules'] = array();
 
		/* This block parses the Module Settings such as Layout, Position,Status & Order Status to the view*/
		if (isset($this->request->post['download_product_photos_module'])) {
			$this->data['modules'] = $this->request->post['download_product_photos_module'];
		} elseif ($this->config->get('download_product_photos_module')) { 
			$this->data['modules'] = $this->config->get('download_product_photos_module');
		}
		/* End Block*/         
 
		$this->load->model('design/layout'); // Loading the Design Layout Models
 
		$this->data['layouts'] = $this->model_design_layout->getLayouts(); // Getting all the Layouts available on system
 
		$this->template = 'module/download_product_photos.tpl'; // Loading the helloworld.tpl template
		$this->children = array(
			'common/header',
			'common/footer'
		);  // Adding children to our default template i.e., helloworld.tpl 
 
		$this->response->setOutput($this->render()); // Rendering the Output
	}
	
	public function install(){
		
		$this->load->model('module/download_product_photos');
		$this->model_module_download_product_photos->install();
		
	}
	
	public function uninstall(){
		
		$this->load->model('module/download_product_photos');
		$this->model_module_download_product_photos->uninstall();
		
	}
	
	public function get(){
		
		$this->load->model('module/download_product_photos');
		$setting = $this->model_module_download_product_photos->get_setting();
		
		$this->response->setOutput(json_encode($setting));
		
	}
	
	public function save(){
		
		$this->load->model('module/download_product_photos');
		$toggle = $this->request->post['module_setting'];
		$save = $this->model_module_download_product_photos->toggle(array($toggle => $toggle));
		
		$this->response->setOutput($save);
		
	}
	
}