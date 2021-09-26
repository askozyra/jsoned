const FORMS = document.forms;

for(let i = 0; i < FORMS.length; i++) {
  if(FORMS[i]["copy_btn"] == undefined)
    continue;

  const COPY_API_TOKEN_BTN = FORMS[i]["copy_btn"];

  COPY_API_TOKEN_BTN.addEventListener("click", event => {
    const API_TOKEN = FORMS[i]["api_token"];

    (window.getSelection()).removeAllRanges();
    API_TOKEN.select();
    document.execCommand("copy");
    
    const MESSAGE = { "message": "Copied! :)", "type": "success" };
    formMessage(MESSAGE);
  });
}