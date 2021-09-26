/**
 * @param {string} login user's login
 * @param {string} token CSRF-token
 * @returns object
 */
export async function getAllSettings(login, token) {
  return fetch("http://localhost/jsoned/api/functions/user_settings/", {
    headers: {
      "X-CSRF-Token": token,
      "X-USER-Login": login
    },
    method: "GET"
  });
}

/**
 * @param {string} setting name of the setting
 * @param {string} login user's login
 * @param {string} token CSRF-token
 * @returns object
 */
export async function getSetting(setting, login, token) {
  return fetch("http://localhost/jsoned/api/functions/user_settings/" + setting, {
    headers: {
      "X-CSRF-Token": token,
      "X-USER-Login": login
    },
    method: "GET"
  });
}

/**
 * @param {object} data { "count_of_docs" | "smoothness_of_anims" }
 * @param {string} login user's login
 * @param {string} token CSRF-token
 * @returns object
 */
export async function updateSettings(data, login, token) {
  return fetch("http://localhost/jsoned/api/functions/user_settings/", {
    headers: {
      "X-CSRF-Token": token,
      "X-USER-Login": login
    },
    method: "PATCH",
    body: data
  });
}

/**
 * @param {string} login user's login
 * @param {string} token CSRF-token
 * @returns object
 */
export async function resetSettings(login, token) {
  return fetch("http://localhost/jsoned/api/functions/user_settings/", {
    headers: {
      "X-CSRF-Token": token,
      "X-USER-Login": login
    },
    method: "DELETE"
  });
}