window.onload = function() {
	var refereeForm = document.getElementById("popup-referee-form");
	refereeForm.onsubmit = function () {
		if (!this.checkValidity()) {
			alert("报名表不符合要求！请重新检查后提交！");
			return false;
		}
		
		var inputs = this.getElementsByTagName("input");
		var confirmInfo = "你的报名信息如下：\r\n姓名： " + inputs[0].value + "\r\n学号： " + 
											inputs[1].value + "\r\n手机号： " + inputs[2].value + 
											"\r\n请仔细核对后点击确认提交，如需修改点击" + "取消返回";
		
		if ( confirm( confirmInfo ) ) {
			return true;
		} else {
			return false;
		}
	};
};