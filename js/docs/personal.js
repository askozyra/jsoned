import * as Functions from "../functions.js";
import * as UserSettings from "../classes/user_settings.js";
import * as Users from "../classes/users.js";

const EMAIL_FIELD               = document.querySelector("input[name=email]")
const DESCRIPTION_FIELD         = document.querySelector("textarea[name=description]")
const OLD_PASS_FIELD            = document.querySelector("input[name=old_pass]");
const NEW_PASS_1_FIELD          = document.querySelector("input[name=new_pass]");
const NEW_PASS_2_FIELD          = document.querySelector("input[name=new_pass_repeat]");
const COUNT_OF_DOCS_FIELD       = document.querySelector("input[name=count_of_docs]");

const SAVE_EMAIL_BTN            = document.querySelector("input[name=email_btn]");
const SAVE_DESCRIPTION_BTN      = document.querySelector("input[name=description_btn]");
const PASS_BTN                  = document.querySelector("input[name=password_btn]");
const DEL_ACCOUNT_BTN           = document.querySelector("input[name=delete_account_btn]");
const CHANGE_COUNT_OF_DOCS_BTN  = document.querySelector("input[name=count_of_docs_btn]");
const SMOOTHNESS_OF_ANIMS_CHK   = document.querySelector("input[name=smoothness_of_anims_chk]");

SAVE_EMAIL_BTN.addEventListener("click", event => {
  event.preventDefault();
  
  const REGEXP = /\S+@\S+\.\D{2,4}/i;
  if(EMAIL_FIELD.value.match(REGEXP) || EMAIL_FIELD.value === "") {
    const data = JSON.stringify({ "email": EMAIL_FIELD.value });
    Users.updateUser(data, USER_LOGIN, Functions.getTokenCSRF()).then(resp => {
      if(resp.ok) {
        resp.json().then(data => {
          formMessage({ "message": "Email successfully updated", "type": "success" });
        });
      } else {
        formMessage({ "message": "Please, reload the page and try again", "type": "error" });
      }
    });
  } else {
    formMessage({ "message": "Invalid email structure", "type": "error" });
  }
});

SAVE_DESCRIPTION_BTN.addEventListener("click", event => {
  event.preventDefault();
  
  const data = JSON.stringify({ "description": DESCRIPTION_FIELD.value });
    Users.updateUser(data, USER_LOGIN, Functions.getTokenCSRF()).then(resp => {
      if(resp.ok) {
        resp.json().then(data => {
          formMessage({ "message": "Description successfully updated", "type": "success" });
        });
      } else {
        formMessage({ "message": "Please, reload the page and try again", "type": "error" });
      }
    });
});

PASS_BTN.addEventListener("click", event => {
  event.preventDefault();

  if(!(NEW_PASS_1_FIELD.value).length || !(OLD_PASS_FIELD.value).length) {
    formMessage({ "message": "Please, enter data in all form fields", "type": "error" });
    return;
  }

  if(NEW_PASS_1_FIELD.value === NEW_PASS_2_FIELD.value) {
    const data = JSON.stringify({ "password": { "old": OLD_PASS_FIELD.value, "new": NEW_PASS_1_FIELD.value } });
    Users.updateUser(data, USER_LOGIN, Functions.getTokenCSRF()).then(resp => {
      if(resp.ok) {
          resp.json().then(data => {
              formMessage({ "message": "Password successfully updated", "type": "success" });
              OLD_PASS_FIELD.value = NEW_PASS_1_FIELD.value = NEW_PASS_2_FIELD.value = "";
          });
      } else {
        formMessage({ "message": "Incorrect old password", "type": "error" });
      }
    });
  } else {
    formMessage({ "message": "Incorrectly repeated password", "type": "error" });
  }
});

DEL_ACCOUNT_BTN.addEventListener("click", event => {
  event.preventDefault();

  if(confirm("Are you sure want to delete your account? (this action is irrevocable)")) {
    
    Users.deleteUser(USER_LOGIN, Functions.getTokenCSRF());
  } else {
    formMessage({ "message": "Account deleting process has been stopped", "type": "success" });
  }
});

CHANGE_COUNT_OF_DOCS_BTN.addEventListener("click", event => {
  event.preventDefault();

  const data = JSON.stringify({ "count_of_docs": COUNT_OF_DOCS_FIELD.value });
  UserSettings.updateSettings(data, USER_LOGIN, Functions.getTokenCSRF()).then(resp => {
    if(resp.ok) {
        resp.json().then(data => {
            formMessage({ "message": "Count of documents successfully updated", "type": "success" });
        });
    } else {
      formMessage({ "message": "Please, reload the page and try again", "type": "error" });
    }
  });
});

SMOOTHNESS_OF_ANIMS_CHK.addEventListener("click", event => {
  const data = JSON.stringify({ "smoothness_of_anims": SMOOTHNESS_OF_ANIMS_CHK.checked ? 1 : 0 });
  UserSettings.updateSettings(data, USER_LOGIN, Functions.getTokenCSRF()).then(resp => {
    if(resp.ok) {
        resp.json().then(data => {
            formMessage({ "message": "Smoothness successfully updated", "type": "success" });
        });
    } else {
      formMessage({ "message": "Please, reload the page and try again", "type": "error" });
    }
  });
});