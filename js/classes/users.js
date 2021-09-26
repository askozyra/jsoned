/**
 * @param {string} login user's login
 * @param {string} token CSRF-token
 * @returns object
 */
export async function getAllUsers(login, token) {
  return fetch("http://localhost/jsoned/api/functions/users/", {
    headers: {
      "X-CSRF-Token": token,
      "X-USER-Login": login
    },
    method: "GET"
  });
}

/**
 * @param {string} login user's login
 * @param {string} token CSRF-token
 * @returns object
 */
export async function getUser(login, token) {
  return fetch("http://localhost/jsoned/api/functions/users/" + login, {
    headers: {
      "X-CSRF-Token": token,
      "X-USER-Login": login
    },
    method: "GET"
  });
}

/**
 * @param {object} data { "description" | "email" }
 * @param {string} login user's login
 * @param {string} token CSRF-token
 * @returns object
 */
export async function updateUser(data, login, token) {
  return fetch("http://localhost/jsoned/api/functions/users/" + login, {
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
export async function deleteUser(login, token) {
  return fetch("http://localhost/jsoned/api/functions/users/" + login, {
    headers: {
      "X-CSRF-Token": token,
      "X-USER-Login": login
    },
    method: "DELETE"
  });
}