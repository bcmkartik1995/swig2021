function navigate2(pageLink)
{	
	window.location.href = pageLink;
}

function setPopupContent(frmId, title, msg)
{	
	// Here set content to popup
	$("#confirmation_modal_title").html(title);	
	$("#confirmation_modal_msg").html(msg);
	//$("#popBtnTxtDlteReset").html($('#popupBtnTxt'+cntVal).val());	
	$("#confirmation_modal_frmId").val(frmId);	
}

function frmSubmit(frmId)
{
	document.getElementById(frmId).submit(); 
}

function popupSubmtBtnAction()
{
	var frmId = $("#confirmation_modal_frmId").val();
	frmSubmit(frmId);	
}

function linkAction(strCode, stPage)
{	
	console.log(strCode);
	$('#blogCatCodeId').val(strCode);
	$('#stPageID').val(stPage);
	frmSubmit('blogFormID');	
}

function lifeFusioniLinkAction(dirName)
{	
	console.log(dirName);
	$('#dirNameId').val(dirName);
	frmSubmit('lifeFusioniFormID');	
}

function portfolioLinkAction(tabIndexNo, tabIndexKey)
{	
	//alert(tabIndexNo+" "+tabIndexKey);
	console.log(tabIndexNo, tabIndexKey);
	$('#tabIndexNoId').val(tabIndexNo);
	$('#tabIndexKeyId').val(tabIndexKey);
	frmSubmit('portfolioFormID');	
}


function checkDuplicateRecord(fldVal, checkType, enckey, errorContentId)
{   
	if (fldVal.length == 0)
	{
        document.getElementById(errorContentId).innerHTML = "";
        return;
    }
	else
	{
        var xmlhttp = new XMLHttpRequest();
        xmlhttp.onreadystatechange = function()
		{
            if (this.readyState == 4 && this.status == 200)
			{              
				document.getElementById(errorContentId).innerHTML = this.responseText;
            }
        };
		
        xmlhttp.open("POST", "ajex-data.php", true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("fldVal=" + fldVal+"&postAction=checkDuplicateRecord&checkType="+checkType+"&enckey="+enckey);
    }
}

function acceptCookieAction()
{
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function()
	{
		if (this.readyState == 4 && this.status == 200)
		{              
			console.log(this.responseText);
			document.getElementById("cookie_box_id").style.display = "none";
		}
	};

	xmlhttp.open("POST", "controller.php", true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("postAction=acceptCookieAction");
}

function resetFormErrorMsg()
{	
	$(".form_error").text('');

}

function resetFormValErrorMsg(frmId)
{	
	document.getElementById(frmId).reset();
	$(".form_error").text('');	
}

function copyVal(fldVal, copy2Id)
{
	//alert(fldVal)
	$("#"+copy2Id).val(fldVal);
}

function copyValmore2Input(fldVal, fld1, fld2)
{
	//alert(fldVal)
	$("#"+fld1).val(fldVal);
	$("#"+fld2).val(fldVal);
}

function errorMsgSet(strVal)
{	
	if (strVal != '')
    {
        var strObj = JSON.parse(strVal);
        $.each(strObj, function(keys, values)
        {	
            $('#span_' + keys).text(values);
        });
    }
}

function isJson(str)
{
    try 
    {
        JSON.parse(str);
    }
    catch (e) 
     {
        return false;
    }
    return true;
}

function saveTinyMCETxt()
{
    tinyMCE.triggerSave();  
}

function acceptCookies()
{
	createCookie('acceptedUserSiteCookies','Y', 1);
	console.log("Sorry! Yes Web Storage support..");
	document.getElementById("cookie_box_id").style.display = "none";
	//localStorage.setItem("acceptedUserSiteCookies", "Y");
}



function createCookie(cookieName,cookieValue,daysToExpire)
{
  var date = new Date();
  date.setTime(date.getTime()+(daysToExpire*24*60*60*1000));
  document.cookie = cookieName + "=" + cookieValue + "; expires=" + date.toGMTString();
}

function accessCookie(cookieName)
{
  var name = cookieName + "=";
  var allCookieArray = document.cookie.split(';');
  for(var i=0; i<allCookieArray.length; i++)
  {
	var temp = allCookieArray[i].trim();
	if (temp.indexOf(name)==0)
	return temp.substring(name.length,temp.length);
  }
	return "";
}

function checkCookie()
{
  var user = accessCookie("testCookie");
  if (user!="")
	alert("Welcome Back " + user + "!!!");
  else
  {
	user = prompt("Please enter your name");
	num = prompt("How many days you want to store your name on your computer?");
	if (user!="" && user!=null)
	{
	createCookie("testCookie", user, num);
	}
  }
}

function autoFillsTxtBox(txtBxId, clsName)
{
    var data = $("."+clsName).val();   
    //alert(txtBxId);
    var dataArray = data.split('|~~|');
	//alert(dataArray);
    var arrData = new Array();
    
    for(i = 0; i < dataArray.length; i++)
    {
        arrData[i] = dataArray[i];
    }
    
    $("#"+txtBxId).autocomplete({source: arrData, autoFocus: true});
   // alert(arrData);
}
