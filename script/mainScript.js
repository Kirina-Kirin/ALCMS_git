var url_path=[];
var isBinding = false;
$(document).ready(function() {
// Configuration

//********** This part is to call all pages to show in index.php **************

var home =$('a[rel="home"]').attr('href');
var hname = $('a[rel="home"]').attr('name');
url_path.push({url:home,name:hname});
$('#wrapper').load(home);
bindingLink();
createLinkPath();
//**********************************************************************************************
}); //end ready function

function onLoadFile(url){
	var sum="Req=1";
	for (i = 1; i < arguments.length; i++) {
        sum += "&"+arguments[i];
    }
	$("#secretIFrame").attr("src",url+"?"+sum);
}

function doAjaxRequest(tarUrl) {
	var sum="Req=1";
	for (i = 1; i < arguments.length; i++) {
        sum += "&"+arguments[i];
    }
	var data = urlParamToJSON(sum);
    $.ajax({ // create an AJAX call...
        data: data,
		dataType:'json',
        type: 'POST', // GET or POST
        url: tarUrl, // the file to call -- postcodecheck if you're on that same domain, otherwise ajaxActions.php
        success: function(response) { // on success..
			console.log(response.name);
			if(response.name==='error'){
				alert(response.msg);
			}else if(response.name==='redirect'){
				$('#wrapper').empty().load(response.url); 
			}else if(response.name==='loadBack'){
				backToPage(response.url);
			}else{
				loadNewPage(response.url,response.name);
			}
        },
		error: function(xhr, ajaxOptions, thrownError) { // on success..
			console.log("err "+xhr.status+" "+xhr.responseText+" "+thrownError);
        }
    });
}
function doMultipartAjaxRequest(tarForm,tarUrl) {	
      //grab all form data  
    //var formData = $(tarForm).serialize();
	var formData = new FormData(tarForm);
	formData.append("upload_material_button", "Upload");
      $.ajax({
          url: tarUrl,
          type: 'POST',
          data: formData,
          cache: false,
		  enctype: 'multipart/form-data',
          contentType: false,
          processData: false,
          success: function (response) {
            console.log("formData: "+formData);
			alert(response);
			refreshPage();
          },
          error: function () {
              console.log("error in ajax form submission");
          }
      });
      return false;
}

function PopupCenterContent(innerHtml, title, w, h) {
    // Fixes dual-screen position                         Most browsers      Firefox
    var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : screen.left;
    var dualScreenTop = window.screenTop != undefined ? window.screenTop : screen.top;

    width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
    height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

    var left = ((width / 2) - (w / 2)) + dualScreenLeft;
    var top = ((height / 2) - (h / 2)) + dualScreenTop;
    var newWindow = window.open('', title, 
		'scrollbars=yes, width=' + w + ', height=' + h + 
		', top=' + top + ', left=' + left+' toolbar=no,'+ 
		'menubar=no, scrollbars=no, resizable=no, location=no,'+
		'directories=no, status=no, channelmode=no');
		newWindow.document.write(innerHtml);
    // Puts focus on the newWindow
    if (window.focus) {
        newWindow.focus();
    }
}

function loadNewPage(tarUrl, tarName){
	console.log("url_path load new "+url_path.length);
	url_path.push({url:tarUrl,name:tarName});
	$('#wrapper').empty().load(tarUrl); // update the DIV
	createLinkPath();
	bindingLink();
}

function refreshPage(){
	$('#wrapper').empty().load(url_path[url_path.length-1].url);
}

function backToPage(link){
	while(url_path[url_path.length-1].url!=link&&url_path.length>1){
		url_path.pop();
	}
	createLinkPath();
	$('#wrapper').empty().load(url_path[url_path.length-1].url);
}

function urlParamToJSON(url) {
    var hash;
    var myJson = {};
    var hashes = url.slice(url.indexOf('?') + 1).split('&');
    for (var i = 0; i < hashes.length; i++) {
        hash = hashes[i].split('=');
        myJson[hash[0]] = hash[1];
    }
    return myJson;
}

function bindingLink(){
	if(isBinding)return;
	isBinding=true;
	$('a[rel="href_wrapper"], a[rel="home"]').bind('click', function(e){
		e.preventDefault();
		if($(this).attr('rel')==='href_wrapper'){
			$('a[rel="home"]').attr('rel',"href_wrapper");
			$(this).attr('rel',"home");
			url_path=[];
			url_path.push({url:$('a[rel="home"]').attr('href'),name:$('a[rel="home"]').attr('name')});
			createLinkPath();
		}
		var link = $(this).attr('href');
		$('#wrapper').empty().load(link);
	});
	$('a[rel="href_back"]').bind('click', function(e){
		e.preventDefault();
		var link = $(this).attr('href');
		var pos = parseInt($(this).attr('value'));
		while(url_path.length>pos){
			console.log(url_path.length+" "+pos);
			console.log('url_path.length1 '+url_path.length);
			url_path.pop();
			console.log('url_path.length2 '+url_path.length);
		}

		$('#wrapper').empty().load(url_path[url_path.length-1].url);
		createLinkPath();
		console.log('url_path.length3 '+url_path.length);
	});
	isBinding=false;
}

function createLinkPath(){
	var linkHtml ="";
	if(url_path.length==1){
		linkHtml='<li>'+url_path[0].name+'</li>';
	}else{
		linkHtml='<li><a href="'+url_path[0].url+'" rel="href_back" name="'+url_path[0].name+'" value="1">'+url_path[0].name+'</a></li>';
		for (var i = 1; i < url_path.length-1; i++){
			linkHtml='<li><a href="'+url_path[i].url+'" rel="href_back" name="'+url_path[i].name+'" value="'+(i+1)+'">'+url_path[i].name+'</a></li>'+'<li>&gt;</li>'+linkHtml;
		}
		linkHtml='<li>'+url_path[url_path.length-1].name+'</li>'+'<li>&gt;</li>'+linkHtml;
	}
	$('#linkPath').html(linkHtml);
	
	for(var i = 0; i < url_path.length-1; i++){
		console.log(url_path[i].name+" "+url_path[i].url);
	}
	bindingLink();
}



