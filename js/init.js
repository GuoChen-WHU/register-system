﻿window.onload = function () {
	var playerApplyButton = document.getElementById("playerApply");
	playerApplyButton.addEventListener("click", function() {
		popup("form_player.html");
		return false;
	});
	
	var refereeApplyButton = document.getElementById("refereeApply");
	refereeApplyButton.addEventListener("click", function() {
		popup("form_referee.html");
		return false;
	});
}

function popup(winURL) {
  window.open(winURL,"popup","width=480,height=640");
}