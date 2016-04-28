window.onload = function() {
	//单打
	var issm = document.getElementById("single-man");
	var issw = document.getElementById("single-woman");
	singleCheck(issm, issw);
	
	//双打
	var isdm = document.getElementById("double-man");
	var isdw = document.getElementById("double-woman");
	singleCheck(isdm, isdw);

	var partnerInfo = document.getElementById("partner-info");	
	setRelevant(isdm, partnerInfo);
	setRelevant(isdw, partnerInfo);
	
	//男单女双 男双女单也要互斥
	singleCheck(issm, isdw);
	singleCheck(issw, isdm);
	
	//混双
	var ismix = document.getElementById("mix");
	var mixPartnerInfo = document.getElementById("mix-partner-info");
	setRelevant(ismix, mixPartnerInfo);
	
	//提交时检查
	var playerForm = document.getElementById("popup-player-form");
	playerForm.onsubmit = function() {
		var typeSelected = issm.checked || issw.checked || isdm.checked || isdw.checked || ismix.checked;
		if (typeSelected == false) {
			alert("请选择至少一个参赛项目。");
			return false;
		}
		if (!this.checkValidity()) {
			alert("报名表不符合要求！请重新检查后提交！");
			return false;
		}
		//student-id 和 partner-id, mix-partner-id 不能一样
		var inputs = playerForm.getElementsByTagName("input");
		if ( inputs[1].value == inputs[9].value || inputs[1].value == inputs[11].value ) {
			alert("本人学号与搭档学号重复！");
			return false;
		}
		
		var confirmInfo = "你的报名信息如下：\r\n姓名： " + inputs[0].value + "\r\n学号： " + inputs[1].value
											+ "\r\n手机号： " + inputs[2].value + "\r\n报名项目：\r\n";
		if ( issm.checked ) {
			confirmInfo += "  **男单\r\n";
		}
		if ( issw.checked ) {
			confirmInfo += "  **女单\r\n";
		}
		if ( isdm.checked ) {
			confirmInfo += "  **男双 搭档： " + inputs[8].value + "\r\n";
		}
		if ( isdw.checked ) {
			confirmInfo += "  **女双 搭档： " + inputs[8].value + "\r\n";
		}
		if ( ismix.checked ) {
			confirmInfo += "  **混双 搭档： " + inputs[10].value + "\r\n";
		}
		confirmInfo += "请仔细核对后点击确认提交，如需修改点击取消返回";
		
		if ( confirm( confirmInfo ) ) {
			return true;
		} else {
			return false;
		}
	};
}

function setRelevant(check, form) {
	check.addEventListener("click", function() {
		if (this.checked == false) {
			form.setAttribute("hidden", "hidden");
			form.removeAttribute("required");
			clearInputs(form);
		} else {
			form.removeAttribute("hidden");
			form.setAttribute("required", "required");
		}
	}, false);
}

function clearInputs(form) {
	var inputs = form.getElementsByTagName("input");
	for (var i = 0; i < inputs.length; i++)
	{
		inputs[i].value = "";
	}
}

function singleCheck(firstCheck, secondCheck) {
	firstCheck.addEventListener("click", function() {
		if ( ( firstCheck.checked == true ) && ( secondCheck.checked == true ) ) {
			secondCheck.click();
		}
	}, false);
	secondCheck.addEventListener("click", function() {
		if ( ( firstCheck.checked == true ) && ( secondCheck.checked == true ) ) {
			firstCheck.click();
		}
	}, false);
}
