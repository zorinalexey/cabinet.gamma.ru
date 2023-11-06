(function () {
  const selectInput = document.querySelectorAll(".custom-select");
  function openSelect(select) {
    select.addEventListener("click", (e) => {
      if (e.target.closest(".custom-select")) {
        const body = select.querySelector(".select-body");
        body.classList.toggle("open");

        body.addEventListener("click", (e) => {
          if (e.target.classList.contains("select-item")) {
            const input = body.parentNode.querySelector("input");
            input.value = e.target.textContent.trim();
          }
        });
      }
    });
  }
  for (let i = 0; i < selectInput.length; i++) {
    openSelect(selectInput[i]);
  }
  function Mask() {
    [].forEach.call(document.querySelectorAll(".tel"), function (input) {
      var keyCode;
      function mask(event) {
        event.keyCode && (keyCode = event.keyCode);
        var pos = this.selectionStart;
        if (pos < 3) event.preventDefault();
        var matrix = "+7 (___) ___ ____",
          i = 0,
          def = matrix.replace(/\D/g, ""),
          val = this.value.replace(/\D/g, ""),
          new_value = matrix.replace(/[_\d]/g, function (a) {
            return i < val.length ? val.charAt(i++) || def.charAt(i) : a;
          });
        i = new_value.indexOf("_");
        if (i != -1) {
          i < 5 && (i = 3);
          new_value = new_value.slice(0, i);
        }
        var reg = matrix
          .substr(0, this.value.length)
          .replace(/_+/g, function (a) {
            return "\\d{1," + a.length + "}";
          })
          .replace(/[+()]/g, "\\$&");
        reg = new RegExp("^" + reg + "$");
        if (
          !reg.test(this.value) ||
          this.value.length < 5 ||
          (keyCode > 47 && keyCode < 58)
        )
          this.value = new_value;
        if (event.type == "blur" && this.value.length < 5) this.value = "";
      }

      input.addEventListener("input", mask, false);
      input.addEventListener("focus", mask, false);
      input.addEventListener("blur", mask, false);
      input.addEventListener("keydown", mask, false);
    });
  }
  Mask();
})();

function check(id) {
  var checkbox = document.getElementById(id);
  let block = document.getElementById("block_" + id);
  if (checkbox.checked !== true) {
    block.style.display = "none";
  } else {
    block.style.display = "block";
  }
}
function checkSec(id) {
  var checkbox = document.getElementById(id);
  let block = document.getElementById("block_" + id);
  if (checkbox.checked !== true) {
    block.classList.remove("green-btn--active");
  } else {
    block.classList.add("green-btn--active");
  }
}

function setGender(gender, button, action) {
  var block = document.getElementById("Gender");
  var btn = document.getElementById("gender-" + action);
  block.value = gender;
  button.classList.remove("btn");
  button.classList.add("green-btn");
  btn.classList.add("btn");
  btn.classList.remove("green-btn");
}

function setFactAddress() {
  var ResidentialAddress = document.getElementById("ResidentialAddress");
  var RegistrationAddress = document.getElementById("RegistrationAddress");
  var factAddress = document.getElementById("factAddress");
  var check = document.getElementById("checkFactAddress");
  if (check.checked) {
    ResidentialAddress.value = RegistrationAddress.value;
    factAddress.style.display = "none";
  } else {
    ResidentialAddress.textContent = "";
    ResidentialAddress.value = "";
    factAddress.style.display = "block";
  }
}

function checkSetAddress() {
  var check = document.getElementById("checkFactAddress");
  if (check.checked) {
    setFactAddress();
  }
}

async function setINN(
  surname,
  name,
  patronymic,
  birthdate,
  docnumber,
  docdate
) {
  const url = "/api/get/inn?";
  const data = {
    fam: surname,
    nam: name,
    otch: patronymic,
    bdate: birthdate,
    bplace: "",
    doctype: 21,
    docno: docnumber,
    docdt: docdate,
    c: "innMy",
    captcha: "",
    captchaToken: "",
  };
  const encoded = encode(data);
  let json = false;
  let response = await fetch(url + encoded);
  if (response) {
    json = await response.json();
    return json;
  }
  return false;
}

function encode(data) {
  let encoded = Object.keys(data)
    .map((key) => encodeURIComponent(key) + "=" + encodeURIComponent(data[key]))
    .join("&");
  return encoded;
}

function formatResult(value, currentValue, suggestion) {
  suggestion.value = suggestion.data.code;
  return suggestion.data.code + " — " + suggestion.data.name;
}

async function getInn(input) {
  let array = input.value.replace(/[^0-9]/g, "").match(/.{1,2}/g);
  let string = "";
  for (let i = 0; i < array.length; i++) {
    if (i < 2) {
      string += array[i] + ".";
    } else if (i < 4) {
      string += array[i];
    }
  }
  let DateOfBirdh = string;
  document.getElementById("DateOfBirdh").value = DateOfBirdh;
  let SurName = document.getElementById("SurName").value;
  let Name = document.getElementById("Name").value;
  let Patronomic = document.getElementById("Patronomic").value;
  let Series = document.getElementById("Series").value;
  let Number = document.getElementById("Number").value;
  let WhenIssued = document.getElementById("WhenIssued").value;
  if (string.length >= 10) {
    let inn = await setINN(
      SurName,
      Name,
      Patronomic,
      DateOfBirdh,
      Series + " " + Number,
      WhenIssued
    );
    if (inn) {
      if (inn.code === 1) {
        document.getElementById("INN").value = inn.inn;
      } else {
        document.getElementById("INN").value = "";
      }
    } else {
      document.getElementById("INN").value = "";
    }
  }
}
