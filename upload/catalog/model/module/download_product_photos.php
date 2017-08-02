<?php
/*
* Download Product Photos
------------------------------------
// get product_id from product page
// get images for that product
-------------------------------------
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
	
	//get list of option colors if product has them
	public function get_product_colors($product_id){

		//get product name
		$query = $this->db->query("SELECT model FROM ".DB_PREFIX."product WHERE product_id = ".(int)$product_id." ");
		$product_model = array(
			'id' => $product_id,
			'model' => $query->row['model']
			);
					
		return $product_model;
	}
	
	public function get_product_images($id){
		
		$sql = "SELECT image FROM ".DB_PREFIX."product_image WHERE product_id = $id UNION SELECT image FROM ".DB_PREFIX."product WHERE product_id = $id ";
		$query = $this->db->query($sql);
		
		if(!$query->num_rows){
			return false;
		}
		
		foreach($query->rows as $rows){
			//returns incorrect path locally
			//works perfectly on web server
			$images[] = $_SERVER['DOCUMENT_ROOT'] . '/image/' . $rows['image'];
		}

		//return http_build_query($images);
		
		//download images using paths returned from query
		//store them in a Zip file
		$zip_file_path = tempnam(sys_get_temp_dir(),'download-product-photos');
		//$zip_file_path = sys_get_temp_dir() . '/' . date('H-i-s') . '-download-product-photos.zip';
		//create the file
		//touch($zip_file_path);
		$zip = create_zip($images,$zip_file_path,true);

		if($zip){
			return $zip_file_path;
		}
		else{
			//return "zip error";
			return false;
		}
		
	}
	
	public function get_status(){
		
		$this->load->model('setting/setting');
		$status = $this->model_setting_setting->getSetting('download_product_photos');
		return $status;
		
	}
}

/* creates a compressed zip file */
function create_zip(array $files,$destination = '',$overwrite = false) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { /*return "destination does not exist";*/return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}

	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {

			return false;
			//return "cannot open";
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,basename($file));
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;

		//close the zip -- done!
		$zip->close();
		//var_dump($cz);
		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
		//return "no valid files";
	}
}
?>
