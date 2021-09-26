/**
 * @param {string} login user's login
 * @param {string} token CSRF-token
 * @returns object
 */
export async function getAllDocuments(login, token) {
  return fetch("http://localhost/jsoned/api/functions/posts/", {
    headers: {
      "X-CSRF-Token": token,
      "X-USER-Login": login
    },
    method: "GET"
  });
}

/**
 * @param {number} card_id the id of the card for which the changes are being made 
 * @param {string} login user's login
 * @param {string} token CSRF-token
 * @returns object
 */
export async function getDocument(card_id, login, token) {
  return fetch("http://localhost/jsoned/api/functions/posts/" + card_id, {
    headers: {
      "X-CSRF-Token": token,
      "X-USER-Login": login
    },
    method: "GET"
  });
}

/**
 * @param {object} data { "title" | "visibility" | "payload" }
 * @param {string} login user's login
 * @param {string} token CSRF-token
 * @returns object
 */
export async function createDocument(data, login, token){
  const formData = new FormData();
  const dataObj = JSON.parse(data);

  for(const key in dataObj) {
    formData.append(key, dataObj[key]);
  }

  return fetch("http://localhost/jsoned/api/functions/posts/", {
    headers: {
      "X-CSRF-Token": token,
      "X-USER-Login": login
    },
    method: "POST",
    body: formData
  });
}

/**
 * @param {number} card_id the id of the card for which the changes are being made 
 * @param {object} data { "title" | "visibility" | "payload" }
 * @param {string} login user's login
 * @param {string} token CSRF-token
 * @returns object
 */
export async function updateDocument(card_id, data, login, token) {
  return fetch("http://localhost/jsoned/api/functions/posts/" + card_id, {
    headers: {
      "X-CSRF-Token": token,
      "X-USER-Login": login
    },
    method: "PATCH",
    body: data
  });
}

/**
 * @param {number} card_id the id of the card for which the changes are being made 
 * @param {string} login user's login
 * @param {string} token CSRF-token
 * @returns object
 */
export async function deleteDocument(card_id, login, token){
  return fetch("http://localhost/jsoned/api/functions/posts/" + card_id, {
    headers: {
      "X-CSRF-Token": token,
      "X-USER-Login": login
    },
    method: "DELETE",
  });
}