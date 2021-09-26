catchServerMessage();

async function catchServerMessage() {
  try {
    const RESP = await fetch("http://localhost/jsoned/get_status");
    const DATA = await RESP.json();
    
    if(Object.keys(DATA).length) {
      formMessage({ "message": DATA["message"], "type": DATA["type"] })
    }
  } catch(e) {}
}

function isMessageWrapperExist() {
  const MESSAGE_WRAPPER = document.querySelector("#message_wrapper");
  return MESSAGE_WRAPPER != undefined;
}

function createMessageWrapper() {
  if(isMessageWrapperExist()) {
    const MESSAGE_WRAPPER = document.querySelector("#message_wrapper");
    return MESSAGE_WRAPPER;
  }
  else {
    const MESSAGE_WRAPPER = document.createElement("div");
    MESSAGE_WRAPPER.setAttribute("id", "message_wrapper");
    MESSAGE_WRAPPER.setAttribute("class", "message_wrapper");
    return MESSAGE_WRAPPER;
  }
}

function formMessage(status) {
  if(!isMessageWrapperExist()) { 
    const MESSAGE_WRAPPER = createMessageWrapper();
    document.body.insertBefore(MESSAGE_WRAPPER, document.body.firstChild);
  }
  const MESSAGE_WRAPPER = document.querySelector("#message_wrapper");

  const DIV = document.createElement("div");
  DIV.setAttribute("class", "auth_message auth_message-" + status.type);
  DIV.innerHTML = status.message;
  MESSAGE_WRAPPER.insertBefore(DIV, MESSAGE_WRAPPER.firstChild);

  const MESSAGE = MESSAGE_WRAPPER.querySelector(".auth_message");
  MESSAGE.classList.add("auth_message-show");
  
  setTimeout(() => {
    MESSAGE.classList.remove("auth_message-show");
    MESSAGE.classList.add("auth_message-hide");

    MESSAGE.addEventListener("animationend", event => {
      MESSAGE_WRAPPER.removeChild(MESSAGE);

      if(MESSAGE_WRAPPER.firstChild == undefined) {
        document.body.removeChild(MESSAGE_WRAPPER);
      }
    });
  }, 3000);
}

function isEmptyResponse(str) {
  try{
    JSON.parse(str);
  } catch(e) {
    return true;
  }
  return false;
}

async function getStatus() {
  return await fetch("http://localhost/curl2/status_code.php");
}