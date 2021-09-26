import * as Functions from "../functions.js";
import * as Users from "../classes/users.js";

//#region constants
const VIEW_BTN      = document.querySelector("#action-btn");
const CARDS         = document.querySelectorAll(".card_item");
//#endregion

//#region event listeners
setActionButtonValue();
addEditBtnClickEvent(VIEW_BTN);
addCardClickEvent(CARDS);

function addEditBtnClickEvent(VIEW_BTN){
  VIEW_BTN.addEventListener("click", (event) => {
    
    const CARD_SELECTED = getSelectedCard();
    const CARD_INDEX    = getCardNumber(CARD_SELECTED);
    const CARD_ID       = getCardID(CARD_INDEX);
    
    if(CARD_ID != -1){
      const DATA = SESSION_CARDS_LIST[CARD_INDEX]["payload"];

      const JSON_VIEWER_WRAPPER = document.createElement("div");
      JSON_VIEWER_WRAPPER.setAttribute("class", "popup_wrapper");
      JSON_VIEWER_WRAPPER.innerHTML =
      "<div class=\"popup_content card_payload_viewer\">"
+       "<div id=\"json_viewer\" class=\"json_viewer fullsize\" readonly placeholder=\"output json\">"
+         jsonPrettier(DATA, "\t", true)
+       "</div>"
+     "</div>";

      showPopup(JSON_VIEWER_WRAPPER);

      const JSON_VIEWER = JSON_VIEWER_WRAPPER.querySelector(".popup_content");
      
      JSON_VIEWER.addEventListener("click", event => {
        event.stopPropagation();
      });
      JSON_VIEWER_WRAPPER.addEventListener("click", event => {
        hidePopup(JSON_VIEWER_WRAPPER);
      });
    } else {
      alert("Please, select a card!");
    }
  });
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

function prettyPrint(obj) {
  const JSON_KEY = /"(.+)":/gi;
  const JSON_VALUE = / ([a-z\d]+)/gi;
  const JSON_STRING = /"(.+?)"/gi;

  return JSON.stringify(obj, null, "\t")
    .replaceAll(JSON_VALUE, "<span class='json-value'>$1</span>")
    .replaceAll(JSON_KEY, "<span class='json-key'>$1: </span>")
    .replaceAll(JSON_STRING, "<span class=\"json-string\">\"$1\"</span>");
}

/**
 * @param isFormatting sets HTML tags into returned string if true
 */
 function jsonPrettier(string, tab, isFormatting) {
  try{
    let obj = JSON.parse(string);

    if(isFormatting)
      return prettyPrint(obj);
    else
      return JSON.stringify(obj, null, tab);

  } catch (err){
    return "Syntax error in JSON string";
  }
}

function addCardClickEvent(CARDS){
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
}
//#endregion

//#region functions
function setActionButtonValue() {
  VIEW_BTN.innerHTML = "V I E W";
}

function getCardNumber(CARD){
  const REG = /№: (\d+)/;
  const NUMBER = (CARD.innerHTML).match(REG)[1];
  
  return NUMBER;
}

function getSelectedCard(){
  const CARD_SELECTED = document.querySelector(".card_item-selected");
  return CARD_SELECTED;
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

function showEditButton(){
  VIEW_BTN.classList.remove("btn-hide");
  VIEW_BTN.classList.add("btn-show");
}

function hideEditButton(){
  VIEW_BTN.classList.remove("btn-show");
  VIEW_BTN.classList.add("btn-hide");
}
//#endregion