window.onload = function () {
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
	
	var confirmApplyButton = document.getElementById("confirmApply");
	confirmApplyButton.addEventListener("click", function() {
		popup("confirm_apply.html");
		return false;
	});
	
	var scheduleButton = document.getElementById("schedule");
	scheduleButton.addEventListener("click", function() {
		window.open("schedule.html");
	})
	
	var playerAppliedRefresh = document.querySelector("#player-applied .octicon-sync");
	playerAppliedRefresh.addEventListener("click", function() {
		
	})
	
}

function popup(winURL) {
  window.open(winURL,"popup","width=480,height=640");
}
