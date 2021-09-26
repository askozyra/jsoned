import * as Docs from "../classes/documents.js";
import * as Functions from "../functions.js";

const ROPE = document.getElementById("rope");
const TEXT_EDITOR = document.getElementById("text_editor");
const JSON_VIEWER = document.getElementById("json_viewer");
const SAVE_BTN = document.getElementById("save-btn");

showRope();
fillTextarea().then(() => {
  textToJSON(TEXT_EDITOR, JSON_VIEWER);
});

addTextAreaCallback(TEXT_EDITOR, () => {
  textToJSON(TEXT_EDITOR, JSON_VIEWER);
  SAVE_BTN.disabled = false;
}, 500);

SAVE_BTN.addEventListener("click", event => {
  saveChanges();
});

ROPE.addEventListener("click", event => {
  ropeEventListener();
});

TEXT_EDITOR.addEventListener("keydown", function(event) {
  const KEY = event.key;
  
  const TAB_SIZE = "\t".length;
  const VALUE = this.value;
  
  const START = this.selectionStart;
  const END   = this.selectionEnd;

  switch(KEY) {
    case "Tab":
      this.value = VALUE.substring(0, START) + "\t" + VALUE.substring(END);
      
      this.selectionStart = this.selectionEnd = START + TAB_SIZE;

      event.preventDefault();
      break;
    case "Enter":
      const CURRENT_LINE = VALUE.substr(0, START).split("\n").pop();
      const INDENT = CURRENT_LINE.match(/^\s*/)[0];

      if(isBracketsNeighboor(VALUE, START, END)) {
        this.value = VALUE.substring(0, START) + "\n" + INDENT + "\t" + "\n" + INDENT + VALUE.substring(END);
        this.selectionStart = this.selectionEnd = START + INDENT.length + 2;
      } else {
        this.value = VALUE.substring(0, START) + "\n" + INDENT + VALUE.substring(END);
        this.selectionStart = this.selectionEnd = START + INDENT.length + TAB_SIZE;
      }
      
      event.preventDefault();

      break;
    case "{":
    case "\"":
    case "'":
    case "(":
    case "[":
      const OPEN_SYMBOL = KEY;
      const CLOSE_SYMBOL = getCloseSymbol(OPEN_SYMBOL);

      this.value = VALUE.substring(0, START) + OPEN_SYMBOL + CLOSE_SYMBOL + VALUE.substring(END);
      this.selectionStart = this.selectionEnd = START + 1;

      event.preventDefault();
      break;
  }
});

function prettyPrint(obj) {
  const JSON_KEY = /"(.+)":/gi;
  const JSON_VALUE = / ([a-z\d]+)/gi;
  const JSON_STRING = /"(.+?)"/gi;

  return JSON.stringify(obj, null, "\t")
    .replaceAll(JSON_VALUE, "<span class='json-value'>$1</span>")
    .replaceAll(JSON_KEY, "<span class='json-key'>$1: </span>")
    .replaceAll(JSON_STRING, "<span class=\"json-string\">\"$1\"</span>");
}

function isBracketsNeighboor(str, left, right) {
  const LEFT_SYMBOL = str.substring(left - 1, left);
  const RIGHT_SYMBOL = str.substring(right, right + 1);

  switch(LEFT_SYMBOL) {
    case "{":
      if(RIGHT_SYMBOL == "}")
      return true;
    case "[":
      if(RIGHT_SYMBOL == "]")
      return true;
    case "\"":
      if(RIGHT_SYMBOL == "\"")
      return true;
    case "'":
      if(RIGHT_SYMBOL == "'")
      return true;
    case "(":
      if(RIGHT_SYMBOL == ")")
      return true;
  }

  return false;
}

function getCloseSymbol(OPEN_SYMBOL) {
  switch(OPEN_SYMBOL) {
    case "{":
      return "}";
    case "[":
      return "]";
    case "\"":
      return "\"";
    case "'":
      return "'";
    case "(":
      return ")";
  }
}

async function saveChanges(){
  try{
    const DATA = TEXT_EDITOR.value;
    
    // This line works as json validator (when inside try{})
    JSON.parse(DATA);

    const PAYLOAD = JSON.stringify( { "payload" : DATA } );


    const RESP = await Docs.updateDocument(SELECTED_CARD["id"], PAYLOAD, USER_LOGIN, Functions.getTokenCSRF());

    if(RESP.ok) {
      formMessage({ "message": "Document successfully saved! :)", "type": "success" });
    } else {
      formMessage({ "message": "Incorrect JSON syntax! :(", "type": "error" });
    }
  } catch(e) {
    formMessage({ "message": "Incorrect JSON syntax! :(", "type": "error" });
  }
}

function addTextAreaCallback(textArea, callback, delay) {
  let timer = null;
  textArea.addEventListener("input", function() {
    SAVE_BTN.disabled = true;

    if (timer) {
      window.clearTimeout(timer);
    }
    timer = window.setTimeout(function() {
      timer = null;
      callback();
    }, delay);
  });
  textArea = null;
}

function textToJSON(text, json){
  json.innerHTML = jsonPrettier(text.value, "\t", true);
}

function ropeEventListener(){
  pullRope();
  showPostsTable();
  crackSaveBtn(SAVE_BTN);
  setTimeout(() => {
    Functions.Redirect("drafts");
  }, 1500);
}

function jsonPrettier(string, tab, isFormatting) {
  try{
    let obj = JSON.parse(string);

    if(isFormatting)
      return prettyPrint(obj);
    else
      return JSON.stringify(obj, null, tab);
  } catch(err) {
    return "Syntax error in JSON string";
  }
}

async function fillTextarea(){
  let text = jsonPrettier(SELECTED_CARD["payload"], "\t", false);
  TEXT_EDITOR.value = text;
}

function showRope(){
  ROPE.classList.remove("rope-hidden");
  ROPE.classList.add("rope-visible");
}

function pullRope(){
  ROPE.classList.remove("rope-visible");
  ROPE.classList.add("rope-pulled");
}

function showPostsTable(){
  const BG_MAT = document.querySelector(".background_mat");
  BG_MAT.classList.add("main-transition-reverse");
}

function crackSaveBtn(SAVE_BTN){
  SAVE_BTN.classList.add("btn-crack");
}