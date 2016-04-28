window.onload = function() {
	var confirmForm = document.getElementById("popup-confirm-form");
	confirmForm.onsubmit = function () {
		if (!this.checkValidity()) {
			alert("请输入有效的姓名和学号");
			return false;
		}
		return true;
	}
}