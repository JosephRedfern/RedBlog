function toggleDisplay(divid){
	var visibility = document.getElementById(divid).style.display;
	if(visibility == "block"){
		document.getElementById(divid).style.display = "none";
	}else{
		document.getElementById(divid).style.display = "block";
	}
}