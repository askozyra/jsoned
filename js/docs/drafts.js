import * as Docs from "../classes/documents.js";
import * as Users from "../classes/users.js";
import * as Functions from "../functions.js";

Users.getAllUsers(USER_LOGIN, Functions.getTokenCSRF());

//#region constants
const EDIT_BTN      = document.querySelector("#action-btn");
const CARDS         = document.querySelectorAll(".card_item");
const ADD_CARD      = document.querySelector("#add_card_item");
//#endregion

//#region event listeners
setActionButtonValue();
addEditBtnClickEvent(EDIT_BTN);
addCardClickEvent(CARDS, ADD_CARD);
addCardHoverEvent(CARDS);

function addEditBtnClickEvent(EDIT_BTN){
  EDIT_BTN.addEventListener("click", (event) => {
    
    const CARD_SELECTED = getSelectedCard();
    const CARD_INDEX    = getCardNumber(CARD_SELECTED);
    const CARD_ID       = getCardID(CARD_INDEX);
    
    if(CARD_ID != -1){
      rollPostsUp();
      crackEditButton();
      setTimeout(() => {
        Functions.Redirect("edit/" + CARD_ID);
        // document.location.href = "http://localhost/jsoned/edit/" + CARD_ID;
      }, 1000);
    } else {
      alert("Please, select a card!");
    }
  });
}

function addCardHoverEvent(CARDS){
  CARDS.forEach(CARD => {
    const SETTINGS_CONTROL = CARD.querySelector(".card_controls");

    const INDEX = getCardNumber(CARD);

    SETTINGS_CONTROL.addEventListener("click", event => {
      toggleSettings(SESSION_CARDS_LIST[INDEX], INDEX);
      event.stopPropagation();
    });
    CARD.addEventListener("mouseover", event => {
      showCardControl(SETTINGS_CONTROL);
    });
    CARD.addEventListener("mouseout", event => {
      hideCardControl(SETTINGS_CONTROL);
    });
  });
}

function addCardClickEvent(CARDS, ADD_CARD){
  if(CARDS)
    CARDS.forEach(CARD => {
      CARD.addEventListener("click", event => {
        const JSON_ARRAY = Array.from(CARDS);
        const ITEM_INDEX = JSON_ARRAY.indexOf(CARD);
        
        if(switchCardItemSelection(CARDS, ITEM_INDEX)){
          showEditButton();
        } else {
          hideEditButton();
        }
      });
    });
  
  if(ADD_CARD)
    ADD_CARD.addEventListener("click", event => {
      const TITLE = prompt("The title of the document:", "some title 1");
      
      if(!TITLE)
        return;

        const DOCUMENT = JSON.stringify({
          "title": TITLE,
          "visibility": "private",
          "payload": "{}"
        });
        Docs.createDocument(DOCUMENT, USER_LOGIN, Functions.getTokenCSRF()).then(resp => {
          if(resp.ok) {
            Functions.updateWindow();
          } else { 
            formMessage({ "message": "Please, reload the page and try again", "type": "error" });
          }
        });
    });
}
//#endregion

//#region functions
function setActionButtonValue() {
  EDIT_BTN.innerHTML = "E D I T";
}

function getCardNumber(CARD){
  const REG = /№: (\d+)/;
  const NUMBER = (CARD.innerHTML).match(REG)[1];
  
  return NUMBER;
}

function showPopup(popup) {
  document.body.insertBefore(popup, document.body.firstChild);
  document.body.firstChild.classList.add("popup-show");
  popup.firstChild.classList.add("popup_content-show");
}

function hidePopup(popup) {
  popup.classList.remove("popup-show");
  popup.classList.add("popup-hide");
  popup.firstChild.classList.remove("popup_content-show");
  popup.firstChild.classList.add("popup_content-hide");
  popup.addEventListener("animationend", event => {
    popup.remove();
  });
}

function toggleSettings(CARD, INDEX) {
  const firstOption = CARD["visibility"] == "public" ? "public" : "private";
  const secondOption = firstOption == "public" ? "private" : "public";

  const SETTINGS_WRAPPER = document.createElement("div");
  SETTINGS_WRAPPER.setAttribute("class", "popup_wrapper");
  SETTINGS_WRAPPER.innerHTML =
  "<div class=\"popup_content card_settings\">"
+    "<div class=\"card_settings_info\">"
+      "<p>№: " + INDEX + "</p>"
+      "<p>public id: " + CARD["id"] + "</p>"
+      "<p>author: " + CARD["author"] + "</p>"
+      "<p>last edit: " + CARD["modifyAt"] + "</p>"
+      "<p>created: " + CARD["createAt"] + "</p>"
+    "</div>"
+    "<ul class=\"card_settings_list\">"
+      "<li class=\"card_settings_item\">"
+        "<label>Title: <input id=\"settings_title\" class=\"flat_input\" type=\"text\" value=\"" + CARD["title"] + "\"></label>"
+      "</li>"
+      "<li class=\"card_settings_item\">"
+        "<label>Privacy:</label>"
+        "<select id=\"settings_privacy\" class=\"flat_input\" size=\"1\">"
+          "<option class=\"flat_option\" value=\"" + firstOption + "\" selected>" + firstOption + "</option>"
+          "<option class=\"flat_option\" value=\"" + secondOption + "\">" + secondOption + "</option>"
+        "</select>"

+      "</li>"
+      "<li class=\"card_settings_item\"><button id=\"delete_card\" class=\"relief-btn\">D E L E T E</button></li>"
+      "<li class=\"card_settings_item\"><button id=\"save_settings\" class=\"relief-btn\">S A V E</button></li>"
+    "</ul>"
+  "</div>";

  showPopup(SETTINGS_WRAPPER);

  const SETTINGS   = SETTINGS_WRAPPER.querySelector(".card_settings");
  const SAVE_BTN   = SETTINGS.querySelector("#save_settings");
  const DELETE_BTN = SETTINGS.querySelector("#delete_card");
  
  SETTINGS.addEventListener("click", event => {
    event.stopPropagation();
  })
  SETTINGS_WRAPPER.addEventListener("click", event => {
    hidePopup(SETTINGS_WRAPPER);
  });
  DELETE_BTN.addEventListener("click", event => {
    Docs.deleteDocument(CARD["id"], USER_LOGIN, Functions.getTokenCSRF()).then(resp => {
      if(resp.ok) {
        Functions.updateWindow();
      } else {
        formMessage({ "message": "Something went wrong!", "type": "error" });
      }
    });
  });
  SAVE_BTN.addEventListener("click", event => {
    const TITLE = SETTINGS.querySelector("#settings_title").value;
    const PRIVACY = SETTINGS.querySelector("#settings_privacy").value;
    const PAYLOAD = JSON.stringify( { "title" : TITLE, "visibility": PRIVACY } );
  
    Docs.updateDocument(CARD["id"], PAYLOAD, USER_LOGIN, Functions.getTokenCSRF()).then(resp => {
      if(resp.ok) {
        formMessage({ "message": "Changes has been saved!", "type": "success" });
      } else {
        formMessage({ "message": "Something went wrong!", "type": "error" });
      }
    });
  });
}

function showCardControl(CONTROL){
  CONTROL.classList.add("card_controls-visible");
}
function hideCardControl(CONTROL){
  CONTROL.classList.remove("card_controls-visible");
}

function getSelectedCard(){
  const CARD_SELECTED = document.querySelector(".card_item-selected");
  return CARD_SELECTED;
}

function getCardIndex(CARD){
  const JSON_ARRAY = Array.from(CARDS);
  const ITEM_INDEX = JSON_ARRAY.indexOf(CARD);
  return ITEM_INDEX;
}

function getCardID(index){
  return SESSION_CARDS_LIST[index]["id"];
}

function clearCardItemSelection(items_list){
  for(let i = 0; i < items_list.length; i++){
    items_list[i].classList.remove("card_item-selected");
  }
}

function switchCardItemSelection(items, index){
  if(items[index].classList.contains("card_item-selected")){
    items[index].classList.remove("card_item-selected");
    
    return false;
  } else {
    clearCardItemSelection(items);
    
    items[index].classList.add("card_item-selected");
    
    return true;
  }
}

function rollPostsUp(){
  const MAIN = document.querySelector("#main_posts");
  MAIN.classList.add("main-transition");
}

function crackEditButton(){
  EDIT_BTN.classList.remove("btn-show");
  EDIT_BTN.classList.add("btn-crack");
}

function showEditButton(){
  EDIT_BTN.classList.remove("btn-hide");
  EDIT_BTN.classList.add("btn-show");
}

function hideEditButton(){
  EDIT_BTN.classList.remove("btn-show");
  EDIT_BTN.classList.add("btn-hide");
}
//#endregion