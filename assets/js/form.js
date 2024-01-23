
document.addEventListener("DOMContentLoaded", function() {

  var savedFormValues = localStorage.getItem("formValues");

  if (savedFormValues) {

    var formValues = JSON.parse(savedFormValues);

    for (var key in formValues) {
      if (formValues.hasOwnProperty(key)) {
        document.getElementById(key).value = formValues[key];
      }
    }
  }
});

function saveFormValues() {
  var formElements = document.forms["myForm"].elements;
  var formValues = {};


  for (var i = 0; i < formElements.length; i++) {
    formValues[formElements[i].id] = formElements[i].value;
  }


  localStorage.setItem("formValues", JSON.stringify(formValues));
}

