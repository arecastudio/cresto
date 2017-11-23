
function NumbersOnly(event) {
    var key = window.event ? event.keyCode : event.which;

	if (event.keyCode == 8 || event.keyCode == 46|| event.keyCode == 37 || event.keyCode == 39) {
	    return true;
	}else if ( key < 48 || key > 57 ) {
	    return false;
	}
	else return true;
};





var popupWindow=null;

function child_open(){ 
popupWindow =window.open('http://localhost/cresto/pages/edit.php',"_blank","directories=no, status=no, menubar=no, scrollbars=yes, resizable=no,width=600, height=280,top=200,left=200");
}

function parent_disable() {
	if(popupWindow && !popupWindow.closed)
	popupWindow.focus();
}


