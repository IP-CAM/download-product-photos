/*
* Download Product Photos
----------------------------
* create HTML Elements
* get data from server
----------------------------
Copyright 2017 Josan Iracheta

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), 
to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, 
and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND 
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*/

$(document).ready(function(){
	//get module status
	$.ajax({
		url: 'index.php?route=module/download_product_photos/get_module_status',
		type: 'POST',
		data: {get_status: 'get'}
	}).done(function(data){
			var status = JSON.parse(data);
			//run function if module is enabled
			if(status[Object.keys(status)] == 'true'){
				downloadProductPhotos();
			}
		});
});

function downloadProductPhotos(){
	
	//create download button using existing button on page
	var targetToClone, targetSelector;
	//clone compare button
	if(!document.querySelector('.compare')){
		targetSelector = '[onClick*="addToCompare"]';
	}
	else{
		targetSelector = '.compare';
	}
	targetToClone = document.querySelector(targetSelector);
	var clone = targetToClone.cloneNode(true);
	
	//default Opencart theme has links
	if(clone.children.length === 0){
		clone.innerHTML = 'Download Product Photos';
		clone.removeAttribute('onClick');
	}
	else{
		//if cloned element has child elements
		//remove all of them except for one element where the text will go
		var oneChild = removeAllChildrenExceptOne(clone, 'onClick', 'addToCompare');
		//if parent does not include a child element that has the attribute we're searching for
		//then we have to append the new text content directly to the cloned element
		if(!oneChild){
			clone.innerHTML = '<i class="fa fa-download"></i>&nbsp;Download Product Photos';
			removeAttributesAll(clone);
		}
		else{
			//change text of button
			//and include fontAwesome icon
			clone.lastElementChild.innerHTML = '<i class="fa fa-download"></i>&nbsp;Download Product Photos';
			//remove all attributes from clone
			removeAttributesAll(clone.lastElementChild);
		}
		//copy classes to clone
		clone.classList = targetToClone.classList.value;
		//remove compare class
		clone.classList.remove('compare');
		//add ID
		clone.id = 'dpp-btn';
		//add title for tooltip
		clone.title = 'Download Product Photos';
		/*
		//remove onClick attribute from anchor tagName
		clone.lastElementChild.removeAttribute('onClick');
		//remove any icons it may have
		var ch = clone.children
		//loop through each child and 
		//remove children that have font-awesome class 
		//or are icon elements
		for(var i = 0; i < ch.length; i++){
			if(ch[i].tagName == 'I' || ch[i].classList.contains('fa')){
				$(ch[i]).remove();
			}
		}
		*/
	}
	//default Opencart theme compatibility
	if(targetToClone.previousElementSibling.tagName == 'BR'){
		clone.style.display = 'block';
	}
	//get product ID and append it to clone element
	var pid = document.querySelector('input[name="product_id"]').value;
	clone.dataset.productId = pid;
	//add bootstrap modal data attributes
	clone.dataset.toggle = 'modal';
	clone.dataset.target = '#dpp-modal';

	let stylesToCopy = ['font-family','font-size','border-bottom','text-align','text-transform','padding-top','padding-right','padding-bottom','padding-left','cursor'];
	//copy target styles
	var targetStyles = window.getComputedStyle(targetToClone,null);
	//set target styles to clone
	for(var i = 0; i < stylesToCopy.length; i++){
		var s = stylesToCopy[i];
		clone.style[s] = targetStyles[s];
	}
	//add overflow hidden to be compatible with themes that have small buttons
	clone.style.overflow = 'hidden';
	/*
	stylesToCopy.forEach(function(s){
		clone.style[s] = targetStyles[s];
	});
	*/
	//hide button for debugging
	//clone.style.display = 'none';
	//insert clone into page
	targetToClone.insertAdjacentElement('afterend',clone);

	//get product options on page load

//window.addEventListener('load',function(){
	
	//create modal
	var modalElem = document.createElement('div');
	modalElem.className = 'modal';
	modalElem.id = 'dpp-modal';
	modalElem.appendChild(document.createElement('div')).className = 'modal-dialog';
	modalElem.lastChild.appendChild(document.createElement('div')).className = 'modal-content';
	modalElem.querySelector('.modal-content').appendChild(document.createElement('div')).className = 'modal-header';
	modalElem.querySelector('.modal-header').appendChild(document.createElement('h4')).textContent = 'Download Product Photos';
	modalElem.querySelector('.modal-content').appendChild(document.createElement('div')).className = 'modal-body';
	modalElem.querySelector('.modal-body').appendChild(document.createElement('form'));
	modalElem.querySelector('.modal-content').appendChild(document.createElement('div')).className = 'modal-footer';
	modalElem.querySelector('.modal-footer').appendChild(document.createElement('button')).classList.add('btn','btn-primary');
	modalElem.querySelector('.btn').textContent = 'Download';
	modalElem.querySelector('.btn').id = 'dpp-download-btn';
	
	//append it to body
	document.body.appendChild(modalElem);
	
	$.ajax({
		url: 'index.php?route=module/download_product_photos/get_image_options',
		type: 'POST',
		data: {product_id: pid}
	}).done(function(data){
		console.log(data);
		var opt = JSON.parse(data);
		//show each option in modal as a checkbox
		var elem = '<label><input type="checkbox" name="option_id" value="' + opt.id + '">&nbsp;' + opt.model + '</label><br/>';
		//append to modal
		var modal = document.querySelector('#dpp-modal');
		modal.querySelector('.modal-body form').innerHTML = elem;
		//show modal
		//$(modal).modal('show');
	});
	
	$('body').on('click','#dpp-download-btn',function(e){
		e.preventDefault();
		var inputs = $('[name="option_id"]:checked');
		var myForm = $(this).parents('.modal').find('form')[0];

		if(inputs.length == 0){
			return alert('Select a checkbox option!');
		}
		
		$.ajax({
			url: 'index.php?route=module/download_product_photos/download_images',
			type: 'POST',
			processData: false,
			contentType: false,
			data: new FormData(myForm)
		}).done(function(data){
			console.log(data);
			if(data){
				if(!document.querySelector('#dpp-download-link')){
					//insert anchor link into modal that sends user to download page
					var link = document.createElement('a');
					link.className = 'pull-left';
					link.id = 'dpp-download-link';
					link.href = window.location.origin + '/download-product-photos/?file_name=' + data;
					link.style.color = '#2980b9';
					link.setAttribute('target','_blank');
					link.textContent = 'Download Link';
					document.querySelector('#dpp-download-btn').insertAdjacentElement('beforebegin',link);
				}
				else{
					document.querySelector('#dpp-download-link').href = window.location.origin + '/download-product-photos/?file_name=' + data;
				}
			}
			else{
				alert('There was an error. Please try again later.');
			}
			//animate anchor element so user will know it exists
			(function noticeElem(){
				$('#dpp-download-link').animate({opacity:'toggle'},1000,'linear',noticeElem);
			})();
		});
		
	});
	
//});
}

function removeAllChildrenExceptOne(parentNode, attributeName, attributeValue){
	var i = parentNode.childNodes.length;
	while(i--){
		if(!parentNode.childNodes[i].attributes || !parentNode.childNodes[i].attributes[attributeName] || !parentNode.childNodes[i].attributes[attributeName].nodeValue.includes(attributeValue)){
			parentNode.removeChild(parentNode.childNodes[i]);
		}
	}
	return parentNode.childNodes.length;
}
function removeAttributesAll(element){
	var i = element.attributes.length;
	while(i--){
  	element.removeAttribute(element.attributes[i].name);
  }
}


