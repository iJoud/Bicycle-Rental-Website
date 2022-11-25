//0->First Name, 1->Last Name, 2->Email, 3->Password, 4->Confirm Password, 5->Description, 6->Empty.
var helpArray = ["Enter your first name (forename).", "Enter your last name (surname).", "Enter your e-mail adress in the format shown in the box.",
  "Enter a password with these requirements in mind:<br>&emsp; 1. 8 characters minimum.<br>&emsp; 2. At least 1 special character.<br>&emsp; 3. At least 1 uppercase character.<br>&emsp; 4. At least 1 lowercase character.<br>&emsp; 5. At least 1 number.<br>",
  "Re-enter the same passsword you entered just now to confirm password.", "If you have any disability/ies, describe it/them.", ""];

var helpTextFName, helpTextLName, helpTextEmail, helpTextPass, helpTextConfirmPass, helpTextDesc, availableLocations, availableLocations;

function init() {
  helpTextFName = document.getElementById("fnameHelp");
  regListener(helpTextFName, document.getElementById("fname"), 0);

  helpTextLName = document.getElementById("lnameHelp");
  regListener(helpTextLName, document.getElementById("lname"), 1);

  helpTextEmail = document.getElementById("emailHelp");
  regListener(helpTextEmail, document.getElementById("email"), 2);

  helpTextPass = document.getElementById("passHelp");
  regListener(helpTextPass, document.getElementById("password"), 3);

  helpTextConfirmPass = document.getElementById("confirmPassHelp");
  regListener(helpTextConfirmPass, document.getElementById("confirmPassword"), 4);

  helpTextDesc = document.getElementById("descHelp");
  regListener(helpTextDesc, document.getElementById("deccription"), 5);

  availableLocations = document.getElementById("availableLocations");
  displayLocations(availableLocations, document.getElementById("displayLocations"));

  availableLocations = document.getElementById("availableLocations");
  displayLocations(availableLocations, document.getElementById("displayLocations"));

}

function regListener(element, object, msgIndx) {
  object.addEventListener("focus", function () { element.innerHTML = helpArray[msgIndx]; element.style.display = "block"; }, false);
  object.addEventListener("blur", function () { element.innerHTML = helpArray[6]; element.style.display = "none"; }, false);

}



// doesn't work on Mac OS google chrome
function displayLocations(element, object) {
  element.addEventListener("dblclick", function () {
     if(object.style.display=="none"){
      object.style.display = "block";
    } else {
      object.style.display = "none";
    }}, false);
}


window.addEventListener("load", init, false);

