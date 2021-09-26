
export async function getPublicConfig() {
  try {
    const RESP = await fetch("http://localhost/jsoned/get_config");
    const DATA = await RESP.json();
    
    return DATA;
  }
  catch(e) {
    console.error(e);
    return false;
  }
}

export function saveCookies(array) {
  array.forEach(el => {
    document.cookie = el + ";  max-age=" + 3600 * 24 * 30;
  })
}

export function uuidv4() {
  return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
    var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
    return v.toString(16);
  });
}

export function formURL(path) {
  // const DATA = getPublicConfig();
  const DATA = {
    "title": "jsoned",
    "transfer_protocol": "http",
    "root": "jsoned",
    "domain_name": "localhost"
  };
  return DATA["transfer_protocol"] + "://" + DATA["domain_name"] + "/" + DATA["root"] + "/" + path;
}

export function Redirect(path) {
  // document.location.href = formURL(path);
  document.location.href = "http://localhost/jsoned/" + path;
}

export function updateWindow(){
  document.location.reload();
}

export function getTokenCSRF(){
  return document.querySelector("meta[name=csrf-token]").content;
}
