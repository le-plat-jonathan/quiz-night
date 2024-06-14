export async function client(url, options = {}) {
  const { data, method, session, signal, customConfig } = options;

  const config = {
    method: method ?? (data ? "POST" : "GET"),
    body: data ? JSON.stringify(data) : null,
    headers: {
      "Content-Type": data ? "application/json" : "",
      Accept: "application/json",
      Authorization: session,
    },
    signal,
    ...customConfig,
  };

  return fetch(url, config).then(async (response) => {
    // on gère le status 401
    if (response.status === 401) {
      return Promise.reject(new Error("You're not authenticated"));
    }

    let result = null;
    try {
      result = response.status === 204 ? null : await response.json();
    } catch (error) {
      return Promise.reject(new Error("Failed to parse JSON response"));
    }

    if (response.ok) {
      return result;
    } else {
      return Promise.reject(result);
    }
  });
}
