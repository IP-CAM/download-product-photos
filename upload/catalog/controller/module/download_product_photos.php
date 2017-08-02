<?php
/*
* Download Product Photos Controller
--------------------------------------
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
	
	public function get_image_options(){
		
		$this->load->model('module/download_product_photos');
		
		$product_colors = $this->model_module_download_product_photos->get_product_colors($this->request->post['product_id']);
		
		$this->response->setOutput(json_encode($product_colors));
	}
	
	public function download_images(){
		
		$this->load->model('module/download_product_photos');
		$images = $this->model_module_download_product_photos->get_product_images($this->request->post['option_id']);
		
		$this->response->setOutput($images);
	}
	
	public function get_module_status(){
		
		$this->load->model('module/download_product_photos');
		$this->response->setOutput(json_encode($this->model_module_download_product_photos->get_status()));
		
	}
}

?>