window.onload = function () {
	//男双
	var double_man = document.getElementById("double_man");
	var manpartner = document.getElementById("manpartner");
	//女双
	var double_woman = document.getElementById("double_woman");
	var womanpartner = document.getElementById("womanpartner");
	var eles=document.getElementsByClassName("radio");
	
	prepareRadio(double_man,manpartner);
	prepareRadio(double_woman,womanpartner);

	function prepareRadio(radio, box) {
		
		var flag = true;
		radio.addEventListener("click", function() {
			flag = (flag == true)?false:true;
			if (flag == true) {
				this.checked = false;
				box.setAttribute("hidden", "hidden");
			} else {
				box.removeAttribute("hidden");
			}
		}, false);
	}
}
