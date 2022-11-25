//0->New Password, 1->Confirm Password, 2->Empty.
var helpArray = ["Enter a password with these requirements in mind:<br>&emsp; 1. 8 characters minimum.<br>&emsp; 2. At least 1 special character.<br>&emsp; 3. At least 1 uppercase character.<br>&emsp; 4. At least 1 lowercase character.<br>&emsp; 5. At least 1 number.<br>",
"Re-enter the same passsword you entered just now to confirm password.", ""];

var helpTextPass, helpTextConfirmPass;

function init()
{
  helpTextPass = document.getElementById("passHelp");
  regListener(helpTextPass, document.getElementById("password"), 0);

  helpTextConfirmPass = document.getElementById("confirmPassHelp");
  regListener(helpTextConfirmPass, document.getElementById("confirmPassword"), 1);
}

function regListener(element, object, msgIndx)
{
  object.addEventListener("focus", function() {element.innerHTML = helpArray[msgIndx]; element.style.display = "block";}, false);
  object.addEventListener("blur", function() {element.innerHTML = helpArray[2]; element.style.display = "none";}, false);
}

window.addEventListener("load", init, false);
